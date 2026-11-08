<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientStatisticsController extends Controller
{
    public function index(Request $request, Patient $patient)
    {
        // Bộ lọc thời gian
        $period = $request->query('period', 'week');

        if (!in_array($period, ['today', 'week', 'month'], true)) {
            $period = 'week';
        }

        $today = Carbon::today('Asia/Ho_Chi_Minh');

        $start = match ($period) {
            'today' => $today->copy(),
            'week' => $today->copy()->startOfWeek(Carbon::MONDAY),
            'month' => $today->copy()->startOfMonth(),
        };

        $end = $today->copy()->endOfDay();

        // Chỉ lấy ca đã có kết quả done hoặc missed
        // và chỉ thuộc đúng hồ sơ người bệnh
        $results = DB::table('dose_logs')
            ->join(
                'dose_times',
                'dose_logs.dose_time_id',
                '=',
                'dose_times.id'
            )
            ->join(
                'reminders',
                'dose_times.reminder_id',
                '=',
                'reminders.id'
            )
            ->where('reminders.patient_id', $patient->id)
            ->whereIn('dose_logs.status', ['done', 'missed'])
            ->whereBetween('dose_logs.scheduled_at', [
                $start->copy()->startOfDay()->toDateTimeString(),
                $end->toDateTimeString(),
            ])
            ->selectRaw('DATE(dose_logs.scheduled_at) AS day')
            ->selectRaw("
                SUM(
                    CASE WHEN dose_logs.status = 'done'
                    THEN 1 ELSE 0 END
                ) AS done_count
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN dose_logs.status = 'missed'
                    THEN 1 ELSE 0 END
                ) AS missed_count
            ")
            ->groupByRaw('DATE(dose_logs.scheduled_at)')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        // Chuẩn bị dữ liệu từng ngày cho biểu đồ
        $dailyStats = [];
        $totalDone = 0;
        $totalMissed = 0;

        $date = $start->copy();

        while ($date->lte($today)) {
            $key = $date->toDateString();
            $item = $results->get($key);

            $done = (int) ($item->done_count ?? 0);
            $missed = (int) ($item->missed_count ?? 0);

            $totalDone += $done;
            $totalMissed += $missed;

            $dailyStats[] = [
                'date' => $key,
                'label' => $date->format('d/m'),
                'done' => $done,
                'missed' => $missed,
            ];

            $date->addDay();
        }

        $total = $totalDone + $totalMissed;

        // Không hiển thị 0% khi chưa có dữ liệu
        $completionRate = $total > 0
            ? round(($totalDone / $total) * 100, 1)
            : null;

        // Dùng để chia tỷ lệ chiều cao cột biểu đồ
        $maxCount = max(
            1,
            ...array_map(
                fn ($item) => max(
                    $item['done'],
                    $item['missed']
                ),
                $dailyStats
            )
        );

        return view('statistics.index', compact(
            'patient',
            'period',
            'start',
            'today',
            'total',
            'totalDone',
            'totalMissed',
            'completionRate',
            'dailyStats',
            'maxCount'
        ));
    }
}
