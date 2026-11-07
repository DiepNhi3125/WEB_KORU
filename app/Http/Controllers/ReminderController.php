<?php

namespace App\Http\Controllers;

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
            ->orderByDesc('created_at')
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

        return view('reminders.show', compact('patient', 'reminder'));
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
}
