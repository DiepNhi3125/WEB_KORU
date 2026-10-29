
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caregiver_devices', function (Blueprint $table) {
            $table->id();

            // Người chăm sóc sở hữu thiết bị
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Thông tin thiết bị
            $table->string('device_name')->nullable();

            // Token nhận thông báo
            $table->text('fcm_token')->nullable();

            // Lần cuối thiết bị hoạt động
            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_devices');
    }
};
