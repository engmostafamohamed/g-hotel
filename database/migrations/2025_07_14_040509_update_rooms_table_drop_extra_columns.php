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
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropColumn('hotel_id');

            $table->dropColumn([
                'name',
                'description',
                'room_image',
                'available_quantity'
            ]);

            $table->string('room_number')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('room_image')->nullable();
            $table->integer('available_quantity')->default(0);
            $table->foreignId('hotel_id')->constrained('hotel_locations')->cascadeOnDelete();

            $table->dropColumn('room_number');
        });
    }
};
