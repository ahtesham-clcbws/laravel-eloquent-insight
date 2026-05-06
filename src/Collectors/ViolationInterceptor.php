<?php

namespace EloquentInsight\Collectors;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;
use EloquentInsight\Storage\AuditBuffer;

class ViolationInterceptor
{
    public function __construct(
        protected AuditBuffer $buffer,
        protected TraceAnalyzer $traceAnalyzer
    ) {}

    /**
     * Register the query listener to detect N+1.
     */
    public function register(): void
    {
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Database\Events\QueryExecuted::class, function ($event) {
            // EMERGENCY DEBUG
            // file_put_contents('/tmp/insight_debug.log', "Query Seen: " . $event->sql . PHP_EOL, FILE_APPEND);

            if (!\config('insight.enabled', true)) return;

            $rawTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $origin = $this->traceAnalyzer->analyze($rawTrace);

            if (!$origin) {
                // file_put_contents('/tmp/insight_debug.log', "Origin NULL for: " . $event->sql . PHP_EOL, FILE_APPEND);
                return;
            }

            // Detect ALL queries for a moment to verify detection
            $this->buffer->record([
                'type' => 'violation',
                'point' => $origin['entry_point'],
                'file' => $origin['file'],
                'line' => $origin['line'],
                'model' => $this->determineModelFromSql($event->sql),
                'relation' => $this->extractRelationName($event->sql),
                'sql' => $event->sql,
            ]);
        });
    }

    protected function isRelationshipQuery(string $sql): bool
    {
        return stripos($sql, 'select') === 0 && stripos($sql, 'where') !== false;
    }

    protected function extractRelationName(string $sql): string
    {
        if (preg_match('/from\s+[`"]?([\w]+)[`"]?/i', $sql, $matches)) {
            return \Illuminate\Support\Str::singular($matches[1]);
        }
        return 'unknown';
    }

    protected function determineModelFromSql(string $sql): string
    {
        if (preg_match('/from\s+[`"]?([\w]+)[`"]?/i', $sql, $matches)) {
            return ucfirst(\Illuminate\Support\Str::singular($matches[1]));
        }
        return 'Unknown';
    }
}
