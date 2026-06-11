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
        Schema::table('posts', function (Blueprint $table) {
            // Adding the column as a string, nullable since not every post needs a category
            $table->string('training_category')->nullable()->after('content');
            
            // Optional: Adding an index helps the sidebar load faster when you have many posts
            $table->index('training_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('training_category');
        });
    }
};