<?php

namespace App\Console\Commands;

use App\Models\DoseTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateDoseLogs extends Command
{
    protected $signature = 'doses:generate';

    protected $description = 'Tạo ca uống thuốc khi đến giờ';

    public function handle(): int
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $today = $now->toDateString();
        $created = 0;

        DoseTime::query()
            ->with('reminder')
            ->where('status', 'active')
            ->where('effective_from', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', $today);
            })
            ->whereHas('reminder', function ($query) use ($today) {
                $query->where('status', 'active')
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->chunkById(200, function ($doseTimes) use ($today, $now, &$created) {
                foreach ($doseTimes as $doseTime) {
                    $reminder = $doseTime->reminder;

                    if (!$reminder) {
                        continue;
                    }

                    $scheduledAt = Carbon::parse(
                        $today . ' ' . $doseTime->time_of_day,
                        'Asia/Ho_Chi_Minh'
                    );

                    if ($scheduledAt->gt($now)) {
                        continue;
                    }

                    // Lưu cấu hình X/Y riêng cho ca
                    $repeatCount = (int) $reminder->repeat_count;
                    $intervalMinutes = (int) $reminder->interval_minutes;

                    // Cho phép xác nhận thêm 1 phút sau chuông cuối
                    $expiresAt = $scheduledAt->copy()->addMinutes(
                        $repeatCount * $intervalMinutes + 1
                    );

                    $timestamp = now();

                    $created += DB::table('dose_logs')->insertOrIgnore([
                        'dose_time_id' => $doseTime->id,
                        'scheduled_at' => $scheduledAt->toDateTimeString(),
                        'status' => 'pending',
                        'confirmed_at' => null,
                        'repeat_count' => $repeatCount,
                        'interval_minutes' => $intervalMinutes,
                        'expires_at' => $expiresAt->toDateTimeString(),
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                }
            });

        $this->info("Đã tạo {$created} ca uống thuốc mới.");

        return self::SUCCESS;
    }
}
