<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;

class FixViolationsCommand extends Command
{
    protected $signature = 'insight:fix {--all} {--diff}';
    protected $description = 'Automatically fix captured N+1 violations via AST manipulation';

    public function handle(): int
    {
        $solutions = app('insight')->getLatestSolutions();

        if (empty($solutions)) {
            $this->info('No violations to fix.');
            return 0;
        }

        $this->info('Found ' . count($solutions) . ' actionable insights:');

        foreach ($solutions as $solution) {
            $this->line('');
            $this->warn("Point: {$solution['point']} (N+1)");
            $this->info("  Summary: {$solution['summary']}");
            $this->comment("  Suggested Fix: {$solution['fix']}");

            if (!$this->option('all')) {
                if (!$this->confirm('Would you like to apply this fix automatically?')) {
                    continue;
                }
            }

            $success = app(\EloquentInsight\Engines\AstFixer::class)->fix(
                $solution['file'],
                $solution['line'],
                $solution['relations']
            );

            if ($success) {
                $this->info('  [✓] Fix applied successfully.');
                app('insight')->clear($solution['point'], $solution['model']);
            } else {
                $this->error('  [!] Failed to apply fix. It might already be fixed or requires manual intervention.');
            }
        }

        return 0;
    }
}
