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
        Schema::create('dream_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dream_id')->constrained('dreams')->onDelete('cascade');
            $table->enum('notification_type', ['24_hours', '1_hour', '10_minutes', 'exact_time']);
            $table->timestamp('scheduled_for');
            $table->enum('status', ['pending', 'queued', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dream_notifications');
    }
};
