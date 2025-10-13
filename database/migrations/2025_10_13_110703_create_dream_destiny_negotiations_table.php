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
        Schema::create('dream_destiny_negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dream_id')->constrained('dreams')->onDelete('cascade');
            $table->foreignId('proposed_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('proposed_date');
            $table->text('message')->nullable();
            $table->enum('status', [
                'pending',           // Awaiting partner's response
                'accepted',          // Partner accepted the date
                'rejected',          // Partner rejected the date
                'edited',            // Partner suggested different date
                'rescheduled',       // Rescheduling an existing scheduled dream
                'remove_requested',  // Request to remove from bucket list
                'remove_confirmed',  // Both agreed to remove from bucket list
                'missed'             // Marked as missed the schedule
            ])->default('pending');
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dream_destiny_negotiations');
    }
};
