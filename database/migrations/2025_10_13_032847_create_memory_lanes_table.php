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
        Schema::create('memory_lanes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('heading'); // Main heading for the memory
            $table->string('title')->nullable(); // Optional title
            $table->text('description'); // Memory description/story
            $table->date('story_date'); // User's manual input for when memory happened
            $table->enum('media_type', ['audio', 'video', 'text', 'image']); // Type of media
            $table->string('media_path')->nullable(); // Path to uploaded media file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memory_lanes');
    }
};
