<?php

namespace App\Console\Commands;

use App\Models\DoseLog;
use App\Models\User;
use App\Notifications\DoseResultNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProcessMissedDoses extends Command
{
    protected $signature = 'doses:missed
        {--dry-run : Chỉ đếm ca quá hạn, không cập nhật}';

    protected $description = 'Chuyển ca uống quá hạn thành missed và cảnh báo NCS';

    public function handle(): int
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString();

        $query = DoseLog::query()
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now);

        if ($this->option('dry-run')) {
            $this->info('Số ca quá hạn: ' . $query->count());

            return self::SUCCESS;
        }

        $processed = 0;

        $query->chunkById(100, function ($logs) use ($now, &$processed) {
            foreach ($logs as $item) {
                DB::transaction(function () use ($item, $now, &$processed) {
                    // Khóa bản ghi để hai tiến trình không xử lý trùng
                    $log = DoseLog::query()
                        ->whereKey($item->id)
                        ->where('status', 'pending')
                        ->where('expires_at', '<=', $now)
                        ->lockForUpdate()
                        ->first();

                    if (!$log) {
                        return;
                    }

                    $reminder = $log->doseTime?->reminder;
                    $patient = $reminder?->patient;

                    if (!$patient) {
                        return;
                    }

                    // NCS chính và các NCS phụ được chia sẻ
                    $recipientIds = $patient->caregivers()
                        ->pluck('users.id')
                        ->push($patient->owner_id)
                        ->unique();

                    $recipients = User::query()
                        ->whereIn('id', $recipientIds)
                        ->where('role', 'caregiver')
                        ->get();

                    // Chốt kết quả của ca uống
                    $log->status = 'missed';
                    $log->save();

                    // Lưu thông báo ngay trong cùng transaction
                    Notification::sendNow(
                        $recipients,
                        new DoseResultNotification([
                            'event' => 'missed',
                            'patient_id' => $patient->id,
                            'reminder_id' => $reminder->id,
                            'dose_log_id' => $log->id,
                            'patient_name' => $patient->full_name,
                            'reminder_name' => $reminder->name,
                            'scheduled_at' => $log->scheduled_at->format('Y-m-d H:i:s'),
                            'message' => 'Người bệnh ' . $patient->full_name
                                . ' đã bỏ lỡ ca uống thuốc theo lịch '
                                . $reminder->name . '.',
                        ])
                    );

                    $processed++;
                });
            }
        });

        $this->info("Đã chuyển {$processed} ca sang missed.");

        return self::SUCCESS;
    }
}
