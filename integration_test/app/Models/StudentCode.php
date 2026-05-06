<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StudentCode extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];
    protected $table = 'student_codes'; // explicitly providing

    protected $casts = [
        'exam_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }

    public function voucher()
    {
        return $this->belongsTo(CouponCode::class, 'coupan_code', 'couponcode');
    }

    public function examCenter()
    {
        return $this->belongsTo(Center::class, 'exam_center', 'id');
    }
    public function corporate()
    {
        return $this->belongsTo(Corporate::class);
    }
}
