<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;

class ClearCommand extends Command
{
    protected $signature = 'insight:clear';
    protected $description = 'Clear all captured performance violations and metrics';

    public function handle(): int
    {
        app('insight')->clear();
        $this->info('Performance history cleared successfully.');
        return 0;
    }
}
