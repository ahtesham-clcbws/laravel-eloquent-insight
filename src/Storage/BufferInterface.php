<?php

namespace EloquentInsight\Storage;

interface BufferInterface
{
    /**
     * Append data to the storage buffer.
     */
    public function append(array $data): void;

    /**
     * Clear data from the buffer. If arguments provided, clears only matching.
     */
    public function clear(?string $entryPoint = null, ?string $model = null): void;

    /**
     * Retrieve all data from the buffer.
     */
    public function all(): array;
}
