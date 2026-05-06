<?php

namespace EloquentInsight\Collectors;

class TraceAnalyzer
{
    /**
     * Analyze the backtrace to find the point of origin in userland code.
     */
    public function analyze(array $backtrace): ?array
    {
        foreach ($backtrace as $frame) {
            if (!isset($frame['file'])) continue;
            
            // Skip the package itself AND its traits
            if (str_contains($frame['file'], 'EloquentInsight')) continue;
            if (str_contains($frame['file'], 'Traits/HasInsight')) continue;
            
            // Skip core framework internals
            if (str_contains($frame['file'], 'vendor/laravel/framework')) continue;
            if (str_contains($frame['file'], 'vendor/symfony/')) continue;
            if (str_contains($frame['file'], 'vendor/composer/')) continue;

            return [
                'entry_point' => ($frame['class'] ?? 'Global') . '@' . ($frame['function'] ?? 'main'),
                'file' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $frame['file']),
                'line' => $frame['line'] ?? 0,
            ];
        }
        return null;
    }

    public function analyzeFull(array $backtrace): array
    {
        $filtered = [];
        foreach ($backtrace as $frame) {
            if (!isset($frame['file'])) continue;
            if (str_contains($frame['file'], 'vendor/laravel/framework')) continue;

            $filtered[] = [
                'file' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $frame['file']),
                'line' => $frame['line'] ?? 0,
                'class' => $frame['class'] ?? 'Global',
                'function' => $frame['function'] ?? 'main',
            ];
        }
        return $filtered;
    }
}
