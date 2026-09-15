<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignDeliveryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_email',
        'subject',
        'mail_type',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }
}
