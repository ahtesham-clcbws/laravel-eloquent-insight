<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectPartLesson extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subjects_part_lesson';
    protected $primaryKey = 'id';

    protected $fillable = [
        'subject_id',
        'subject_part_id',
        'name'
    ];
    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }
    public function subject_part()
    {
        return $this->hasOne(SubjectPart::class, 'id', 'subject_part_id');
    }
}
