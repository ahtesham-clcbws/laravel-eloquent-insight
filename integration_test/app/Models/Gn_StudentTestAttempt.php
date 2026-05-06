<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_StudentTestAttempt extends Model
{
    use HasFactory;

    public function test()
    {
        return $this->belongsTo(TestModal::class,'test_id','id');
    }
}
