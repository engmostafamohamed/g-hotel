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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('guest_title', 10)->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('passport_or_id_num', 50)->unique()->nullable();
            $table->enum('passport_or_id_flag', ['passport', 'id'])->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->string('phone_no', 20)->nullable();
            $table->string('country_code')->nullable();
            $table->boolean('is_loyalty_member')->default(false);
            $table->date('member_since')->nullable();
            $table->unsignedTinyInteger('loyalty_tier')->default(1);
            $table->unsignedInteger('total_points')->default(0);
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
