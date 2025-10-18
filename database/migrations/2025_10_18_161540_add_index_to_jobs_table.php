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
        Schema::table('jobs', function (Blueprint $table) {
            //adding composite index to faster the queries with available_at and reserved_at columns
            $table->index(['available_at', 'reserved_at'], 'jobs_available_reserved_index');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Drop the index when rolling back
            $table->dropIndex('jobs_available_reserved_index');
        });
    }
};
