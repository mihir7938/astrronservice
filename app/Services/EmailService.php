<?php

namespace App\Services;

use App\Mail\ProcessEmail;

class EmailService
{
    public function sendEmail($template, $result, $to, $subject, $attachment = '', $attachment_name = '', $reply_to = '', $from = '')
    {
        $details = [
            'template' => $template,
            'result' => $result,
            'to' => $to,
            'subject' => $subject,
            'attachment' => $attachment,
            'attachment_name' => $attachment_name,
            'reply_to' => $reply_to,
            'from' => $from,
        ];
        dispatch(new ProcessEmail($details));
    }
}
