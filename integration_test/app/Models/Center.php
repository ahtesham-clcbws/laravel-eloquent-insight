<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function state()
    {
      return $this->belongsTo(State::class, 'state_id', 'id')->select('id','name');
    }

    public function city()
    {
        return $this->belongsTo(District::class, 'city_id', 'id')->select('id','name');
    }
}
