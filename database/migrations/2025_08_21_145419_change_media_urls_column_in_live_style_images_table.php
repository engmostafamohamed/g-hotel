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
        Schema::table('live_style_images', function (Blueprint $table) {
            $table->dropColumn('media_urls');
            $table->json('images_url')->after('caption');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_style_images', function (Blueprint $table) {
            // Drop JSON column
            $table->dropColumn('images_url');
            // Revert to previous state if necessary
            $table->string('media_urls')->after('caption');
        });
    }
};
