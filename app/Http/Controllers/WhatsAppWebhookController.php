<?php

namespace App\Http\Controllers;

use App\Models\WhatsappMessage;
use Illuminate\Http\Request;

class WhatsAppWebhookController extends Controller
{
    public function status(Request $request)
    {
        $secret = $request->header('X-WhatsApp-Status-Secret');
        if ($secret !== env('WHATSAPP_STATUS_SECRET')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $request->validate([
            'message_id' => 'required|string',
            'status' => 'required|string',
        ]);
        $message = WhatsappMessage::where('message_id', $request->message_id)->first();
        if (!$message) {
            return response()->json([
                'success' => true,
                'message' => 'Message not found in support'
            ]);
        }
        $message->update([
            'status' => $request->status,
            'error_message' => $request->input('errors.0.title'),
            'payload' => $request->input('payload')
        ]);
        return response()->json([
            'success' => true
        ]);
    }
}