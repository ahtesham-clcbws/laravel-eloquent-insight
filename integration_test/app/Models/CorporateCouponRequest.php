<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateCouponRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'corporate_id',
        'status',
        'reject_reason',
        'prefix',
        'numbers'
    ];

    public function corporate()
    {
        return $this->belongsTo(Corporate::class);
    }
}
