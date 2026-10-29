<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'full_name',
    'birth_date',
    'gender',
    'phone',
])]
#[Hidden([
    'pairing_code',
    'pairing_hash',
    'sharing_code',
    'sharing_hash',
])]
class Patient extends Model
{
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'pairing_code' => 'encrypted',
            'sharing_code' => 'encrypted',
        ];
    }

    // Người chăm sóc chính sở hữu bệnh nhân
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Những người chăm sóc được chia sẻ quyền
    public function caregivers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'patient_user',
            'patient_id',
            'user_id'
        )->withTimestamps();
    }

    // Những thiết bị được liên kết với bệnh nhân
    public function devices(): HasMany
    {
        return $this->hasMany(PatientDevice::class, 'patient_id');
    }

    // Những lịch nhắc uống thuốc của bệnh nhân
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class, 'patient_id');
    }
}
