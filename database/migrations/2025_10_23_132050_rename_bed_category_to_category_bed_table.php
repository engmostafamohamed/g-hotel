<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('bed_category', 'category_bed');
    }

    public function down(): void
    {
        Schema::rename('category_bed', 'bed_category');
    }
};
