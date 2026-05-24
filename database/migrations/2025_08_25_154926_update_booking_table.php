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

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['hotel_code']);
            $table->dropColumn(['room_no']);
            $table->foreignId('room_id')->constrained('rooms')->after('guest_id');
            $table->foreignId('hotel_id')->constrained('hotel_locations')->after('room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('hotel_code')->constrained('hotel_locations');

        });
    }
};
