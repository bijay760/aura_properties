<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $email;
    private $login_at;
    /**
     * Create a new message instance.
     */
    public function __construct($mailable)
    {
        $this->name = $mailable['name'];
        $this->email = $mailable['email'];
        $this->login_at = $mailable['login_at'];

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Login Alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.login_alert',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'login_at' => $this->login_at,
            ],
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
