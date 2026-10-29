<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'start_date',
    'end_date',
    'repeat_count',
    'interval_minutes',
])]
class Reminder extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'repeat_count' => 'integer',
            'interval_minutes' => 'integer',
            'stopped_at' => 'datetime',
        ];
    }

    // Benh nhan so huu lich nhac
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Cac moc gio uong thuoc
    public function doseTimes(): HasMany
    {
        return $this->hasMany(DoseTime::class, 'reminder_id');
    }
}

