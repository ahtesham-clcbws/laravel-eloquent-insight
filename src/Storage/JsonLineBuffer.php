<?php

namespace EloquentInsight\Storage;

use Illuminate\Support\Facades\File;

class JsonLineBuffer implements BufferInterface
{
    protected string $path;

    public function __construct()
    {
        $this->path = config('insight.storage_path') . DIRECTORY_SEPARATOR . 'buffer.jsonl';
    }

    /**
     * Append data to the JSONL file.
     */
    public function append(array $data): void
    {
        if (!File::isDirectory(dirname($this->path))) {
            File::makeDirectory(dirname($this->path), 0755, true);
        }

        File::append($this->path, json_encode($data) . PHP_EOL);
    }

    /**
     * Clear the buffer file or selective entries.
     */
    public function clear(?string $entryPoint = null, ?string $model = null): void
    {
        if (!File::exists($this->path)) {
            return;
        }

        if ($entryPoint === null && $model === null) {
            File::delete($this->path);
            return;
        }

        // Selective clearing
        $remaining = array_filter($this->all(), function ($v) use ($entryPoint, $model) {
            if ($entryPoint && $v['entry_point'] === $entryPoint && $model && $v['model'] === $model) {
                return false;
            }
            return true;
        });

        if (empty($remaining)) {
            File::delete($this->path);
        } else {
            $content = implode(PHP_EOL, array_map(fn($v) => json_encode($v), $remaining)) . PHP_EOL;
            File::put($this->path, $content);
        }
    }

    /**
     * Read and decode all lines from the buffer.
     */
    public function all(): array
    {
        if (!File::exists($this->path)) {
            return [];
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_map(fn($line) => json_decode($line, true), $lines);
    }
}
