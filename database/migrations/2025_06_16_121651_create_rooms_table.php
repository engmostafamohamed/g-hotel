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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->json("name");
            $table->string("room_image")->nullable();
            $table->integer("available_quantity")->default(0);
            $table->integer("number_of_adults")->default(0);
            $table->integer("number_of_children")->default(0);
            $table->json("description")->nullable();
            $table->json("bed_configuration")->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained('hotel_locations')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
