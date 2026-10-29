<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'email',
    'phone',
    'purpose',
    'expires_at',
])]
#[Hidden([
    'code_hash',
])]
class Otp extends Model
{
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
            'failed_attempts' => 'integer',
        ];
    }

    // Tai khoan lien quan den OTP
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
