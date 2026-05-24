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
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name', 50);
            $table->text('description')->nullable();
            $table->decimal('points_per_dollar', 5, 2);
            $table->string('tier1_name', 20)->default('Silver');
            $table->unsignedInteger('tier1_threshold');
            $table->string('tier2_name', 20)->default('Gold');
            $table->unsignedInteger('tier2_threshold');
            $table->string('tier3_name', 20)->default('Platinum');
            $table->unsignedInteger('tier3_threshold');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_programs');
    }
};
