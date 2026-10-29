<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'scheduled_at',
])]
class DoseLog extends Model
{
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    // Moc gio uong thuoc cua ban ghi nay
    public function doseTime(): BelongsTo
    {
        return $this->belongsTo(DoseTime::class, 'dose_time_id');
    }
}
