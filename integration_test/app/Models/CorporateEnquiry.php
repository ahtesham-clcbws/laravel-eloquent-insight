<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateEnquiry extends Model
{
    use HasFactory;

    protected $table = 'corporate_enquiries';
    protected $guarded = [];

    public static function generateCounts()
    {
        // Placeholder for legacy count generation logic if needed
        return true;
    }
}
