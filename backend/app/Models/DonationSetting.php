<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationSetting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'qris_image',
        'whatsapp_number',
        'payment_instruction',
        'suggested_amounts',
    ];

    protected function casts(): array
    {
        return ['suggested_amounts' => 'array'];
    }
}
