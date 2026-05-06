<?php

namespace EloquentInsight;

use Illuminate\Support\ServiceProvider;
use EloquentInsight\Commands\ListViolationsCommand;
use EloquentInsight\Commands\FixViolationsCommand;
use EloquentInsight\Commands\ClearCommand;
use EloquentInsight\Commands\CompileManifestCommand;
use EloquentInsight\Commands\CheckCommand;
use EloquentInsight\Commands\BoostExportCommand;
use EloquentInsight\Commands\AuditCommand;

class InsightServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/insight.php', 'insight');

        $this->app->singleton(Storage\BufferInterface::class, Storage\JsonLineBuffer::class);
        $this->app->singleton(Storage\AuditBuffer::class);
        $this->app->singleton(Collectors\TraceAnalyzer::class);
        $this->app->singleton(Collectors\PerformanceInterceptor::class);
        $this->app->singleton(Engines\HeuristicEngine::class);
        $this->app->singleton(Engines\SolutionGenerator::class);
        $this->app->singleton(Engines\AstFixer::class);
        $this->app->singleton(Compilers\ManifestCompiler::class);
        $this->app->singleton(Resolvers\RuntimeResolver::class);
        
        $this->app->singleton(Auditors\EfficiencyAuditor::class);
        $this->app->singleton(Auditors\DuplicateQueryDetector::class);
        $this->app->singleton(Engines\TopologyEngine::class);
        
        $this->app->singleton('insight', function ($app) {
            return new InsightManager(
                $app->make(Storage\BufferInterface::class),
                $app->make(Engines\HeuristicEngine::class),
                $app->make(Engines\SolutionGenerator::class),
                $app->make(Auditors\EfficiencyAuditor::class),
                $app->make(Engines\TopologyEngine::class),
                $app->make(Auditors\DuplicateQueryDetector::class),
                $app->make(Storage\AuditBuffer::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/insight.php' => config_path('insight.php'),
            ], 'insight-config');

            $this->commands([
                ListViolationsCommand::class,
                FixViolationsCommand::class,
                ClearCommand::class,
                CompileManifestCommand::class,
                CheckCommand::class,
                BoostExportCommand::class,
                AuditCommand::class,
            ]);
        }

        // Initialize Unified Performance Interceptor
        if (\config('insight.enabled', false)) {
            $this->app->make(Collectors\PerformanceInterceptor::class)->register();
        }

        // Register Global Middleware
        $router = $this->app->make(\Illuminate\Routing\Router::class);
        $router->aliasMiddleware('auto-eager-load', Resolvers\AutoEagerLoadMiddleware::class);
        $router->pushMiddlewareToGroup('web', Http\Middleware\InjectInsightOverlay::class);

        $this->registerRoutes();
    }

    /**
     * Register the Insight API routes.
     */
    protected function registerRoutes(): void
    {
        $router = $this->app->make(\Illuminate\Routing\Router::class);
        $router->group([
            'prefix' => '_insight',
            'middleware' => ['web'],
        ], function ($router) {
            $router->get('/data', [Http\Controllers\InsightController::class, 'index']);
            $router->post('/clear', [Http\Controllers\InsightController::class, 'clear']);
            $router->post('/fix', [Http\Controllers\InsightController::class, 'fix']);
        });
    }
}
