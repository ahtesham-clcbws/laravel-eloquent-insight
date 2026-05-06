<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherCategoryClass extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'other_category_class';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];
}
