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
        Schema::table('hotel_locations', function (Blueprint $table) {
            $table->string('property_code')->unique()->after('id');
            $table->string('display_name')->after('property_code');
            $table->string('default_language')->after('display_name');
            $table->string('default_currency')->after('default_language');
            $table->string('timezone')->default('Egpyt/Cairo')->after('default_currency');
            $table->boolean('is_active')->default(false)->after('timezone');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_locations', function (Blueprint $table) {
            $table->dropColumn([
                'property_code',
                'display_name',
                'default_language',
                'default_currency',
                'timezone',
                'is_active',
            ]);
        });
    }
};
