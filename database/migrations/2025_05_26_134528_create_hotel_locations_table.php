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
        Schema::create('hotel_locations', function (Blueprint $table) {
            $table->id();
            $table->string("hotel_video_url")->nullable();
            $table->string("location_name");
            $table->decimal("lat", 12, 8);
            $table->decimal("long", 13, 8);
            $table->json("address");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_locations');
    }
};
