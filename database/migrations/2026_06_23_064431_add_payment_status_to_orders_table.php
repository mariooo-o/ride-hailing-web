<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'ongoing', 'completed', 'cancelled', 'pending_payment', 'paid') DEFAULT 'pending'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};