
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dose_logs', function (Blueprint $table) {
            $table->unsignedInteger('repeat_count')->nullable();
            $table->unsignedInteger('interval_minutes')->nullable();
            $table->dateTime('expires_at')->nullable();

            $table->index(
                ['status', 'expires_at'],
                'dose_logs_status_expiry_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('dose_logs', function (Blueprint $table) {
            $table->dropIndex('dose_logs_status_expiry_idx');

            $table->dropColumn([
                'repeat_count',
                'interval_minutes',
                'expires_at',
            ]);
        });
    }
};
