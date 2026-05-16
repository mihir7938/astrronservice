<?php

namespace App\Services;

class WhatsappService
{
    public function sendMessage($message_type, $to_number, $message){   
        $server_url = env('WHATSAPP_REST_API_URL');
        $url = $server_url.'/send-message';
        $token = env('WHATSAPP_API_TOKEN');
        $from_number = '91'.env('WHATSAPP_MOBILE');
        $data = '{
                    "messageType": "'.$message_type.'",
                    "requestType": "POST",
                    "token": "'.$token.'",
                    "from": "'.$from_number.'",
                    "to": "'.$to_number.'",
                    "text": "'.$message.'"
                }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}
