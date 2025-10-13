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
        Schema::create('dreams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('connection_id')->constrained('connections')->onDelete('cascade');
            $table->string('heading');
            $table->string('title')->nullable();
            $table->text('description');
            $table->string('place');
            $table->boolean('validated_by_partner')->default(false);
            $table->enum('status', [
                'solo',              // Created by one partner only
                'shared',            // Validated by both partners
                'planning',          // Destiny date negotiation in progress
                'scheduled',         // Destiny date confirmed (in Bucket List - future date)
                'cherished',         // Date passed (in Cherished Memories - past date)
                'fulfilled',         // Marked as lived (in Lived in the Dream)
                'deleted'            // Soft deleted
            ])->default('solo');
            $table->timestamp('destiny_date')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('cherished_at')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dreams');
    }
};
