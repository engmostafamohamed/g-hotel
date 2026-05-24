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
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->foreignId('room_type_id')->after('name')->constrained()->onDelete('cascade');

            $table->dropColumn(['number_of_adults', 'number_of_children', 'bed_configuration']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');

            $table->integer("number_of_adults")->default(0);
            $table->integer("number_of_children")->default(0);
            $table->json("bed_configuration")->nullable();
        });
    }
};
