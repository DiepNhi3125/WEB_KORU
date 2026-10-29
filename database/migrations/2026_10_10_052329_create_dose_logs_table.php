
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dose_logs', function (Blueprint $table) {
            $table->id();

            // Khung giờ uống thuốc
            $table->foreignId('dose_time_id')
                ->constrained('dose_times')
                ->cascadeOnDelete();

            // Thời điểm dự kiến uống thuốc
            $table->dateTime('scheduled_at');

            // Thời điểm người bệnh xác nhận uống thuốc
            $table->dateTime('confirmed_at')->nullable();

            // Trạng thái lần uống thuốc
            $table->string('status', 20)->default('pending');

            $table->timestamps();

            // Không tạo trùng lần uống thuốc
            $table->unique(['dose_time_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dose_logs');
    }
};
