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
        Schema::create('campaign_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->enum('type', ['points_discount', 'percentage_discount', 'fixed_discount', 'free_service']);
            $table->string('value'); // e.g. "15%", "50 EGP", or points like "150"
            $table->decimal('min_booking', 10, 2)->nullable(); // e.g. minimum order amount

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_offers');
    }
};
