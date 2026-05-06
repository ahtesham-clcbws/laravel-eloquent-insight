<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;

    public function corporate()
    {
        return $this->belongsTo(Corporate::class, 'corporate_id', 'id');
    }
}
