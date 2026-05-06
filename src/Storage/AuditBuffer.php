<?php

namespace EloquentInsight\Storage;

class AuditBuffer
{
    protected string $path;

    public function __construct()
    {
        $storagePath = config('insight.storage_path') ?: storage_path('framework/insight');
        $this->path = $storagePath . DIRECTORY_SEPARATOR . 'audit_events.jsonl';
    }

    public function record(array $event): void
    {
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $event['timestamp'] = microtime(true);
        file_put_contents($this->path, json_encode($event) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function all(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map(fn($line) => json_decode($line, true), $lines);
    }

    public function clear(?string $point = null, ?string $model = null): void
    {
        if (!$point && !$model) {
            if (file_exists($this->path)) {
                unlink($this->path);
            }
            return;
        }

        $events = $this->all();
        $filtered = array_filter($events, function ($e) use ($point, $model) {
            if (($e['type'] ?? '') !== 'violation') return true;
            if ($point && ($e['point'] ?? '') === $point) return false;
            if ($model && ($e['model'] ?? '') === $model) return false;
            return true;
        });

        file_put_contents($this->path, '');
        foreach ($filtered as $event) {
            $this->record($event);
        }
    }
}
