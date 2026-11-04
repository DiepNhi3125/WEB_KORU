<?php

namespace App\Services;

use App\Models\Patient;

class PatientCodeService
{
    public function generate($patient, $type)
    {
        if ($type != 'pairing' && $type != 'sharing') {
            return false;
        }

        $key = config('app.key');

        if (!$key) {
            return false;
        }

        $createdAt = $patient->getRawOriginal('created_at');
        $data = 'koru:' . $type . ':' . $patient->id . ':' . $createdAt;

        $hash = hash_hmac('sha256', $data, $key);

        return strtoupper(substr($hash, 0, 24));
    }

    public function matches($patient, $type)
    {
        $column = '';
        if ($type == 'pairing') {
            $column = 'pairing_hash';
        } elseif ($type == 'sharing') {
            $column = 'sharing_hash';
        } else {
            return false;
        }

        $generatedCode = $this->generate($patient, $type);

        if (!$generatedCode) {
            return false;
        }

        $expectedHash = hash('sha256', $generatedCode);

        return hash_equals($patient->{$column}, $expectedHash);
    }
}