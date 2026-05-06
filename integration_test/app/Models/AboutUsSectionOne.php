<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsSectionOne extends Model
{
    use HasFactory;

    protected $table = 'aboutus_section_one';

    protected $fillable = [
        'title', 'banner', 'description', 'service_a', 'service_a_image', 'service_a_description', 
        'service_b', 'service_b_image', 'service_b_description', 
        'service_c', 'service_c_image', 'service_c_description', 
        'service_d', 'service_d_image', 'service_d_description'
    ];
}
