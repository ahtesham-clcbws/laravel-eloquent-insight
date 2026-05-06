<?php

namespace EloquentInsight;

use EloquentInsight\Storage\BufferInterface;
use EloquentInsight\Engines\HeuristicEngine;
use EloquentInsight\Engines\SolutionGenerator;
use EloquentInsight\Auditors\EfficiencyAuditor;
use EloquentInsight\Auditors\DuplicateQueryDetector;
use EloquentInsight\Engines\TopologyEngine;
use EloquentInsight\Storage\AuditBuffer;

class InsightManager
{
    public function __construct(
        protected BufferInterface $buffer, // Legacy, kept for compatibility
        protected HeuristicEngine $heuristicEngine,
        protected SolutionGenerator $solutionGenerator,
        protected EfficiencyAuditor $efficiencyAuditor,
        protected TopologyEngine $topologyEngine,
        protected DuplicateQueryDetector $duplicateDetector,
        protected AuditBuffer $auditBuffer
    ) {}

    /**
     * Get the latest solutions/insights.
     */
    public function getLatestSolutions(): array
    {
        $events = $this->auditBuffer->all();
        $violations = array_filter($events, fn($e) => ($e['type'] ?? '') === 'violation');

        $insights = $this->heuristicEngine->analyze($violations);
        
        return $this->solutionGenerator->generate($insights);
    }

    /**
     * Get a performance score.
     */
    public function getPerformanceScore(): int
    {
        $events = $this->auditBuffer->all();
        $violations = count(array_filter($events, fn($e) => ($e['type'] ?? '') === 'violation'));
        
        if ($violations === 0) return 100;
        if ($violations < 5) return 90;
        if ($violations < 10) return 70;
        
        return 50;
    }

    /**
     * Get the efficiency report.
     */
    public function getEfficiencyReport(): array
    {
        return $this->efficiencyAuditor->getReport();
    }

    /**
     * Get the query topology graph.
     */
    public function getTopologyGraph(): string
    {
        return $this->topologyEngine->generateMermaid();
    }

    /**
     * Get the duplicate queries report.
     */
    public function getDuplicateReport(): array
    {
        return $this->duplicateDetector->getReport();
    }

    /**
     * Clear captured data (optionally specific points).
     */
    public function clear(?string $point = null, ?string $model = null): void
    {
        $this->auditBuffer->clear($point, $model);
    }
}
