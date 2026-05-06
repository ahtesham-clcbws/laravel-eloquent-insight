<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectPaperDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function studentPaper(){
        return $this->hasMany(StudentPaperExported::class, 'subject_mapping_id', 'subject_mapping_id')
        ->where('subject_id', $this->subject_id);
    }
}
