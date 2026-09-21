<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'address',
        'email',
        'phone',
        'whatsapp',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'tiktok_url',
        'google_maps_embed',
        'operational_hours',
    ];
}
