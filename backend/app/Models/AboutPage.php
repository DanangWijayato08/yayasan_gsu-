<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'vision',
        'mission',
        'values',
        'image',
        'legal_info',
        'organization_structure',
    ];

    protected function casts(): array
    {
        return [
            'mission' => 'array',
            'values' => 'array',
            'organization_structure' => 'array',
        ];
    }
}
