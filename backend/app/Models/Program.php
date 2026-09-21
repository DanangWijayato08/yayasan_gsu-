<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Program extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'short_description',
        'description',
        'image',
        'target_amount',
        'collected_amount',
        'beneficiaries_count',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $appends = ['target', 'collected', 'active'];

    protected static function booted(): void
    {
        static::saving(function (Program $program) {
            if (blank($program->slug)) {
                $program->slug = Str::slug($program->title);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'collected_amount' => 'integer',
            'target_amount' => 'integer',
            'beneficiaries_count' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected function target(): Attribute
    {
        return Attribute::get(fn () => $this->target_amount);
    }

    protected function collected(): Attribute
    {
        return Attribute::get(fn () => $this->collected_amount);
    }

    protected function active(): Attribute
    {
        return Attribute::get(fn () => $this->is_active);
    }
}
