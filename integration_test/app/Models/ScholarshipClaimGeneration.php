<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipClaimGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_eligibility_percentage',
        'max_students'
    ];

    // check min_eligibity by percent
    // check obtain marks in percent
    // check maximum right answers
    // check minimum wrong answers
    // check if disable
    // check max age
}
