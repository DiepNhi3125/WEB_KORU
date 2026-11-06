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
    public function edit($id)
{
    $user = Auth::user();

    // Tìm người bệnh theo ID
    $patient = Patient::find($id);

    if (!$patient) {
        return redirect()
            ->route('patients.index')
            ->with('status', 'Không tìm thấy hồ sơ người bệnh.');
    }

    // Kiểm tra quyền chăm sóc chính
    if ($patient->owner_id != $user->id) {
        return redirect()
            ->route('patients.index')
            ->with('status', 'Bạn không có quyền chỉnh sửa hồ sơ này.');
    }

    return view('patients.edit', compact('patient'));
}

public function update(Request $request, $id)
{
    $user = Auth::user();

    // Tìm người bệnh theo ID
    $patient = Patient::find($id);

    if (!$patient) {
        return redirect()
            ->route('patients.index')
            ->with('status', 'Không tìm thấy hồ sơ người bệnh.');
    }

    // Kiểm tra quyền chăm sóc chính
    if ($patient->owner_id != $user->id) {
        return redirect()
            ->route('patients.index')
            ->with('status', 'Bạn không có quyền chỉnh sửa hồ sơ này.');
    }

    $data = $request->validate([
        'full_name'  => ['required', 'string', 'max:255'],
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
        'gender'     => ['nullable', Rule::in(['male', 'female', 'other'])],
        'phone'      => ['required', 'string', 'regex:/^0[0-9]{9}$/'],
    ]);

    
    $patient->update($data);

    return redirect()
        ->route('patients.index')
        ->with('status', 'Đã cập nhật hồ sơ người bệnh thành công.');
}
}
