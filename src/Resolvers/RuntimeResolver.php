<?php

namespace EloquentInsight\Resolvers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class RuntimeResolver
{
    protected array $manifest = [];
    protected ?string $currentAction = null;

    /**
     * Register the production runtime resolver.
     */
    public function register(): void
    {
        $path = base_path('bootstrap/cache/eloquent_insight.php');

        if (!File::exists($path)) {
            return;
        }

        $this->manifest = require $path;
        $this->currentAction = Route::currentRouteAction();

        if (!$this->currentAction || !isset($this->manifest[$this->currentAction])) {
            return;
        }

        // Register a global listener for model booting
        \Illuminate\Support\Facades\Event::listen('eloquent.booting: *', function ($modelName, $models) {
            $model = $models[0];
            $relations = $this->getRelationsForModel(get_class($model));

            if (!empty($relations)) {
                $model->addGlobalScope(new AutoEagerLoadScope($relations));
            }
        });
    }

    /**
     * Get the relations that should be eager-loaded for a given model.
     */
    public function getRelationsForModel(string $model): array
    {
        if (!$this->currentAction || !isset($this->manifest[$this->currentAction])) {
            return [];
        }

        return $this->manifest[$this->currentAction][$model] ?? [];
    }
}
