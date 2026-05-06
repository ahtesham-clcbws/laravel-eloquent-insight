<?php

namespace EloquentInsight\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use EloquentInsight\Storage\BufferInterface;
use EloquentInsight\Engines\HeuristicEngine;
use EloquentInsight\Engines\SolutionGenerator;

class InsightController
{
    public function __construct(
        protected BufferInterface $buffer,
        protected HeuristicEngine $heuristicEngine,
        protected SolutionGenerator $solutionGenerator
    ) {}

    /**
     * Get the latest insights for the UI overlay.
     */
    public function index(\EloquentInsight\InsightManager $manager): JsonResponse
    {
        $solutions = $manager->getLatestSolutions();
        $efficiency = $manager->getEfficiencyReport();
        $duplicates = $manager->getDuplicateReport();

        return \response()->json([
            'solutions' => $solutions,
            'efficiency' => $efficiency,
            'duplicates' => $duplicates,
            'summary' => [
                'total_solutions' => count($solutions),
                'ghost_relations' => count($efficiency['ghost_relations']),
                'duplicate_groups' => count($duplicates),
            ]
        ]);
    }

    /**
     * Clear all captured violations.
     */
    public function clear(BufferInterface $buffer): JsonResponse
    {
        $buffer->clear();

        return \response()->json(['status' => 'success']);
    }

    /**
     * Apply an auto-fix via the UI.
     */
    public function fix(Request $request): JsonResponse
    {
        $file = $request->input('file');
        $line = $request->input('line');
        $relations = $request->input('relations');

        $success = app(\EloquentInsight\Engines\AstFixer::class)->fix($file, $line, $relations);

        return \response()->json([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Fix applied successfully' : 'Failed to apply fix'
        ]);
    }
}
