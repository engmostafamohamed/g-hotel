<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('service_reservations');

        Schema::create('service_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            $table->date('date')->nullable(); // Required only for schedulable services

            // Reference the chosen slot instead of storing from/to
            $table->foreignId('service_time_slot_id')->nullable()->constrained('service_time_slots')->nullOnDelete();

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('notes')->nullable();

            // Audit columns
            $table->foreignId('confirmed_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index for filtering and availability
            $table->index(['service_id', 'date', 'service_time_slot_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_reservations');
    }
};