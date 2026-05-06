<?php

namespace EloquentInsight\Compilers;

use Illuminate\Support\Facades\File;
use EloquentInsight\Storage\BufferInterface;
use EloquentInsight\Engines\HeuristicEngine;

class ManifestCompiler
{
    public function __construct(
        protected BufferInterface $buffer,
        protected HeuristicEngine $heuristicEngine
    ) {}

    /**
     * Compile the violation buffer into a static PHP manifest.
     */
    public function compile(): string
    {
        $violations = $this->buffer->all();
        $insights = $this->heuristicEngine->analyze($violations);

        $manifest = [];
        foreach ($insights as $insight) {
            $manifest[$insight['entry_point']][$insight['model']] = $insight['relations'];
        }

        $path = $this->getManifestPath();
        $content = "<?php" . PHP_EOL . PHP_EOL . "return " . var_export($manifest, true) . ";" . PHP_EOL;

        File::put($path, $content);

        return $path;
    }

    /**
     * Get the path to the cached manifest file.
     */
    public function getManifestPath(): string
    {
        return base_path('bootstrap/cache/eloquent_insight.php');
    }
}
