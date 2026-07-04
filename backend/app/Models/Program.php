<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'category', 'collected', 'target', 'image', 'active'])]
class Program extends Model
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'collected' => 'integer',
            'target' => 'integer',
        ];
    }
}
