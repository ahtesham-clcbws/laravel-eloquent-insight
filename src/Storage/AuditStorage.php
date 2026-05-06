<?php

namespace EloquentInsight\Storage;

use Illuminate\Support\Facades\File;

class AuditStorage
{
    protected string $path;

    public function __construct()
    {
        $storagePath = config('insight.storage_path') ?: storage_path('framework/insight');
        $this->path = $storagePath . DIRECTORY_SEPARATOR . 'audit.json';
    }

    public function save(array $data): void
    {
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function load(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }

        $content = file_get_contents($this->path);
        return json_decode($content, true) ?: [];
    }
}
