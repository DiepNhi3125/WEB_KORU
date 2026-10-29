<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'device_name',
    'fcm_token',
])]
#[Hidden([
    'fcm_token',
])]
class CaregiverDevice extends Model
{
    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
        ];
    }

    // Tai khoan nguoi cham soc so huu thiet bi
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
