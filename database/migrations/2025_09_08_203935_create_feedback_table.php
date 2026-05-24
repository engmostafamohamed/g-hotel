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
        // Drop if it already exists
        Schema::dropIfExists('feedback');

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');

            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->unique('booking_id');

            $table->unsignedBigInteger('service_reservation_id')->nullable();
            $table->foreign('service_reservation_id')->references('id')->on('service_reservations')->onDelete('cascade');
            $table->unique('service_reservation_id');

            $table->tinyInteger('rating')->unsigned()->comment('1 to 5');
            $table->text('comment')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
