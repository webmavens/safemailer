<?php

namespace SafeMailer;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SafeMailer
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = config('safemailer.base_url');
    }

    /**
     * Send an email with attachments
     */
    public function sendEmail(string $recipientEmail, string $subject, string $content, string $expirationType = 'never', array $attachments = []): ?array
    {
        Log::info('SafeMailer: Sending email', [
            'recipient' => $recipientEmail,
            'subject' => $subject,
            'has_api_key' => !empty($this->apiKey)
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->post("{$this->baseUrl}/emails", [
                'recipient_email' => $recipientEmail,
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