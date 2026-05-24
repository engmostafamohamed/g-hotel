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
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'image_url']);
            $table->string('name')->unique()->after('id');
            $table->text('description')->after('name');
            $table->foreignId('category_id')->nullable()->after('description')->constrained('service_categories')->nullOnDelete();
            $table->string('image_path')->nullable()->after('category_id');
            $table->boolean('sync_with_pms')->default(false)->after('image_path');
            $table->enum('pms_sync_status', ['pending', 'synced', 'failed'])->default('pending')->after('sync_with_pms');
            $table->integer('version')->default(1)->after('pms_sync_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['category_id']);

            $table->dropColumn([
                'name',
                'description',
                'category_id',
                'image_path',
                'sync_with_pms',
                'pms_sync_status',
                'version'
            ]);

            $table->json('name');
            $table->json('description');
            $table->string('image_url');
        });
    }
};
