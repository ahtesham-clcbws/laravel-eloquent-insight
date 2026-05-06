<?php

namespace EloquentInsight\Auditors;

use EloquentInsight\Storage\AuditBuffer;

class DuplicateQueryDetector
{
    public function __construct(
        protected AuditBuffer $buffer
    ) {}

    public function getReport(): array
    {
        $events = $this->buffer->all();
        $queries = [];
        $duplicates = [];

        foreach ($events as $event) {
            if (($event['type'] ?? '') !== 'query') continue;

            $sql = $event['sql'];
            $bindings = $event['bindings'];
            $hash = md5($sql . serialize($bindings));

            if (isset($queries[$hash])) {
                if (!isset($duplicates[$hash])) {
                    $duplicates[$hash] = [
                        'sql' => $sql,
                        'bindings' => $bindings,
                        'count' => 1
                    ];
                }
                $duplicates[$hash]['count']++;
            } else {
                $queries[$hash] = true;
            }
        }

        return array_values($duplicates);
    }
}
