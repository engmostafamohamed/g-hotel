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
        Schema::create('loyalty_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_program_id')->constrained('loyalty_programs')->cascadeOnDelete();
            $table->unsignedTinyInteger('tier_level');
            $table->text('benefit_description');
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->boolean('free_upgrade_eligible')->default(false);
            $table->boolean('late_checkout_eligible')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_benefits');
    }
};
