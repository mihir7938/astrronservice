<?php

namespace App\Jobs;

use App\Models\WhatsappMessage;
use App\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $messageId;
    public $tries = 3;
    public $timeout = 120;

    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    public function handle(WhatsappService $whatsapp)
    {
        $msg = null;
        try {
            Log::info('Support WhatsApp Queue Worker Started', [
                'message_id' => $this->messageId
            ]);
            $msg = WhatsappMessage::find($this->messageId);
            if (!$msg) {
                return;
            }
            if (in_array($msg->status, ['sent', 'delivered', 'read'])) {
                return;
            }
            $msg->update([
                'status' => 'processing'
            ]);
            if ($msg->type == 'text') {
                $response = $whatsapp->sendText(
                    $msg->to_number,
                    $msg->message
                );
            } else {
                $response = $whatsapp->sendTemplate(
                    $msg->to_number,
                    $msg->template_name,
                    $msg->parameters ?? []
                );
            }
            if ($response['success']) {
                $result = $response['data'];
                $msg->update([
                    'message_id'=>$result['messages'][0]['id'] ?? null,
                    'status'=>'sent',
                    'payload'=>$result
                ]);
            } else {
                $msg->update([
                    'status'=>'failed',
                    'error_message' => $response['data']['error']['message']
                        ?? $response['message']
                        ?? 'Unknown Error',
                    'payload'=>$response
                ]);
            }
        } catch (\Throwable $e) {
            if ($msg) {
                $msg->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
            Log::error('WhatsApp Queue Exception.', [
                'message_id' => $this->messageId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }
}