<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialsModel extends Model
{
    use HasFactory;
    protected $table = 'testimonials';
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class, 'type_id');
    }

    public function corporate()
    {
        return $this->belongsTo(Corporate::class, 'type_id');
    }
}
