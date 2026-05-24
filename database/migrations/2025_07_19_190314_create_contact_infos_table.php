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
        // Drop the old contact_numbers table
        Schema::dropIfExists('contact_numbers');

        // Create the new dynamic contact_infos table
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_location_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type');
            $table->json('label')->nullable(); // optional display label, translatable
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');

        // Recreate contact_numbers
        Schema::create('contact_numbers', function (Blueprint $table) {
            $table->id();
            $table->json('label');
            $table->string('number');
            $table->timestamps();
        });
    }
};
