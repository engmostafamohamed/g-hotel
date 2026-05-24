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
        Schema::create('page_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('static_page_id')->unique()->constrained('static_pages')->cascadeOnDelete();
            $table->string('meta_title', 100);
            $table->string('meta_description', 255);
            $table->string('keywords', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_metadata');
    }
};
