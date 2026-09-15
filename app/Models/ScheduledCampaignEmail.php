<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledCampaignEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_email',
        'recipient_name',
        'mail_type',
        'campaign_content',
        'scheduled_at',
        'status',
        'sent_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'campaign_content' => 'array',
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
