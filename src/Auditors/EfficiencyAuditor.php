<?php

namespace EloquentInsight\Auditors;

use EloquentInsight\Storage\AuditBuffer;

class EfficiencyAuditor
{
    public function __construct(
        protected AuditBuffer $buffer
    ) {}

    public function getReport(): array
    {
        $events = $this->buffer->all();
        
        $loadedRelations = [];
        $accessedRelations = [];
        $accessedAttributes = [];
        $fetchedAttributes = [];

        foreach ($events as $event) {
            $type = $event['type'] ?? '';
            $class = $event['class'] ?? 'Unknown';
            $id = $event['model_id'] ?? 0;

            if ($type === 'relation_loaded') {
                $loadedRelations[$class][] = $event['relation'];
            } elseif ($type === 'access') {
                $key = $event['key'];
                $isRelation = $event['is_relation'] ?? false;

                if ($isRelation) {
                    $accessedRelations[$class][] = $key;
                } else {
                    $accessedAttributes[$id][$class][] = $key;
                }

                if (!isset($fetchedAttributes[$id])) {
                    $fetchedAttributes[$id] = [
                        'class' => $class,
                        'keys' => $event['fetched_keys'] ?? []
                    ];
                }
            }
        }

        return [
            'ghost_relations' => $this->calculateGhostRelations($loadedRelations, $accessedRelations),
            'hydration_insights' => $this->calculateHydrationEfficiency($fetchedAttributes, $accessedAttributes),
        ];
    }

    protected function calculateGhostRelations(array $loaded, array $accessed): array
    {
        $ghosts = [];
        foreach ($loaded as $class => $relations) {
            $acc = $accessed[$class] ?? [];
            $diff = array_diff(array_unique($relations), array_unique($acc));
            
            if (!empty($diff)) {
                $ghosts[$class] = array_values($diff);
            }
        }
        return $ghosts;
    }

    protected function calculateHydrationEfficiency(array $fetched, array $accessed): array
    {
        $insights = [];
        foreach ($fetched as $id => $data) {
            $class = $data['class'];
            $fetchKeys = $data['keys'];
            $accKeys = array_unique($accessed[$id][$class] ?? []);
            
            $unused = array_diff($fetchKeys, $accKeys);
            
            if (count($fetchKeys) > 0 && count($unused) / count($fetchKeys) > 0.5) {
                $insights[$class] = [
                    'fetched_count' => count($fetchKeys),
                    'accessed_count' => count($accKeys),
                    'suggested_select' => $accKeys ?: ['id'],
                ];
            }
        }
        return $insights;
    }
}
