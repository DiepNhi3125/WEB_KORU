<?php
use App\Http\Controllers\PatientPairingController;
use App\Http\Controllers\PatientSharingController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/device/pair', [PatientPairingController::class, 'scan'])
    ->name('device.pair');

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

        Route::get('/patients/join', [PatientSharingController::class, 'create'])
            ->name('patients.join');

        Route::post('/patients/join', [PatientSharingController::class, 'store'])
            ->name('patients.join.store');

        Route::get('/patients/{patient}', [PatientController::class, 'show'])
            ->name('patients.detail');

        // Chủ hồ sơ và người được chia sẻ có thể xem lịch
        Route::get('/patients/{patient}/reminders', [ReminderController::class, 'index'])
            ->middleware('patient.access')
            ->name('reminders.index');
            

        // Chỉ người chăm sóc chính được chỉnh sửa
        Route::middleware('patient.owner')->group(function () {

            Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])
                ->name('patients.edit');

            Route::patch('/patients/{patient}', [PatientController::class, 'update'])
                ->name('patients.update');


            Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])
                ->name('patients.destroy');

            Route::get('/patients/{patient}/share', [PatientSharingController::class, 'show'])
                ->name('patients.share');


            // Người chăm sóc chính tạo QR ghép thiết bị
            Route::get('/patients/{patient}/pair', [PatientPairingController::class, 'show'])
                ->name('patients.pair');

            Route::get('/patients/{patient}/reminders/create', [ReminderController::class, 'create'])
                ->name('reminders.create');
            Route::post('/patients/{patient}/reminders', [ReminderController::class, 'store'])
                ->name('reminders.store');
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
