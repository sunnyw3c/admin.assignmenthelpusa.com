<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailDraft extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'mail_type',
        'content',
    ];

    protected function casts(): array
    {
        return ['content' => 'array'];
    }
}
