<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $ownedPatients = $user->ownedPatients()
            ->latest()
            ->get();

        $sharedPatients = $user->sharedPatients()
            ->with('owner:id,name')
            ->orderByDesc('patients.created_at')
            ->get();

        return view('patients.index', compact(
            'ownedPatients',
            'sharedPatients'
        ));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request, PatientCodeService $codes)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'phone' => [
                'nullable',
                'string',
                'regex:/^0[0-9]{9}$/',
            ],
        ]);

        DB::transaction(function () use ($data, $codes) {
            $patient = new Patient();
            $patient->fill($data);
            $patient->owner_id = Auth::id();

            $patient->pairing_hash = bin2hex(random_bytes(32));
            $patient->sharing_hash = bin2hex(random_bytes(32));
            $patient->save();

            $patient->pairing_hash = hash(
                'sha256',
                $codes->generate($patient, 'pairing')
            );

            $patient->sharing_hash = hash(
                'sha256',
                $codes->generate($patient, 'sharing')
            );

            $patient->save();
        });

        return redirect()
            ->route('patients.index')
            ->with('success', 'Đã tạo hồ sơ người bệnh thành công.');
    }
}