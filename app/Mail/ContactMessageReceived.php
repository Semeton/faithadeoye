<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Message $message) {}

    public function envelope(): Envelope
    {
        $subject = $this->message->subject
            ? 'New message: '.$this->message->subject
            : 'New message from '.$this->message->name;

        return new Envelope(
            replyTo: [$this->message->email],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-message-received',
        );
    }
}
