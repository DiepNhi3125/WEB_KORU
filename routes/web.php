
<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Người chăm sóc: bắt buộc xác thực email
Route::middleware(['auth', 'verified', 'role:caregiver'])
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // Quản lý hồ sơ người bệnh
        Route::get('/patients', [PatientController::class, 'index'])
            ->name('patients.index');

        Route::get('/patients/create', [PatientController::class, 'create'])
            ->name('patients.create');

        Route::post('/patients', [PatientController::class, 'store'])
            ->name('patients.store');

        Route::get('/patients/{patient}', [PatientController::class, 'show'])
            ->name('patients.detail');

        // Chỉ người chăm sóc chính được chỉnh sửa
        Route::middleware('patient.owner')->group(function () {

            Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])
                ->name('patients.edit');

            Route::patch('/patients/{patient}', [PatientController::class, 'update'])
                ->name('patients.update');

        });
    });

// Admin: tài khoản được cấp sẵn
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'Trang quản trị KORU';
        })->name('dashboard');

    });

require __DIR__ . '/auth.php';
