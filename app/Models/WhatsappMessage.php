<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    protected $fillable = [
        'message_id',
        'wa_id',
        'from_number',
        'to_number',
        'direction',
        'type',
        'message',
        'template_name',
        'parameters',
        'media_id',
        'mime_type',
        'file_name',
        'status',
        'error_message',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array',
        'parameters' => 'array',
    ];
}