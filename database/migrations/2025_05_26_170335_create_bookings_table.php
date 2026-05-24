<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_code', 10);
            $table->foreignId('guest_id')->constrained('guests');
            $table->string('room_no', 10)->nullable();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->date('arrival_date');
            $table->time('arrival_time');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->unsignedInteger('num_adults');
            $table->unsignedInteger('num_children')->default(0);
            $table->text('special_reg')->nullable();
            $table->unsignedInteger('loyalty_points_earned')->default(0);
            $table->unsignedInteger('loyalty_points_redeemed')->default(0);

            $table->softDeletes();
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
