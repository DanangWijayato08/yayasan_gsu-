<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
        'activity_date',
        'is_active',
        'sort_order',
    ];

    protected $appends = ['active'];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected function active(): Attribute
    {
        return Attribute::get(fn () => $this->is_active);
    }
}
