<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectPart extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subjects_part';
    protected $primaryKey = 'id';

    protected $fillable = [
        'subject_id',
        'name',
    ];
    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }
    public function subject_part_lessons()
    {
        return $this->hasMany(SubjectPartLesson::class, 'subject_part_id', 'id');
    }
}
