<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    use HasFactory;

    protected $table="company_models";

    protected $fillable = [
        'softwareurl', // Add this line to allow mass assignment for 'softwareurl'
        'companyname',
        'shortname',
        'cin',
        'pan',
        'tan',
        'gst',
        'logo',
        'companycategory',
        'companyclass',
        'authorizedcapital',
        'paidupcapital',
        'sharenominalvalue',
        'stateofregistration',
        'incorporationdate',
        'email',
        'phone',
        'landlineno',
        'whatsappno',
        'city',
        'state',
        'pincode',
        'razorpay_marchent_id',
        'razorpay_marchent_key',
        'sms_api_key',
        'sms_api_link',
        'address',
        'about',
    ];
}
