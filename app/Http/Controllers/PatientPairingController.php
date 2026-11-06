<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientCodeService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;

class PatientPairingController extends Controller
{
    // Người chăm sóc chính xem QR ghép thiết bị
    public function show(Patient $patient, PatientCodeService $codes)
    {
        // Lấy mã ghép thiết bị cố định
        $code = $codes->generate($patient, 'pairing');

        // Tạo đường dẫn ghép thiết bị
        $pairUrl = route('device.pair', ['code' => $code]);

        // Tạo mã QR
        $writer = new SvgWriter();

        $qrCode = $writer->write(
            new QrCode(
                data: $pairUrl,
                size: 260,
                margin: 10
            )
        )->getDataUri();

        return view('patients.pair', compact('patient', 'qrCode'));
    }

    // Người bệnh mở đường dẫn từ QR
    public function scan(Request $request)
    {
        $code = strtoupper(trim((string) $request->query('code', '')));

        $valid = false;

        if (preg_match('/^[A-F0-9]{24}$/', $code)) {
            $valid = Patient::where(
                'pairing_hash',
                hash('sha256', $code)
            )->exists();
        }

        return view('patient_devices.pair', compact('valid'));
    }
}
