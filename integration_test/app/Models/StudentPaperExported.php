<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPaperExported extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function subjectPaperDetail()
    {
        return $this->belongsTo(SubjectPaperDetail::class, 'subject_mapping_id', 'subject_mapping_id')
                    ->where('subject_id', $this->subject_id);
    }
}
