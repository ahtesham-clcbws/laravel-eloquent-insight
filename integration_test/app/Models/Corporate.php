<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Corporate extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'corporates';

    protected $guarded = [];

    // protected $fillable = [
    //     'name',
    //     'institute_name',
    //     'type_institution',
    //     'interested_for',
    //     'established_year',
    //     'email',
    //     'phone',
    //     'otp',
    //     'address',
    //     'city',
    //     'pincode',
    //     'attachment',
    // ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function studentCodes()
    {
        return $this->hasMany(StudentCode::class, 'corporate_id');
    }

    public function testimonial()
    {
        return $this->hasOne(TestimonialsModel::class, 'type_id')
            ->where('type', 'corporate');
    }

    public function couponRequests(){
        return $this->hasMany(CorporateCouponRequest::class);
    }
}
