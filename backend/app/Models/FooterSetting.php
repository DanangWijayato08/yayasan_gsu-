<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $fillable = ['description', 'copyright_text', 'quick_links', 'social_links'];

    protected function casts(): array
    {
        return [
            'quick_links' => 'array',
            'social_links' => 'array',
        ];
    }
}
