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
        Schema::create('menu_imports', function (Blueprint $table) {
            $table->id();
            $table->uuid('import_id')->unique();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_location_id')->constrained()->onDelete('cascade');
            $table->string('menu_type');
            $table->string('csv_file_path');
            $table->unsignedInteger('new_items')->default(0);
            $table->unsignedInteger('updated_items')->default(0);
            $table->json('errors')->nullable();
            $table->string('report_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_imports');
    }
};
