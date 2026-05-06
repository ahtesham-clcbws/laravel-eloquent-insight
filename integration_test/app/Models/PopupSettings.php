<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'status'
    ];

    public function casts()
    {
        return [
            'status' => 'boolean'
        ];
    }
}
