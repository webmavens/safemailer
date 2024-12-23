<?php

namespace SafeMailer\Laravel\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecureEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $content,
        protected string $expirationType = 'never'
    ) {}

    public function build()
    {
        return $this->view('safemailer::emails.secure')
                    ->text('safemailer::emails.secure_plain')
                    ->with([
                        'content' => $this->content
                    ])
                    ->withSymfonyMessage(function($message) {
                        $message->getHeaders()->addTextHeader('X-SafeMailer-Expiration-Type', $this->expirationType);
                    });
    }
} 