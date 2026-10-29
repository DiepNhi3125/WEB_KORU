
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();

            // Hồ sơ người bệnh
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // Thông tin đợt nhắc thuốc
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');

            // Quy tắc lặp lại báo thức
            $table->unsignedInteger('repeat_count');
            $table->unsignedInteger('interval_minutes');

            // Trạng thái đợt nhắc
            $table->string('status', 20)->default('active');
            $table->timestamp('stopped_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
