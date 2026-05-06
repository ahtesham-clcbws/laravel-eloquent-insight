<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;
use EloquentInsight\Storage\BufferInterface;

class CheckCommand extends Command
{
    use HandlesSmartOutput;

    protected $signature = 'insight:check {--strict}';
    protected $description = 'Check for N+1 violations (useful for CI pipelines)';

    public function handle(BufferInterface $buffer): int
    {
        $violations = $buffer->all();

        if (empty($violations)) {
            $this->info('No N+1 violations detected. Your Eloquent queries are clean!');
            return 0;
        }

        $count = count($violations);
        $this->buffer("--- CI Check: {$count} Violations Found ---");

        foreach ($violations as $v) {
            $this->buffer(" - {$v['model']} -> {$v['relation']} at {$v['entry_point']}");
        }

        $this->finalizeOutput('ci_check_results');

        if ($this->option('strict')) {
            $this->error("\nFAILED: {$count} N+1 violations detected.");
            return 1;
        }

        return 0;
    }
}
