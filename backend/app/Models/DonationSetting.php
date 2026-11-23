<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationSetting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'collected_amount',
        'target_amount',
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
        return [
            'collected_amount' => 'integer',
            'target_amount' => 'integer',
            'suggested_amounts' => 'array',
        ];
    }
}
