
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();

            // Tài khoản liên quan (có thể chưa tồn tại khi đăng ký)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            // Thông tin nhận OTP
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();

            // Mục đích sử dụng OTP
            $table->string('purpose', 30);

            // Mã OTP đã được băm
            $table->string('code_hash');

            // Thời hạn và số lần nhập sai
            $table->timestamp('expires_at');
            $table->unsignedInteger('failed_attempts')->default(0);

            // Thời điểm OTP đã được sử dụng
            $table->timestamp('consumed_at')->nullable();

            $table->timestamps();

            // Hỗ trợ tra cứu OTP
            $table->index(['email', 'purpose']);
            $table->index(['phone', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
