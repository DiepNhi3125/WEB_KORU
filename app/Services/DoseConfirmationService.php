<?php

namespace App\Services;

use App\Models\DoseTime;
use App\Models\User;
use App\Notifications\DoseResultNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DoseConfirmationService
{
    public function confirm(int $doseLogId): bool
    {
        return DB::transaction(function () use ($doseLogId) {
            $log = DB::table('dose_logs')
                ->where('id', $doseLogId)
                ->lockForUpdate()
                ->first();

            $now = Carbon::now('Asia/Ho_Chi_Minh')
                ->toDateTimeString();

            // Chỉ xác nhận ca đang chờ và còn thời hạn
            if (
                !$log ||
                $log->status !== 'pending' ||
                $log->expires_at === null ||
                $log->scheduled_at > $now ||
                $log->expires_at <= $now
            ) {
                return false;
            }

            $doseTime = DoseTime::with('reminder.patient')
                ->find($log->dose_time_id);

            $reminder = $doseTime?->reminder;
            $patient = $reminder?->patient;

            if (!$patient) {
                return false;
            }

            // Cập nhật đúng ca uống, không tạo bản ghi khác
            DB::table('dose_logs')
                ->where('id', $doseLogId)
                ->update([
                    'status' => 'done',
                    'confirmed_at' => $now,
                    'updated_at' => $now,
                ]);

            // Lấy NCS chính và các NCS phụ
            $recipientIds = $patient->caregivers()
                ->pluck('users.id')
                ->push($patient->owner_id)
                ->unique();

            $recipients = User::query()
                ->whereIn('id', $recipientIds)
                ->where('role', 'caregiver')
                ->get();

            Notification::sendNow(
                $recipients,
                new DoseResultNotification([
                    'event' => 'done',
                    'patient_id' => $patient->id,
                    'reminder_id' => $reminder->id,
                    'dose_log_id' => $doseLogId,
                    'patient_name' => $patient->full_name,
                    'reminder_name' => $reminder->name,
                    'scheduled_at' => $log->scheduled_at,
                    'confirmed_at' => $now,
                    'message' => 'Người bệnh '
                        . $patient->full_name
                        . ' đã xác nhận uống thuốc theo lịch '
                        . $reminder->name . '.',
                ])
            );

            return true;
        }, 3);
    }
}
