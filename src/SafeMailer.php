<?php

namespace SafeMailer;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SafeMailer
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = config('safemailer.base_url', 'https://api.safemailer.com');
    }

    /**
     * Send an email with attachments
     */
    public function sendEmail(
        string $recipientEmail, 
        string $subject, 
        string $content, 
        string $fromEmail = null,
        string $expirationType = 'never', 
        array $attachments = []
    ): ?array
    {
        Log::info('SafeMailer: Sending email', [
            'recipient' => $recipientEmail,
            'from' => $fromEmail,
            'subject' => $subject,
            'has_api_key' => !empty($this->apiKey)
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->post("{$this->baseUrl}/emails", [
                'recipient_email' => $recipientEmail,
                'from_email' => env('MAIL_FROM_ADDRESS', 'hello@safemailer.com'),
                'subject' => $subject,
                'content' => $content,
                'expiration_type' => $expirationType,
                'attachments' => $attachments
            ]);

            if (!$response->successful()) {
                Log::error('SafeMailer: API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception("API request failed: {$response->status()} - {$response->body()}");
            }

            $result = $response->json();
            Log::info('SafeMailer: Email sent successfully', [
                'response' => $result
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('SafeMailer: Exception while sending email', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 