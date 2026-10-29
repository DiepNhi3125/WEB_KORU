
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_devices', function (Blueprint $table) {
            $table->id();

            // Hồ sơ người bệnh
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // Thông tin thiết bị
            $table->string('device_name')->nullable();

            // Xác thực phiên thiết bị
            $table->string('token_hash')->nullable();

            // Token nhận thông báo (nếu hỗ trợ FCM)
            $table->text('fcm_token')->nullable();

            // Thời gian ghép và vô hiệu hóa
            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();

            $table->index(['patient_id', 'revoked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_devices');
    }
};
