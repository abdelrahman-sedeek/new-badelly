<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $resetCode;
    public $resetLink;
    /**
     * Create a new message instance.
     */
    public function __construct($resetCode)
    {
     $this->resetCode = $resetCode ;
     $this->resetLink = url("reset-password?code={$this->resetCode}") ;
    }
    public function build()
{
    return $this->subject('Reset Your Password')
        ->view('emails.reset_password');
        
}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password',
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
