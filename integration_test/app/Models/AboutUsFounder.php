<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsFounder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'form_type', 'banner_id', 'icon', 'picture', 'name', 'message',
    ];
}
