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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // $table->string('hotel_code', 10);
            $table->string('primary_role')->nullable(); // purely informational
            $table->string('name', 100);
            $table->string('cnic', 20)->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone_no', 20);
            $table->string('email', 100);
            $table->string('password');
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
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
        Schema::dropIfExists('employees');
    }
};
