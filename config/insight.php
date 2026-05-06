<?php

return [
    /**
     * Enable or disable the entire N+1 recording engine.
     * Usually enabled in local/testing and disabled in production.
     */
    'enabled' => \env('INSIGHT_ENABLED', \env('APP_ENV') !== 'production'),

    /**
     * Enable automated eager loading in production using the compiled manifest.
     */
    'auto_resolve' => \env('INSIGHT_AUTO_RESOLVE', true),

    /**
     * --- Elite Efficiency Suite ---
     */
    
    /**
     * Enable Ghost Relation and Hydration profiling.
     */
    'audit_enabled' => \env('INSIGHT_AUDIT_ENABLED', true),

    /**
     * Enable Query Topology (Call-chain) mapping.
     */
    'topology_enabled' => \env('INSIGHT_TOPOLOGY_ENABLED', true),

    /**
     * Enable Duplicate Query detection.
     */
    'duplicates_enabled' => \env('INSIGHT_DUPLICATES_ENABLED', true),

    /**
     * --- UI & Storage ---
     */

    /**
     * Enable the Browser UI Overlay.
     */
    'ui_enabled' => \env('INSIGHT_UI_ENABLED', true),

    /**
     * The storage path for the violation buffer and reports.
     */
    'storage_path' => \env('INSIGHT_STORAGE_PATH', \storage_path('framework/insight')),

    /**
     * Classes to ignore during trace analysis.
     */
    'ignore_namespaces' => [
        'Illuminate\\',
        'EloquentInsight\\',
    ],
];
