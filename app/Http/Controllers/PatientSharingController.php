<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientSharingController extends Controller
{
    // Chủ hồ sơ xem mã chia sẻ
    public function show(Patient $patient, PatientCodeService $codes)
    {
        $code = $codes->generate($patient, 'sharing');

        return view('patients.share', compact('patient', 'code'));
    }

    // Trang nhập mã của người chăm sóc phụ
    public function create()
    {
        return view('patients.join');
    }

    // Liên kết với hồ sơ bằng mã chia sẻ
    public function store(Request $request)
    {
        $request->merge([
            'code' => strtoupper(trim((string) $request->input('code'))),
        ]);

        $data = $request->validate([
            'code' => ['required', 'string', 'regex:/^[A-F0-9]{24}$/'],
        ], [
            'code.required' => 'Vui lòng nhập mã chia sẻ.',
            'code.regex' => 'Mã chia sẻ không đúng định dạng.',
        ]);

        $hash = hash('sha256', $data['code']);

        $patient = Patient::where('sharing_hash', $hash)->first();

        if (!$patient) {
            return back()
                ->withErrors(['code' => 'Mã chia sẻ không hợp lệ.'])
                ->withInput();
        }

        $user = Auth::user();

        if ((int) $patient->owner_id === (int) $user->id) {
            return back()->withErrors([
                'code' => 'Bạn đã là người chăm sóc chính của hồ sơ này.',
            ]);
        }

        $alreadyLinked = $user->sharedPatients()
            ->where('patients.id', $patient->id)
            ->exists();

        if ($alreadyLinked) {
            return redirect()
                ->route('patients.index')
                ->with('info', 'Hồ sơ này đã được liên kết với tài khoản của bạn.');
        }

        $patient->caregivers()->syncWithoutDetaching([
            $user->id,
        ]);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Đã liên kết hồ sơ người bệnh thành công.');
    }
}
