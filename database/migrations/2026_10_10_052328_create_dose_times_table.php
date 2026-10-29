
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dose_times', function (Blueprint $table) {
            $table->id();

            // Đợt nhắc thuốc
            $table->foreignId('reminder_id')
                ->constrained('reminders')
                ->cascadeOnDelete();

            // Giờ uống thuốc trong ngày
            $table->time('time_of_day');

            // Ghi chú của người chăm sóc
            $table->text('note')->nullable();

            // Trạng thái khung giờ
            $table->string('status', 20)->default('active');

            // Thời gian áp dụng khung giờ
            $table->date('effective_from');
            $table->date('effective_until')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dose_times');
    }
};
