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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('channels'); // e.g. ["email", "app_push"]

            $table->unsignedBigInteger('created_by'); // employee who created
            $table->foreign('created_by')->references('id')->on('employees');

            $table->unsignedBigInteger('approved_by')->nullable(); // employee who approved
            $table->foreign('approved_by')->references('id')->on('employees')->nullOnDelete();

            $table->boolean('approval_required')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->integer('estimated_reach')->default(0);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'active', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
