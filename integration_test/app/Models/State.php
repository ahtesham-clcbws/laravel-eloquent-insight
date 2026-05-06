<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    // Apply Global Scope for 'status = Active'
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 'Active');
        });
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'state_id', 'id');
    }
    public function districts_all()
    {
        return $this->hasMany(District::class)->withoutGlobalScope('active');
    }
}
