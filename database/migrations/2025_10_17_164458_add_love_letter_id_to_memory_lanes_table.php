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
        Schema::table('memory_lanes', function (Blueprint $table) {
            $table->foreignId('love_letter_id')->nullable()->after('user_id')->constrained('love_letters')->onDelete('cascade');
            $table->text('letter_content')->nullable()->after('love_letter_id'); // Store full letter HTML content
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memory_lanes', function (Blueprint $table) {
            $table->dropForeign(['love_letter_id']);
            $table->dropColumn(['love_letter_id', 'letter_content']);
        });
    }
};
