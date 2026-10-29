
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_user', function (Blueprint $table) {
            // Người bệnh được chia sẻ
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // Người chăm sóc được chia sẻ
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Không cho phép chia sẻ trùng
            $table->primary(['patient_id', 'user_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_user');
    }
};
