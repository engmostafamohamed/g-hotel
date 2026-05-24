<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('guests', function (Blueprint $table) {
            // Drop the old loyalty_tier column
            if (Schema::hasColumn('guests', 'loyalty_tier')) {
                $table->dropColumn('loyalty_tier');
            }

            // Add new foreign key column
            $table->unsignedBigInteger('tier_id')->nullable()->after('id');

            $table->foreign('tier_id')
                ->references('id')
                ->on('tiers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
