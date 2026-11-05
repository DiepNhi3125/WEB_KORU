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
    public function index()
    {
        $user = Auth::user();

        $ownedPatients = $user->ownedPatients()
            ->orderBy('created_at', 'desc')
            ->get();

        $sharedPatients = $user->sharedPatients()
            ->with('owner:id,name')
            ->orderBy('patients.created_at', 'desc')
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
        $user = Auth::user();

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'phone' => [
                'required',
                'string',
                'regex:/^0[0-9]{9}$/',
            ],
        ]);

        DB::transaction(function () use ($data, $codes, $user) {
            $patient = new Patient();
            $patient->fill($data);
            $patient->owner_id = $user->id;

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

    public function show(Patient $patient)
    {
        $user = Auth::user();

        $isOwner = (int) $patient->owner_id === (int) $user->id;

        $isShared = $patient->caregivers()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isOwner && !$isShared) {
            return redirect()->route('patients.index');
        }

        $patient->load('owner');

        return view('patients.detail', compact(
            'patient',
            'isOwner'
        ));
    }
}
