<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Nama lokasi (teks dari user)
            $table->string('pickup');
            $table->string('destination');

            // Koordinat pickup
            $table->double('pickup_lat');
            $table->double('pickup_lng');

            // Koordinat destination
            $table->double('destination_lat');
            $table->double('destination_lng');

            // Hasil perhitungan jarak (km)
            $table->double('distance');

            // Jenis kendaraan
            $table->enum('vehicle_type', ['Motor', 'Mobil']);

            // Harga (integer, tidak ada desimal untuk rupiah)
            $table->unsignedInteger('price');

            // Status order
            $table->enum('status', ['pending', 'completed', 'cancelled'])
                ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};