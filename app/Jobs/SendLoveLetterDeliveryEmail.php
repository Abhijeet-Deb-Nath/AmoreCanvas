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
            // Check if letter is still pending delivery
            if ($this->loveLetter->isDelivered()) {
                Log::info("Love letter {$this->loveLetter->id} is already delivered. Skipping notification.");
                return;
            }

            // Get the receiver
            $receiver = $this->loveLetter->receiver;

            // Send email notification to receiver
            Mail::to($receiver->email)->send(new LoveLetterDelivered($this->loveLetter));

            // Mark letter as delivered
            $this->loveLetter->markAsDelivered();

            Log::info("Love letter {$this->loveLetter->id} delivered successfully to {$receiver->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send love letter delivery email: " . $e->getMessage());
            throw $e; // Re-throw to trigger job retry
        }
    }
}
