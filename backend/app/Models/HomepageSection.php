<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'description',
        'image',
        'button_primary_text',
        'button_primary_link',
        'button_secondary_text',
        'button_secondary_link',
        'extra_data',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'extra_data' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
