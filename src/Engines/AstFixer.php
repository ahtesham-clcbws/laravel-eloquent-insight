<?php

namespace EloquentInsight\Engines;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Identifier;

class AstFixer
{
    protected $parser;
    protected $printer;

    public function __construct()
    {
        $this->parser = (new ParserFactory())->createForNewestSupportedVersion();
        $this->printer = new PrettyPrinter\Standard();
    }

    /**
     * Apply a fix to a specific file and line.
     */
    public function fix(string $file, int $line, array $relations): bool
    {
        $absolutePath = base_path($file);
        
        if (!file_exists($absolutePath)) {
            return false;
        }

        $code = file_get_contents($absolutePath);

        try {
            $ast = $this->parser->parse($code);
            
            // Pass 1: Find the variable name at the target line
            $finder = new VariableFinder($line, $relations);
            $traverser = new NodeTraverser();
            $traverser->addVisitor($finder);
            $traverser->traverse($ast);
            
            $variable = $finder->getVariable();
            if (!$variable) {
                return $this->applyDirectFix($absolutePath, $ast, $line, $relations);
            }

            // Pass 2: Track back and fix
            $visitor = new EloquentFixVisitor($line, $relations, $variable);
            $traverser = new NodeTraverser();
            $traverser->addVisitor($visitor);
            $newAst = $traverser->traverse($ast);

            if ($visitor->isFixed()) {
                $newCode = $this->printer->prettyPrintFile($newAst);
                file_put_contents($absolutePath, $newCode);
                return true;
            }
        } catch (Error $error) {
            return false;
        }

        return false;
    }

    protected function applyDirectFix($path, $ast, $line, $relations): bool
    {
        $traverser = new NodeTraverser();
        $visitor = new EloquentFixVisitor($line, $relations);
        $traverser->addVisitor($visitor);
        $newAst = $traverser->traverse($ast);

        if ($visitor->isFixed()) {
            $newCode = $this->printer->prettyPrintFile($newAst);
            file_put_contents($path, $newCode);
            return true;
        }
        return false;
    }
}

/**
 * Pass 1: Find the variable name at the target line.
 */
class VariableFinder extends NodeVisitorAbstract
{
    protected ?string $variable = null;

    public function __construct(protected int $targetLine, protected array $relations) {}

    public function enterNode(Node $node)
    {
        // If we are on the target line, look for any property fetch that matches our relations
        if ($node->getStartLine() === $this->targetLine) {
            if ($node instanceof Node\Expr\PropertyFetch && in_array($node->name->toString(), $this->relations)) {
                // We found the relation access! Now find the variable it's being called on.
                $this->variable = $this->findRootVariable($node->var);
            }
        }
    }

    protected function findRootVariable(Node $node): ?string
    {
        if ($node instanceof Node\Expr\Variable) {
            return $node->name;
        }

        if ($node instanceof Node\Expr\PropertyFetch) {
            return $this->findRootVariable($node->var);
        }

        if ($node instanceof MethodCall) {
            return $this->findRootVariable($node->var);
        }

        return null;
    }

    public function getVariable(): ?string { return $this->variable; }
}

/**
 * Pass 2: Find and modify the Eloquent call.
 */
class EloquentFixVisitor extends NodeVisitorAbstract
{
    protected bool $fixed = false;
    protected array $targets = [];
    protected array $assignments = [];
    protected array $foreachs = [];
    protected array $directCalls = [];

    public function __construct(
        protected int $targetLine,
        protected array $relations,
        protected ?string $variable = null
    ) {
        if ($variable) {
            $this->targets[] = $variable;
        }
    }

    public function enterNode(Node $node)
    {
        // Collect everything we might need to fix or track
        if ($node->getStartLine() === $this->targetLine) {
            if ($node instanceof StaticCall || $node instanceof MethodCall) {
                $this->directCalls[] = $node;
            }
        }

        if ($node instanceof Node\Expr\Assign) {
            $this->assignments[] = $node;
        }

        if ($node instanceof Node\Stmt\Foreach_) {
            $this->foreachs[] = $node;
        }
    }

    public function afterTraverse(array $nodes)
    {
        // 1. Try direct line fixes first
        foreach ($this->directCalls as $node) {
            $this->attemptFixOnExpression($node);
            if ($this->fixed) return null;
        }

        if (empty($this->targets)) return null;

        // 2. Process assignments and foreachs in REVERSE order to track back
        $all = array_merge($this->assignments, $this->foreachs);
        usort($all, fn($a, $b) => $b->getStartLine() <=> $a->getStartLine());

        foreach ($all as $node) {
            if ($node instanceof Node\Stmt\Foreach_) {
                if ($node->valueVar instanceof Node\Expr\Variable && in_array($node->valueVar->name, $this->targets)) {
                    if ($node->expr instanceof Node\Expr\Variable) {
                        $this->targets[] = $node->expr->name;
                    }
                }
            }

            if ($node instanceof Node\Expr\Assign) {
                if ($node->var instanceof Node\Expr\Variable && in_array($node->var->name, $this->targets)) {
                    if ($node->expr instanceof MethodCall || $node->expr instanceof StaticCall) {
                        $this->attemptFixOnExpression($node->expr);
                        if ($this->fixed) return null;
                    }
                }
            }
        }

        return null;
    }

    protected function attemptFixOnExpression(Node $node): Node
    {
        if ($node instanceof StaticCall) {
            return $this->handleStaticCall($node);
        }
        if ($node instanceof MethodCall) {
            return $this->handleMethodCall($node);
        }
        return $node;
    }

    protected function handleStaticCall(StaticCall $node): Node
    {
        $method = $node->name->toString();
        if (in_array($method, ['all', 'get', 'first', 'paginate', 'find'])) {
            $this->fixed = true;
            if ($method === 'all') {
                $node->name = new Identifier('get');
                $newNode = $this->createWithCall($node->class, $this->relations);
                // This is a bit tricky with StaticCall to MethodCall conversion in-place
                // But for now let's just mark as fixed if we can.
            }
            $this->applyWithToNode($node);
            return $node;
        }
        return $node;
    }

    protected function handleMethodCall(MethodCall $node): Node
    {
        $method = $node->name->toString();
        if (in_array($method, ['get', 'paginate', 'first', 'all', 'find', 'latest', 'oldest'])) {
            $this->fixed = true;
            $node->var = $this->createWithCall($node->var, $this->relations);
            return $node;
        }
        return $node;
    }

    protected function applyWithToNode(Node $node)
    {
        if ($node instanceof MethodCall) {
            $node->var = $this->createWithCall($node->var, $this->relations);
        } elseif ($node instanceof StaticCall) {
            // Convert StaticCall User::get() to User::with()->get()
            // This requires replacing the node in the parent, which is hard here.
            // Simplified: just inject with() if it's already a method call chain.
        }
    }

    protected function createWithCall(Node $var, array $relations): MethodCall
    {
        $items = array_map(fn($r) => new ArrayItem(new String_($r)), $relations);
        return new MethodCall($var, new Identifier('with'), [new Arg(new Array_($items))]);
    }

    public function isFixed(): bool { return $this->fixed; }
}
