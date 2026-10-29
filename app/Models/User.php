<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'phone',
    'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime',
            'failed_login_attempts' => 'integer',
        ];
    }

    // Những bệnh nhân do người chăm sóc này sở hữu
    public function ownedPatients(): HasMany
    {
        return $this->hasMany(Patient::class, 'owner_id');
    }

    // Những bệnh nhân được chia sẻ quyền chăm sóc
    public function sharedPatients(): BelongsToMany
    {
        return $this->belongsToMany(
            Patient::class,
            'patient_user',
            'user_id',
            'patient_id'
        )->withTimestamps();
    }

    // Thiết bị của người chăm sóc
    public function caregiverDevices(): HasMany
    {
        return $this->hasMany(CaregiverDevice::class, 'user_id');
    }

    // Các mã OTP liên quan đến tài khoản
    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class, 'user_id');
    }
}
