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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('value', 5, 2)->default(0);
            $table->unsignedInteger('total_inventory');
            $table->unsignedInteger('per_guest_inventory');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('redemption_code')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
