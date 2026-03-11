<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (pelanggan yang membuat booking)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mechanic_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('vehicle_plate');
            $table->string('vehicle_model');
            $table->text('complaint');
            $table->dateTime('booking_date');

            // kolom untuk fitur tracking status
            $table->enum('status', ['pending', 'process', 'completed', 'cancelled'])->default('pending');

            $table->integer('total_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
