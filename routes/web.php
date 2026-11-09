<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientStatisticsController;
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
Route::middleware(['auth', 'account.unlocked', 'verified', 'role:caregiver'])
    ->group(function () {

        Route::get('/dashboard', function () {
            $user = auth()->user();

            $patients = $user->ownedPatients()
                ->select('patients.id', 'patients.full_name')
                ->get()
                ->concat(
                    $user->sharedPatients()
                        ->select('patients.id', 'patients.full_name')
                        ->get()
                )
                ->unique('id')
                ->sortBy('full_name')
                ->values();

            return view('dashboard', compact('patients'));
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
        // Xem chi tiết lịch nhắc
        Route::get('/patients/{patient}/reminders/{reminder}', [ReminderController::class, 'show'])
            ->whereNumber('reminder')
            ->middleware('patient.access')
            ->name('reminders.show');

        Route::get('/patients/{patient}/statistics', [PatientStatisticsController::class, 'index'])
            ->middleware('patient.access')
            ->name('patients.statistics');


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

            Route::get('/patients/{patient}/reminders/{reminder}/edit', [ReminderController::class, 'edit'])
                ->whereNumber('reminder')
                ->name('reminders.edit');

            // Lưu thông tin lịch nhắc đã chỉnh sửa
            Route::patch('/patients/{patient}/reminders/{reminder}', [ReminderController::class, 'update'])
                ->whereNumber('reminder')
                ->name('reminders.update');
            Route::patch('/patients/{patient}/reminders/{reminder}/stop', [ReminderController::class, 'stop'])
                ->whereNumber('reminder')
                ->name('reminders.stop');
        });
    });


// Admin: tài khoản được cấp sẵn
// Admin: tài khoản được cấp sẵn
Route::middleware(['auth', 'account.unlocked', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])
            ->name('users.index');

        // Chi tiết tài khoản
        Route::get('/users/{user}', [AdminController::class, 'showUser'])
            ->whereNumber('user')
            ->name('users.show');

        // Khóa tài khoản
        Route::patch('/users/{user}/lock', [AdminController::class, 'lockUser'])
            ->whereNumber('user')
            ->name('users.lock');

        // Mở khóa tài khoản
        Route::patch('/users/{user}/unlock', [AdminController::class, 'unlockUser'])
            ->whereNumber('user')
            ->name('users.unlock');
    });




require __DIR__ . '/auth.php';
