<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token;
    protected $phoneNumberId;
    protected $version;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->version = config('services.whatsapp.version');
        $this->baseUrl = "https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages";
    }
    /**
     * Common HTTP Request
     */
    protected function send(array $payload)
    {
        try {

            $response = Http::withToken($this->token)
                ->acceptJson()
                ->post($this->baseUrl, $payload);

            $result = $response->json();
            Log::info('WhatsApp API Response', [
                'payload' => $payload,
                'status'  => $response->status(),
                'response'=> $result
            ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $result
            ];

        } catch (\Exception $e) {

            Log::error('WhatsApp Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
    }
    /**
     * Send Text Message
     */
    public function sendText($mobile, $message)
    {
        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $mobile,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => $message
            ]
        ];

        return $this->send($payload);
    }
    public function sendTemplate($mobile, $templateName, $parameters = [], $language = 'en')
    {
        $bodyParameters = [];

        foreach ($parameters as $name => $value) {
            $bodyParameters[] = [
                "type" => "text",
                "parameter_name" => $name,
                "text" => (string) $value
            ];
        }

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $mobile,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => [
                    "code" => $language
                ]
            ]
        ];

        // Add body parameters only if provided
        if (!empty($bodyParameters)) {
            $payload['template']['components'] = [
                [
                    "type" => "body",
                    "parameters" => $bodyParameters
                ]
            ];
        }

        return $this->send($payload);
    }
}
