<?php

namespace EloquentInsight\Engines;

use EloquentInsight\Storage\AuditBuffer;

class TopologyEngine
{
    public function __construct(
        protected AuditBuffer $buffer
    ) {}

    public function generateMermaid(): string
    {
        $events = $this->buffer->all();
        $lines = ["graph TD"];
        $uniqueEdges = [];

        foreach ($events as $event) {
            if (($event['type'] ?? '') !== 'query') continue;

            $model = $event['model'] ?? 'Unknown';
            $trace = $event['trace'] ?? [];

            // Extract the userland call chain
            $chain = array_reverse($trace);
            
            $prev = null;
            foreach ($chain as $step) {
                $nodeName = "{$step['class']}@{$step['function']}";

                if ($prev) {
                    $key = "{$prev} --> {$nodeName}";
                    if (!isset($uniqueEdges[$key])) {
                        $lines[] = "    {$key}";
                        $uniqueEdges[$key] = true;
                    }
                }
                $prev = $nodeName;
            }

            if ($prev) {
                $key = "{$prev} --> Model:{$model}";
                if (!isset($uniqueEdges[$key])) {
                    $lines[] = "    {$key}";
                    $uniqueEdges[$key] = true;
                }
            }
        }

        return implode("\n", $lines);
    }
}
