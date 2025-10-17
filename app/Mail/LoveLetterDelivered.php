<?php

namespace App\Mail;

use App\Models\LoveLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoveLetterDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $loveLetter;

    /**
     * Create a new message instance.
     */
    public function __construct(LoveLetter $loveLetter)
    {
        $this->loveLetter = $loveLetter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💌 A Love Letter Has Arrived For You!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.love-letter-delivered',
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
