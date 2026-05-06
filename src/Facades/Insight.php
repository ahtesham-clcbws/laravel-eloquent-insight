<?php

namespace EloquentInsight\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getLatestSolutions()
 * @method static int getPerformanceScore()
 * @method static array getEfficiencyReport()
 * @method static string getTopologyGraph()
 * @method static array getDuplicateReport()
 * 
 * @see \EloquentInsight\InsightManager
 */
class Insight extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'insight';
    }
}
