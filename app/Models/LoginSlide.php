<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginSlide extends Model
{
    use HasFactory;

    protected $table = 'login_slides';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'demo_email',
        'demo_password',
        'order_num',
        'is_active',
        'button_icon',
        'button_color'
    ];
}
