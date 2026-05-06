<?php

namespace EloquentInsight\Commands;

use Illuminate\Support\Facades\File;

trait HandlesSmartOutput
{
    protected array $outputBuffer = [];

    /**
     * Add a line to the smart buffer.
     */
    public function buffer(string $line): void
    {
        $this->outputBuffer[] = $line;
    }

    /**
     * Finalize the output by checking the 200-line threshold.
     */
    public function finalizeOutput(string $reportName = 'report'): void
    {
        if (count($this->outputBuffer) > 200) {
            $this->exportToFile($reportName);
        } else {
            foreach ($this->outputBuffer as $line) {
                $this->line($line);
            }
        }
    }

    /**
     * Export the buffered output to a markdown file.
     */
    protected function exportToFile(string $name): void
    {
        $dir = config('insight.storage_path') . '/reports';
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $filename = "{$name}_" . date('Y-m-d_His') . ".md";
        $path = "{$dir}/{$filename}";

        $content = "# Eloquent Insight Report: {$name}\n";
        $content .= "Generated at: " . date('Y-m-d H:i:s') . "\n\n";
        $content .= implode("\n", $this->outputBuffer);

        File::put($path, $content);

        $this->warn("\n⚠️ Output too large (" . count($this->outputBuffer) . " lines).");
        $this->info("✅ Report exported to: [{$path}]");
    }
}
