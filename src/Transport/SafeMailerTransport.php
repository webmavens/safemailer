<?php

namespace SafeMailer\Transport;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\Envelope;
use SafeMailer\SafeMailer;

class SafeMailerTransport implements TransportInterface 
{
    protected $key;
    protected $safemailer;

    public function __construct($key)
    {
        $this->key = $key;
        $this->safemailer = new SafeMailer($key);
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $email = MessageConverter::toEmail($message);
        
        Log::info('SafeMailerTransport: Starting email send process', [
            'transport_key_exists' => !empty($this->key),
            'envelope' => $envelope ? 'provided' : 'not provided'
        ]);
        
        // Get the recipient email from the To header
        $recipients = $email->getTo();
        $recipientEmail = !empty($recipients) ? $recipients[0]->getAddress() : null;
        
        if (!$recipientEmail) {
            Log::error('SafeMailerTransport: No recipient email found');
            throw new \Exception('No recipient email address specified');
        }
        $fromEmail = null;
        if ($envelope && $envelope->getSender()) {
            $fromEmail = $envelope->getSender()->getAddress();
        } elseif ($email->getFrom()) {
            $fromEmail = $email->getFrom()[0]->getAddress();
        }


        // Get expiration type from message metadata
        $expirationType = $message->getHeaders()->get('X-SafeMailer-Expiration-Type')?->getBodyAsString() ?? 'never';

        // Actually send the email using SafeMailer API
        $result = $this->safemailer->sendEmail(
            recipientEmail: $recipientEmail,
            subject: $email->getSubject(),
            content: $email->getBody()->toString(),
            fromEmail: $fromEmail,
            expirationType: $expirationType
        );
        
        Log::info('SafeMailerTransport: API call result', [
            'success' => !empty($result),
            'recipient' => $recipientEmail
        ]);

        $sentMessage = new SentMessage($message, $envelope ?? Envelope::create($message));
        Log::info('SafeMailerTransport: Email processed and SentMessage created');
        
        return $sentMessage;
    }

    public function __toString(): string
    {
        return 'safemailer';
    }
} 