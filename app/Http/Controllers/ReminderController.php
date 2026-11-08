<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReminderController extends Controller
{
    // Chủ hồ sơ và người chăm sóc phụ đều được xem
    public function index(Patient $patient)
    {
        $isOwner = (int) $patient->owner_id === (int) Auth::id();

        $reminders = $patient->reminders()
            ->with('doseTimes')
            ->orderBy('id', 'asc')
            ->get();

        return view('reminders.index', compact(
            'patient',
            'reminders',
            'isOwner'
        ));
    }

    public function show(Patient $patient, $reminder)
    {
        $reminder = $patient->reminders()
            ->with([
                'doseTimes' => function ($query) {
                    $query->orderBy('time_of_day');
                }
            ])
            ->findOrFail($reminder);
        // Số thứ tự của lịch trong hồ sơ người bệnh
        $displayNumber = $patient->reminders()
            ->where('id', '<=', $reminder->id)
            ->count();
        return view('reminders.show', compact('patient', 'reminder', 'displayNumber'));
    }


    // Chỉ người chăm sóc chính được tạo lịch
    public function create(Patient $patient)
    {
        return view('reminders.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'repeat_count' => ['required', 'integer', 'min:0', 'max:20'],
            'interval_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'times' => ['required', 'array', 'min:1', 'max:12'],
            'times.*' => ['required', 'date_format:H:i', 'distinct'],
        ]);

        DB::transaction(function () use ($patient, $data) {

            $reminder = $patient->reminders()->create([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'repeat_count' => $data['repeat_count'],
                'interval_minutes' => $data['interval_minutes'],
            ]);

            foreach ($data['times'] as $time) {
                $reminder->doseTimes()->create([
                    'time_of_day' => $time,
                    'effective_from' => $data['start_date'],
                ]);
            }
        });

        return redirect()
            ->route('reminders.index', $patient)
            ->with('success', 'Đã tạo lịch nhắc thuốc thành công.');
    }


    // Hiển thị form chỉnh sửa lịch nhắc
    public function edit(Patient $patient, $reminder)
    {
        $reminder = $patient->reminders()
            ->with('doseTimes')
            ->findOrFail($reminder);

        if ($reminder->status !== 'active') {
            return redirect()
                ->route('reminders.show', [$patient, $reminder->id])
                ->with('error', 'Không thể chỉnh sửa lịch đã dừng.');
        }

        $currentTimes = $reminder->doseTimes
            ->filter(
                fn($time) =>
                    $time->status === 'active'
                    && $time->effective_until === null
            )
            ->sortBy('time_of_day');

        return view('reminders.edit', compact(
            'patient',
            'reminder',
            'currentTimes'
        ));
    }

    // Lưu thay đổi lịch nhắc
    public function update(Request $request, Patient $patient, $reminder)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:start_date'
            ],
            'repeat_count' => [
                'required',
                'integer',
                'min:0',
                'max:20'
            ],
            'interval_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:1440'
            ],
            'times' => ['required', 'array', 'min:1', 'max:12'],
            'times.*' => [
                'required',
                'date_format:H:i',
                'distinct'
            ],
        ]);

        $today = Carbon::today('Asia/Ho_Chi_Minh');
        $tomorrow = $today->copy()->addDay();

        DB::transaction(function () use ($patient, $reminder, $data, $today, $tomorrow) {
            // Khóa lịch trong lúc cập nhật để tránh sửa đồng thời
            $reminder = $patient->reminders()
                ->lockForUpdate()
                ->findOrFail($reminder);

            if ($reminder->status !== 'active') {
                throw ValidationException::withMessages([
                    'name' => 'Không thể chỉnh sửa lịch đã dừng.',
                ]);
            }

            $oldStart = $reminder->start_date->toDateString();

            $newStart = Carbon::parse($data['start_date']);
            $newEnd = Carbon::parse($data['end_date']);

            $hasStarted = $reminder->start_date->lte($today);

            // Không thay đổi ngày bắt đầu của lịch đã chạy
            if ($hasStarted && $data['start_date'] !== $oldStart) {
                throw ValidationException::withMessages([
                    'start_date' =>
                        'Lịch đã bắt đầu nên không thể đổi ngày bắt đầu.',
                ]);
            }

            // Không tạo lịch lùi về quá khứ khi lịch chưa chạy
            if (!$hasStarted && $newStart->lt($today)) {
                throw ValidationException::withMessages([
                    'start_date' =>
                        'Ngày bắt đầu mới không được ở quá khứ.',
                ]);
            }

            // Ngày kết thúc của lịch chỉnh sửa phải còn hiệu lực
            if ($newEnd->lt($today)) {
                throw ValidationException::withMessages([
                    'end_date' =>
                        'Ngày kết thúc phải từ hôm nay trở đi.',
                ]);
            }

            // Các mốc giờ hiện tại
            $currentTimes = $reminder->doseTimes()
                ->where('status', 'active')
                ->whereNull('effective_until')
                ->lockForUpdate()
                ->get();

            // So sánh giờ cũ và giờ mới
            $oldTimes = $currentTimes
                ->map(fn($time) => substr($time->time_of_day, 0, 5))
                ->sort()
                ->values()
                ->all();

            $newTimes = $data['times'];
            sort($newTimes);

            $timesChanged = $oldTimes !== $newTimes;

            // Giờ mới có hiệu lực từ ngày mai nếu lịch đã chạy
            $effectiveDate = $hasStarted ? $tomorrow : $newStart;

            if ($timesChanged && $effectiveDate->gt($newEnd)) {
                throw ValidationException::withMessages([
                    'times' =>
                        'Không còn ngày áp dụng cho giờ uống mới.',
                ]);
            }

            // Không sửa lịch chưa bắt đầu nếu đã phát sinh nhật ký
            if (
                !$hasStarted
                && ($timesChanged || $data['start_date'] !== $oldStart)
                && $reminder->doseTimes()
                    ->whereHas('doseLogs')
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'times' =>
                        'Lịch đã có nhật ký, không thể thay đổi các giờ cũ.',
                ]);
            }

            // Cập nhật thông tin chung
            $reminder->update([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'repeat_count' => $data['repeat_count'],
                'interval_minutes' => $data['interval_minutes'],
            ]);

            // Nếu lịch chưa bắt đầu và chỉ đổi ngày bắt đầu
            if (
                !$hasStarted
                && !$timesChanged
                && $data['start_date'] !== $oldStart
            ) {
                foreach ($currentTimes as $doseTime) {
                    $doseTime->effective_from = $data['start_date'];
                    $doseTime->save();
                }
            }

            // Chỉ xử lý các mốc giờ khi thực sự có thay đổi
            if ($timesChanged) {

                foreach ($currentTimes as $doseTime) {

                    if (
                        $hasStarted
                        && $doseTime->effective_from->lte($today)
                    ) {
                        // Kết thúc giờ cũ sau hôm nay
                        $doseTime->effective_until =
                            $today->toDateString();
                    } else {
                        // Mốc giờ chưa có hiệu lực: hủy kích hoạt
                        // nhưng giữ bản ghi, không xóa
                        $doseTime->status = 'stopped';
                    }

                    $doseTime->save();
                }

                // Tạo các giờ uống mới
                foreach ($newTimes as $time) {
                    $reminder->doseTimes()->create([
                        'time_of_day' => $time,
                        'effective_from' =>
                            $effectiveDate->toDateString(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('reminders.show', [
                'patient' => $patient,
                'reminder' => $reminder,
            ])
            ->with('success', 'Đã cập nhật lịch nhắc thành công.');
    }


    // Dừng lịch nhắc uống thuốc
    public function stop(Patient $patient, $reminder)
    {
        // Chỉ tìm lịch nhắc thuộc đúng hồ sơ người bệnh
        $reminder = $patient->reminders()
            ->findOrFail($reminder);

        // Không xử lý lại lịch đã dừng hoặc không còn hoạt động
        if ($reminder->status !== 'active') {
            return redirect()
                ->route('reminders.show', [$patient, $reminder->id])
                ->with('warning', 'Lịch nhắc này không còn hoạt động.');
        }

        // Chỉ cập nhật trạng thái, không xóa dữ liệu
        $reminder->status = 'stopped';
        $reminder->stopped_at = now();
        $reminder->save();

        return redirect()
            ->route('reminders.show', [$patient, $reminder->id])
            ->with('success', 'Đã dừng lịch nhắc uống thuốc thành công.');
    }

}
