<?php

namespace EloquentInsight\Commands;

use Illuminate\Console\Command;
use EloquentInsight\Compilers\ManifestCompiler;

class CompileManifestCommand extends Command
{
    protected $signature = 'insight:compile';
    protected $description = 'Compile captured violations into a static production manifest';

    public function handle(ManifestCompiler $compiler): int
    {
        $this->info('Compiling Eloquent Insight manifest...');

        $path = $compiler->compile();

        $this->info("Manifest successfully compiled to: {$path}");
        $this->comment('This manifest will now be used for zero-overhead auto-resolution in production.');

        return 0;
    }
}
