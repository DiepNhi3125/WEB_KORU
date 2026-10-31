<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KoruDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = now();

            // Tài khoản của bà đã tồn tại: KHÔNG tạo lại, KHÔNG sửa.
            $me = DB::table('users')
                ->where('phone', '0868433401')
                ->first();

            if (!$me) {
                throw new \RuntimeException(
                    'Không tìm thấy tài khoản đã đăng ký: 0868433401'
                );
            }

            // 14 tài khoản NCS giả
            $people = [
                ['Nguyễn Thảo Nguyên', '0934827156', 'thaonguyen2002@gmail.com'],
                ['Trần Quốc Huy', '0983652041', 'tranquochuy98@gmail.com'],
                ['Lê Ngọc Trâm', '0917246038', 'ngoctramle@gmail.com'],
                ['Phạm Gia Bảo', '0974382150', 'giabao97@gmail.com'],
                ['Võ Thanh Hà', '0906824137', 'thanhha2001@gmail.com'],
                ['Đặng Minh Khoa', '0385271946', 'minhkhoadang@gmail.com'],
                ['Bùi Khánh Vy', '0348196257', 'khanhvy2003@gmail.com'],
                ['Đỗ Anh Tuấn', '0963157824', 'anhtuan99@gmail.com'],
                ['Phan Thùy Linh', '0834627195', 'thuylinh2000@gmail.com'],
                ['Ngô Đức Minh', '0705832169', 'ducminh1998@gmail.com'],
                ['Huỳnh Ngọc Diễm', '0762418953', 'ngocdiem2002@gmail.com'],
                ['Mai Hoàng Phúc', '0895371264', 'hoangphucmai@gmail.com'],
                ['Trương Mỹ Hạnh', '0326854719', 'myhanh2001@gmail.com'],
                ['Hồ Nhật Quang', '0853269147', 'nhatquang2000@gmail.com'],
            ];

            $userIds = [];

            foreach ($people as [$name, $phone, $email]) {
                $existing = DB::table('users')
                    ->where('email', $email)
                    ->first();

                if ($existing) {
                    if ($existing->phone !== $phone) {
                        throw new \RuntimeException(
                            "Email {$email} đã tồn tại với SĐT khác."
                        );
                    }

                    $userIds[] = $existing->id;
                    continue;
                }

                if (DB::table('users')->where('phone', $phone)->exists()) {
                    throw new \RuntimeException(
                        "Số điện thoại {$phone} đã tồn tại."
                    );
                }

                $userIds[] = DB::table('users')->insertGetId([
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'password' => Hash::make('KoruDemo@123'),
                    'role' => 'caregiver',
                    'account_status' => 'active',
                    'email_verified_at' => $now,
                    'failed_login_attempts' => 0,
                    'locked_until' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Tài khoản thứ 15 là tài khoản hiện có của bà.
            $userIds[] = $me->id;

            // 10 hồ sơ người bệnh
            $patients = [
                ['Nguyễn Văn Lâm', '1955-04-12', 'male'],
                ['Trần Thị Bích', '1958-08-24', 'female'],
                ['Lê Văn Hòa', '1960-01-19', 'male'],
                ['Phạm Thị Kim Oanh', '1952-11-06', 'female'],
                ['Võ Văn Dũng', '1957-03-15', 'male'],
                ['Đặng Thị Thu', '1963-09-27', 'female'],
                ['Bùi Văn Phước', '1954-06-03', 'male'],
                ['Đỗ Thị Thanh', '1959-12-18', 'female'],
                ['Phan Văn Hùng', '1961-02-09', 'male'],
                ['Ngô Thị Hồng', '1956-07-22', 'female'],
            ];

            $patientIds = [];

            foreach ($patients as $i => [$name, $dob, $gender]) {
                $key = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);

                $pairHash = hash('sha256', "KORU-PAIR-{$key}");
                $shareHash = hash('sha256', "KORU-SHARE-{$key}");

                $patient = DB::table('patients')
                    ->where('pairing_hash', $pairHash)
                    ->first();

                if ($patient) {
                    if ($patient->owner_id != $userIds[$i]) {
                        throw new \RuntimeException(
                            "Hồ sơ mẫu {$key} có chủ sở hữu khác."
                        );
                    }
                    $patientIds[] = $patient->id;
                } else {
                    $patientIds[] = DB::table('patients')->insertGetId([
                        'owner_id' => $userIds[$i],
                        'full_name' => $name,
                        'birth_date' => $dob,
                        'gender' => $gender,
                        'phone' => null,
                        'pairing_hash' => $pairHash,
                        'sharing_hash' => $shareHash,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Chia sẻ hồ sơ: bà được chia sẻ hồ sơ đầu tiên
            $shares = [
                [0, 14], [1, 10], [2, 11], [3, 12],
                [4, 13], [5, 10], [6, 11], [7, 12],
            ];

            foreach ($shares as [$p, $u]) {
                DB::table('patient_user')->insertOrIgnore([
                    'patient_id' => $patientIds[$p],
                    'user_id' => $userIds[$u],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Thiết bị người bệnh
            $devices = [
                'Samsung Galaxy A54', 'iPhone 11', 'OPPO Reno 8',
                'Samsung Galaxy A35', 'Xiaomi Redmi Note 12',
                'iPhone 12', 'Samsung Galaxy M34', 'Vivo Y36',
                'OPPO A78', 'iPhone 13',
            ];

            foreach ($patientIds as $i => $patientId) {
                $token = hash('sha256', "KORU-PATIENT-DEVICE-{$i}");

                if (!DB::table('patient_devices')
                    ->where('token_hash', $token)->exists()) {
                    DB::table('patient_devices')->insert([
                        'patient_id' => $patientId,
                        'device_name' => $devices[$i],
                        'token_hash' => $token,
                        'fcm_token' => null,
                        'revoked_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                if ($i < 2) {
                    $old = hash('sha256', "KORU-OLD-DEVICE-{$i}");

                    if (!DB::table('patient_devices')
                        ->where('token_hash', $old)->exists()) {
                        DB::table('patient_devices')->insert([
                            'patient_id' => $patientId,
                            'device_name' => 'Thiết bị cũ',
                            'token_hash' => $old,
                            'fcm_token' => null,
                            'revoked_at' => '2026-10-20 09:00:00',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }

            // Thiết bị của NCS mẫu
            $caregiverDevices = [
                'Chrome - Windows', 'Safari - iPhone',
                'Edge - Windows', 'Chrome - Android',
                'Chrome - Windows 11', 'Safari - macOS',
                'Samsung Internet', 'Chrome - Laptop',
                'Edge - Laptop', 'Safari - iOS',
                'Chrome - Android 14', 'Firefox - Windows',
                'Chrome - macOS', 'Edge - Windows 10',
            ];

            for ($i = 0; $i < 14; $i++) {
                DB::table('caregiver_devices')->updateOrInsert(
                    [
                        'user_id' => $userIds[$i],
                        'device_name' => $caregiverDevices[$i],
                    ],
                    [
                        'fcm_token' => null,
                        'last_seen_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            DB::table('caregiver_devices')->updateOrInsert(
                [
                    'user_id' => $userIds[0],
                    'device_name' => 'iPhone - Người chăm sóc',
                ],
                [
                    'fcm_token' => null,
                    'last_seen_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // OTP mẫu đã hết hạn
            for ($i = 0; $i < 10; $i++) {
                DB::table('otps')->updateOrInsert(
                    [
                        'code_hash' => hash(
                            'sha256', "KORU-EXPIRED-OTP-{$i}"
                        ),
                    ],
                    [
                        'user_id' => $userIds[$i],
                        'email' => null,
                        'phone' => $people[$i][1],
                        'purpose' => 'pair_device',
                        'expires_at' => '2026-10-15 10:05:00',
                        'failed_attempts' => 0,
                        'consumed_at' => $i % 2 === 0
                            ? '2026-10-15 10:02:00'
                            : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            // Lịch nhắc, giờ nhắc và nhật ký
            $names = [
    ['Uống thuốc huyết áp', 'Bổ sung vitamin D'],
    ['Thuốc tiểu đường', 'Uống thuốc buổi tối'],
    ['Thuốc sau ăn sáng', 'Bổ sung canxi'],
    ['Thuốc điều trị dạ dày', 'Uống thuốc trước khi ngủ'],
    ['Thuốc tim mạch', 'Vitamin tổng hợp'],
    ['Thuốc theo toa tháng 10', 'Thuốc buổi chiều'],
    ['Thuốc huyết áp hằng ngày', 'Bổ sung vitamin B'],
    ['Thuốc sau bữa trưa', 'Thuốc buổi sáng'],
    ['Thuốc điều trị định kỳ', 'Bổ sung sắt'],
    ['Thuốc theo đơn bác sĩ', 'Thuốc trước bữa ăn'],
];
            foreach ($patientIds as $i => $patientId) {
                for ($r = 0; $r < 2; $r++) {
                    $name = $names[$i][$r];

                    $stopped = ($i === 0 && $r === 0);

                    $reminder = DB::table('reminders')
                        ->where('patient_id', $patientId)
                        ->where('name', $name)
                        ->first();

                    $reminderId = $reminder
                        ? $reminder->id
                        : DB::table('reminders')->insertGetId([
                            'patient_id' => $patientId,
                            'name' => $name,
                            'start_date' => '2026-10-20',
                            'end_date' => '2026-11-15',
                            'repeat_count' => $r === 0 ? 3 : 2,
                            'interval_minutes' => $r === 0 ? 5 : 10,
                            'status' => $stopped ? 'stopped' : 'active',
                            'stopped_at' => $stopped
                                ? '2026-10-26 00:00:00'
                                : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                    $times = $r === 0
                        ? ['07:30:00', '12:00:00']
                        : ['18:00:00', '20:30:00'];

                    foreach ($times as $time) {
                        $doseTime = DB::table('dose_times')
                            ->where('reminder_id', $reminderId)
                            ->where('time_of_day', $time)
                            ->where('effective_from', '2026-10-20')
                            ->first();

                        $doseTimeId = $doseTime
                            ? $doseTime->id
                            : DB::table('dose_times')->insertGetId([
                                'reminder_id' => $reminderId,
                                'time_of_day' => $time,
                                'note' => null,
                                'status' => $stopped ? 'stopped' : 'active',
                                'effective_from' => '2026-10-20',
                                'effective_until' => $stopped
                                    ? '2026-10-25'
                                    : null,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);

                        for ($day = 20; $day <= 25; $day++) {
                            $scheduledAt = sprintf(
                                '2026-10-%02d', $day
                            ) . ' ' . $time;

                            $done = (
                                ($day + $i + $r
                                    + (int) substr($time, 0, 2)
                                ) % 4
                            ) !== 0;

                            if (!DB::table('dose_logs')
                                ->where('dose_time_id', $doseTimeId)
                                ->where('scheduled_at', $scheduledAt)
                                ->exists()) {
                                DB::table('dose_logs')->insert([
                                    'dose_time_id' => $doseTimeId,
                                    'scheduled_at' => $scheduledAt,
                                    'confirmed_at' => $done
                                        ? Carbon::parse($scheduledAt)
                                            ->addMinutes(2)
                                        : null,
                                    'status' => $done
                                        ? 'done'
                                        : 'missed',
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ]);
                            }
                        }
                    }
                }
            }
        });
    }
}
