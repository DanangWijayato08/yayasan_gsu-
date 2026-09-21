<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'favicon',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'primary_color',
        'maintenance_mode',
    ];

    protected function casts(): array
    {
        return ['maintenance_mode' => 'boolean'];
    }
}
