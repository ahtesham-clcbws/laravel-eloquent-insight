<?php

namespace EloquentInsight\Resolvers;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AutoEagerLoadScope implements Scope
{
    /**
     * Create a new auto-eager load scope instance.
     */
    public function __construct(
        protected array $relations
    ) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->with($this->relations);
    }
}
