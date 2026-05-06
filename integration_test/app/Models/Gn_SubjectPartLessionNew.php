<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gn_SubjectPartLessionNew extends Model
{
    use HasFactory;
    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }

    public function subject_part()
    {
        return $this->hasOne(SubjectPart::class, 'id', 'subject_part_id');
    }

    public function subject_chapter()
    {
        return $this->hasOne(SubjectPartLesson::class, 'id', 'subject_chapter_id');
    }

}
