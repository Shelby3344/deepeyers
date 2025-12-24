<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLoginAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $ipAddress,
        public string $userAgent,
        public string $location
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Novo login detectado - DeepEyes',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-login-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
