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
        //
        // Schema::table('restaurants', function (Blueprint $table) {
        //     $table->boolean('in_dining')->default(false)->after('image_url');
        //     $table->boolean('room_service')->default(false)->after('in_dining');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        // Schema::table('restaurants', function (Blueprint $table) {
        //     $table->dropColumn('in_dining');
        //     $table->dropColumn('room_service');

        // });
    }
};
