<?php

namespace EloquentInsight\Engines;

class SolutionGenerator
{
    /**
     * Generate human-readable and machine-actionable solutions for insights.
     */
    public function generate(array $insights): array
    {
        return array_map(function ($insight) {
            return array_merge($insight, [
                'fix' => $this->formatFix($insight),
                'summary' => $this->formatSummary($insight),
            ]);
        }, $insights);
    }

    /**
     * Format the Eloquent with() call.
     */
    protected function formatFix(array $insight): string
    {
        $relations = $insight['relations'];
        
        if (count($relations) === 1) {
            return "->with('{$relations[0]}')";
        }

        $relationString = implode("', '", $relations);
        return "->with(['{$relationString}'])";
    }

    /**
     * Format a descriptive summary of why this fix is needed.
     */
    protected function formatSummary(array $insight): string
    {
        $count = count($insight['relations']);
        $relationList = implode(', ', $insight['relations']);
        
        return "[Impact: {$insight['impact']}] Detected {$count} N+1 relations ({$relationList}) triggered {$insight['count']} times in {$insight['type']} [{$insight['file']}:{$insight['line']}].";
    }
}
