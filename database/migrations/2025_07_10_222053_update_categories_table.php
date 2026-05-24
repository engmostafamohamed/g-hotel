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
        Schema::table('categories', function (Blueprint $table) {
            $table->json("images")->nullable()->change();
            $table->unsignedTinyInteger('max_adults')->default(0)->after('description');
            $table->unsignedTinyInteger('max_children')->default(0)->after('max_adults');
            $table->boolean('infants_allowed')->default(true)->after('max_children');
            $table->json('policies')->nullable()->after('infants_allowed'); // create policies table later?
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->json("images")->nullable(false)->change();
            $table->dropColumn(['max_adults', 'max_children', 'infants_allowed', 'policies']);
        });
    }
};
