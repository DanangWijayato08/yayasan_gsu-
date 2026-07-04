<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'image', 'category', 'active'])]
class Gallery extends Model
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
