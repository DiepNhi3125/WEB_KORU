
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

        Route::get('/patients', [PatientController::class, 'index'])
        ->name('patients.index');

        Route::get('/patients/create', [PatientController::class, 'create'])
        ->name('patients.create');

        Route::post('/patients', [PatientController::class, 'store'])
        ->name('patients.store');
        });

// Admin: tài khoản được cấp sẵn, không qua đăng ký công khai
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return 'Trang quản trị KORU';
        })->name('dashboard');
    });

require __DIR__.'/auth.php';
