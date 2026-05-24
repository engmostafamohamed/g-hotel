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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->foreignId('guest_id')->constrained('guests');
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->decimal('room_charges', 10, 2);
            $table->decimal('service_charges', 10, 2)->default(0);
            $table->decimal('loyalty_discount', 10, 2)->default(0);
            $table->date('payment_date');
            $table->boolean('late_checkout')->default(false);
            $table->date('expiry_date')->nullable();
            $table->decimal('final_amount', 10, 2);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
