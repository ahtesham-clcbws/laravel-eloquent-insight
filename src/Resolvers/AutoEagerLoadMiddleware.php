<?php

namespace EloquentInsight\Resolvers;

use Closure;
use Illuminate\Http\Request;

class AutoEagerLoadMiddleware
{
    public function __construct(
        protected RuntimeResolver $resolver
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Only run in production and if auto-resolve is enabled
        if (app()->environment('production') && config('insight.auto_resolve', false)) {
            $this->resolver->register();
        }

        return $next($request);
    }
}
