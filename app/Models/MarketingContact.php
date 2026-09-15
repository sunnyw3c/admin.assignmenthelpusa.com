<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'tags',
        'is_subscribed',
        'last_sent_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_subscribed' => 'boolean',
            'last_sent_at' => 'datetime',
        ];
    }

    public function isCoolingDown(int $cooldownDays = 7): bool
    {
        return $this->last_sent_at !== null && $this->last_sent_at->gte(now()->subDays($cooldownDays));
    }
}
