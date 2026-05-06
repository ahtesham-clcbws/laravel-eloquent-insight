<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subjects';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];
    public function subject_parts()
    {
        return $this->hasMany(SubjectPart::class, 'subject_id', 'id');
    }
    public function subject_part_lessons()
    {
        return $this->hasMany(SubjectPartLesson::class, 'subject_id', 'id');
    }

    public function gnResultSubjectMappings()
    {
        return $this->belongsToMany(GnResultSubjectMapping::class, 'gn_result_subject_mappings', 'subject_id', 'id');
    }
}
