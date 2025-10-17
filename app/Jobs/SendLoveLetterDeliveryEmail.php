<?php

namespace App\Jobs;

use App\Models\LoveLetter;
use App\Mail\LoveLetterDelivered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLoveLetterDeliveryEmail implements ShouldQueue
{
    use Queueable;

    public $loveLetter;

    /**
     * Create a new job instance.
     */
    public function __construct(LoveLetter $loveLetter)
    {
        $this->loveLetter = $loveLetter;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Refresh the model to get the latest data
            $this->loveLetter->refresh();
            
            // Check if letter is still pending delivery
            if ($this->loveLetter->isDelivered()) {
                Log::info("Love letter {$this->loveLetter->id} is already delivered. Skipping notification.");
                return;
            }

            // CRITICAL: Verify the scheduled delivery time has actually arrived
            // This prevents early delivery even if job is processed before scheduled time
            $scheduledTime = $this->loveLetter->scheduled_delivery_at;
            $currentTime = now();
            
            if ($currentTime->lt($scheduledTime)) {
                // Current time is before scheduled time - re-queue the job
                $remainingSeconds = $currentTime->diffInSeconds($scheduledTime);
                
                Log::warning("Love letter {$this->loveLetter->id} job ran too early. " .
                            "Scheduled: {$scheduledTime}, Current: {$currentTime}. " .
                            "Re-queuing with {$remainingSeconds} seconds delay.");
                
                // Re-dispatch with the remaining delay
                self::dispatch($this->loveLetter)->delay($remainingSeconds);
                return;
            }

            // Get the receiver
            $receiver = $this->loveLetter->receiver;

            // Send email notification to receiver
            Mail::to($receiver->email)->send(new LoveLetterDelivered($this->loveLetter));

            // Mark letter as delivered
            $this->loveLetter->markAsDelivered();

            Log::info("Love letter {$this->loveLetter->id} delivered successfully to {$receiver->email} at {$currentTime}");
        } catch (\Exception $e) {
            Log::error("Failed to send love letter delivery email: " . $e->getMessage());
            throw $e; // Re-throw to trigger job retry
        }
    }
}
