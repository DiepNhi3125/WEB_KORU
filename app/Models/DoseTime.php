<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'time_of_day',
    'note',
    'effective_from',
])]
class DoseTime extends Model
{
    protected function casts(): array
    {
        return [
            'effective_from' => 'date',
            'effective_until' => 'date',
        ];
    }

    // Lich nhac chua moc gio nay
    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class, 'reminder_id');
    }

    // Cac lan ghi nhan uong thuoc
    public function doseLogs(): HasMany
    {
        return $this->hasMany(DoseLog::class, 'dose_time_id');
    }
}

