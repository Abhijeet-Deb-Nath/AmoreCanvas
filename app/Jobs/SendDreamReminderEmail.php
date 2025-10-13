<?php

namespace App\Jobs;

use App\Models\Dream;
use App\Models\DreamNotification;
use App\Mail\DreamReminderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDreamReminderEmail implements ShouldQueue
{
    use Queueable;

    public $dream;
    public $notificationType;
    public $notificationId;

    /**
     * Create a new job instance.
     */
    public function __construct(Dream $dream, string $notificationType, int $notificationId)
    {
        $this->dream = $dream;
        $this->notificationType = $notificationType;
        $this->notificationId = $notificationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get the dream notification record
            $notification = DreamNotification::find($this->notificationId);
            
            if (!$notification) {
                Log::error("Dream notification not found: {$this->notificationId}");
                return;
            }

            // Check if dream is still scheduled
            if ($this->dream->status !== 'scheduled') {
                Log::info("Dream {$this->dream->id} is no longer scheduled. Skipping notification.");
                $notification->markAsFailed('Dream status changed');
                return;
            }

            // Get both partners
            $connection = $this->dream->connection;
            $sender = $connection->sender;
            $receiver = $connection->receiver;

            // Send email to both partners
            Mail::to($sender->email)->send(new DreamReminderMail($this->dream, $this->notificationType));
            Mail::to($receiver->email)->send(new DreamReminderMail($this->dream, $this->notificationType));

            // Mark notification as sent
            $notification->markAsSent();

            // If this is the exact time notification, move dream to cherished
            if ($this->notificationType === 'exact_time') {
                $this->moveDreamToCherished();
            }

            Log::info("Dream reminder sent for dream {$this->dream->id}, type: {$this->notificationType}");
        } catch (\Exception $e) {
            Log::error("Failed to send dream reminder: " . $e->getMessage());
            
            $notification = DreamNotification::find($this->notificationId);
            if ($notification) {
                $notification->markAsFailed($e->getMessage());
            }
            
            // Even if email fails, still move to cherished if it's exact time
            if ($this->notificationType === 'exact_time') {
                try {
                    $this->moveDreamToCherished();
                } catch (\Exception $statusException) {
                    Log::error("Failed to update dream status: " . $statusException->getMessage());
                }
            }
            
            throw $e; // Re-throw to trigger job retry
        }
    }

    /**
     * Move dream to cherished memories
     */
    private function moveDreamToCherished(): void
    {
        // Reload dream to get fresh data
        $dream = Dream::find($this->dream->id);
        
        if (!$dream) {
            Log::error("Dream {$this->dream->id} not found for status update");
            return;
        }

        // Only update if still scheduled (prevent duplicate updates on retry)
        if ($dream->status === 'scheduled') {
            $dream->update([
                'status' => 'cherished',
                'cherished_at' => now(),
            ]);
            
            Log::info("Dream {$dream->id} moved to cherished memories");
        } else {
            Log::info("Dream {$dream->id} already in status: {$dream->status}, skipping update");
        }
    }
}
