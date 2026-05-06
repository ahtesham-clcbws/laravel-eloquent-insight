<?php

namespace EloquentInsight\Collectors;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;
use EloquentInsight\Storage\AuditBuffer;
use Illuminate\Support\Str;

class PerformanceInterceptor
{
    public function __construct(
        protected TraceAnalyzer $traceAnalyzer,
        protected AuditBuffer $buffer
    ) {}

    public function register(): void
    {
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Database\Events\QueryExecuted::class, function ($event) {
            if (!\config('insight.enabled', true)) return;

            $rawTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $origin = $this->traceAnalyzer->analyze($rawTrace);

            if (!$origin) return;

            // Only record as violation if it's a potential N+1 relationship query
            if ($this->isRelationshipQuery($event->sql)) {
                $this->buffer->record([
                    'type' => 'violation',
                    'point' => $origin['entry_point'],
                    'file' => $origin['file'],
                    'line' => $origin['line'],
                    'model' => $this->determineModelFromSql($event->sql),
                    'relation' => $this->extractRelationName($event->sql),
                    'sql' => $event->sql,
                ]);
            }

            // Always record for the Efficiency Audit
            $fullTrace = $this->traceAnalyzer->analyzeFull($rawTrace);
            if (!empty($fullTrace)) {
                $model = $this->determineModelFromSql($event->sql);
                $this->buffer->record([
                    'type' => 'query',
                    'sql' => $event->sql,
                    'bindings' => $event->bindings,
                    'model' => $model,
                    'trace' => $fullTrace
                ]);
            }
        });
    }

    protected function isRelationshipQuery(string $sql): bool
    {
        // N+1 queries usually have a WHERE clause targeting a specific ID
        return stripos($sql, 'select') === 0 && stripos($sql, 'where') !== false && !str_contains($sql, 'count(');
    }

    protected function determineModelFromSql(string $sql): string
    {
        if (preg_match('/from\s+[`"]?([\w]+)[`"]?/i', $sql, $matches)) {
            return ucfirst(Str::singular($matches[1]));
        }
        return 'Unknown';
    }

    protected function extractRelationName(string $sql): string
    {
        if (preg_match('/from\s+[`"]?([\w]+)[`"]?/i', $sql, $matches)) {
            return Str::singular($matches[1]);
        }
        return 'unknown';
    }
}
