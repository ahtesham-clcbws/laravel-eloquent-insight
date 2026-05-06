<?php

namespace EloquentInsight\Traits;

use EloquentInsight\Storage\AuditBuffer;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasInsight
{
    /**
     * Boot the trait and register model events.
     */
    public static function bootHasInsight(): void
    {
        // Handled by interceptor
    }

    /**
     * Intercept attribute access to track hydration and ghost relations.
     * 
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (app()->bound(AuditBuffer::class)) {
            app(AuditBuffer::class)->record([
                'type' => 'access',
                'model_id' => spl_object_id($this),
                'class' => static::class,
                'key' => (string) $key,
                'is_relation' => $this->relationLoaded($key),
                'fetched_keys' => array_keys($this->getAttributes())
            ]);
        }

        return $value;
    }
}
