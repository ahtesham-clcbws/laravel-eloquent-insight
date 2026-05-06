<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use EloquentInsight\Storage\BufferInterface;
use EloquentInsight\Engines\HeuristicEngine;
use EloquentInsight\Engines\SolutionGenerator;

class BoostExportCommand extends Command
{
    protected $signature = 'insight:boost';
    protected $description = 'Export insights as a Laravel Boost agent artifact';

    public function handle(
        BufferInterface $buffer,
        HeuristicEngine $heuristicEngine,
        SolutionGenerator $solutionGenerator
    ): int {
        $violations = $buffer->all();
        $insights = $heuristicEngine->analyze($violations);
        $solutions = $solutionGenerator->generate($insights);

        $artifact = [
            'project' => config('app.name'),
            'type' => 'performance_insight',
            'schema_version' => '1.0.0',
            'insights' => $solutions,
        ];

        $path = base_path('insight.json');
        File::put($path, json_encode($artifact, JSON_PRETTY_PRINT));

        $this->info("Laravel Boost artifact generated at: {$path}");
        
        return 0;
    }
}
