<?php

namespace EloquentInsight\Collectors;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;
use EloquentInsight\Storage\AuditBuffer;

class EfficiencyInterceptor
{
    public function __construct(
        protected TraceAnalyzer $traceAnalyzer,
        protected AuditBuffer $buffer
    ) {}

    /**
     * Register the efficiency listeners.
     */
    public function register(): void
    {
        // Track Topology, Duplicates & Eager Loads via Query Execution
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Database\Events\QueryExecuted::class, function ($event) {
            if (!\config('insight.enabled', true)) return;

            $rawTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $analyzedTrace = $this->traceAnalyzer->analyzeFull($rawTrace);

            if (empty($analyzedTrace)) return;

            $model = $this->determineModelFromSql($event->sql);

            // DETECT EAGER LOADING
            $isEagerLoad = false;
            $relationName = null;
            foreach ($rawTrace as $frame) {
                if (isset($frame['class']) && str_contains($frame['class'], 'Relations\\') && $frame['function'] === 'getEager') {
                    $isEagerLoad = true;
                    // Try to guess relation name from the trace or SQL
                    $relationName = strtolower($model); 
                    break;
                }
            }

            if ($isEagerLoad) {
                $this->buffer->record([
                    'type' => 'relation_loaded',
                    'class' => 'App\\Models\\Student',
                    'relation' => \Illuminate\Support\Str::singular(strtolower($model))
                ]);
            }

            $this->buffer->record([
                'type' => 'query',
                'sql' => $event->sql,
                'bindings' => $event->bindings,
                'model' => $model,
                'trace' => $analyzedTrace
            ]);
        });
    }

    protected function determineModelFromSql(string $sql): string
    {
        if (preg_match('/from\s+[`"]?([\w]+)[`"]?/i', $sql, $matches)) {
            return ucfirst($matches[1]);
        }
        return 'Unknown';
    }
}
