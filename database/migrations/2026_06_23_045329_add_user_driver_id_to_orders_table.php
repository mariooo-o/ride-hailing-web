<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('orders', 'driver_id')) {
                $table->foreignId('driver_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('drivers')
                    ->onDelete('set null');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            // Pertahankan semua status yang sudah dipakai project
            $table->enum('status', [
                'pending',
                'pending_payment',
                'ongoing',
                'paid',
                'completed',
                'cancelled',
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        // sengaja dikosongkan, kolom user_id/driver_id sudah ada sejak awal
    }
};