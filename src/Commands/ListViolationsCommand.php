<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;

class ListViolationsCommand extends Command
{
    protected $signature = 'insight:list';
    protected $description = 'List all captured Eloquent performance violations';

    public function handle(): int
    {
        $solutions = app('insight')->getLatestSolutions();

        if (empty($solutions)) {
            $this->info('No N+1 violations captured yet.');
            return 0;
        }

        $this->info("--- N+1 Violations List ---");
        foreach ($solutions as $solution) {
            $this->line("<info>[{$solution['impact']}]</info> <comment>{$solution['point']}</comment>");
            $this->line("  Model: <info>{$solution['model']}</info> | Relation: <info>{$solution['relation']}</info> | Count: <info>{$solution['count']}</info>");
            $this->line("  File: {$solution['file']}:{$solution['line']}");
            $this->line("");
        }

        return 0;
    }
}
