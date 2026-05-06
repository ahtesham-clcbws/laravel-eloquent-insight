<?php

namespace EloquentInsight\Engines;

class HeuristicEngine
{
    /**
     * Analyze violations to find patterns.
     */
    public function analyze(array $violations): array
    {
        $insights = [];
        $grouped = [];

        foreach ($violations as $violation) {
            $key = $violation['model'] . ':' . $violation['relation'];
            $grouped[$key][] = $violation;
        }

        foreach ($grouped as $v) {
            $insights[] = [
                'type' => 'N+1', // Added missing type
                'point' => $v[0]['point'] ?? 'Unknown',
                'file' => $v[0]['file'] ?? 'unknown',
                'line' => $v[0]['line'] ?? 0,
                'model' => $v[0]['model'],
                'relations' => [$v[0]['relation']],
                'relation' => $v[0]['relation'],
                'count' => count($v),
                'impact' => count($v) > 5 ? 'HIGH' : 'MODERATE',
            ];
        }

        return $insights;
    }
}
