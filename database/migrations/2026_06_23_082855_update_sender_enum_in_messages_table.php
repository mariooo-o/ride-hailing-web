<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('messages')->where('sender', 'admin')->update(['sender' => 'driver']);

        Schema::table('messages', function (Blueprint $table) {
            $table->enum('sender', ['customer', 'driver'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('sender', ['customer', 'admin'])->change();
        });
    }
};