<?php

namespace App\Mail;

use App\Models\Dream;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DreamReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dream;
    public $notificationType;
    public $timeUntilDream;

    /**
     * Create a new message instance.
     */
    public function __construct(Dream $dream, string $notificationType)
    {
        $this->dream = $dream;
        $this->notificationType = $notificationType;
        
        // Calculate time until dream
        $this->timeUntilDream = match($notificationType) {
            '24_hours' => '24 hours',
            '1_hour' => '1 hour',
            '10_minutes' => '10 minutes',
            'exact_time' => 'now',
            default => '',
        };
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->notificationType) {
            '24_hours' => '🌟 Your Dream Awaits Tomorrow',
            '1_hour' => '💫 Your Dream is Just 1 Hour Away',
            '10_minutes' => '✨ 10 Minutes Until Your Dream',
            'exact_time' => '💖 It\'s Time to Live Your Dream!',
            default => 'Dream Reminder',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dream-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
