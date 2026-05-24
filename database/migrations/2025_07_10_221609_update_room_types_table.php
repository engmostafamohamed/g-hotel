<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->string('room_code')->unique()->after('id');
            $table->json('name')->after('room_code');
            $table->foreignId('category_id')->constrained()->onDelete('cascade')->after('base_price');

            $table->dropColumn(['type', 'hotel_code', 'occupancy']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['room_code', 'name', 'category_id']);

            $table->string('type', 50);
            $table->string('hotel_code', 10);
            $table->unsignedInteger('occupancy');
        });
    }
};
