<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Siapa yang pesan (customer)
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->onDelete('set null');

            // Siapa yang antar (driver)
            $table->foreignId('driver_id')
                ->nullable()
                ->after('user_id')
                ->constrained('drivers')
                ->onDelete('set null');

            // Update enum status tambah 'ongoing'
            $table->enum('status', [
                'pending',
                'ongoing',
                'completed',
                'cancelled'
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['user_id', 'driver_id']);
        });
    }
};