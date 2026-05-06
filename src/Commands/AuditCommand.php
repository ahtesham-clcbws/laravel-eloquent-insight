<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;
use EloquentInsight\InsightManager;

class AuditCommand extends Command
{
    use HandlesSmartOutput;

    protected $signature = 'insight:audit {--graph}';
    protected $description = 'Perform a deep efficiency audit (Ghost relations, hydration, topology, duplicates)';

    public function handle(InsightManager $manager): int
    {
        $this->buffer('--- Eloquent Insight: Efficiency Audit ---');

        $report = $manager->getEfficiencyReport();
        $duplicates = $manager->getDuplicateReport();

        // Ghost Relations
        $this->buffer("\n👻 Ghost Relations (Loaded but never accessed):");
        if (empty($report['ghost_relations'])) {
            $this->buffer('  None found. Great job!');
        } else {
            foreach ($report['ghost_relations'] as $class => $relations) {
                $this->buffer("  [{$class}]: " . implode(', ', $relations));
            }
        }

        // Hydration Insights
        $this->buffer("\n💧 Hydration Profiler (Heavy models):");
        if (empty($report['hydration_insights'])) {
            $this->buffer('  All models are efficiently hydrated.');
        } else {
            foreach ($report['hydration_insights'] as $class => $data) {
                $this->buffer("  [{$class}]: Fetched {$data['fetched_count']} columns, but only accessed {$data['accessed_count']}.");
                $this->buffer("     Suggestion: ->select(['" . implode("', '", $data['suggested_select']) . "'])");
            }
        }

        // Duplicate Queries
        $this->buffer("\n🔄 Duplicate Queries (Redundant SQL):");
        if (empty($duplicates)) {
            $this->buffer('  No redundant queries detected.');
        } else {
            foreach ($duplicates as $dup) {
                $this->buffer("  [Count: {$dup['count']}] SQL: {$dup['sql']}");
            }
        }

        // Topology Graph
        if ($this->option('graph')) {
            $this->buffer("\n🗺️ Query Topology (Mermaid format):");
            $this->buffer($manager->getTopologyGraph());
        }

        $this->finalizeOutput('efficiency_audit');

        return 0;
    }
}
