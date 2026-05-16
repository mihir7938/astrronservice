<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->data['attachment']) {
            if ($this->data['attachment_name']) {
                return $this->view($this->data['template'])
                        ->with('result', $this->data['result'])
                        ->attach($this->data['attachment'],[
                            'as' => $this->data['attachment_name'],
                        ]);
            } else {
                return $this->view($this->data['template'])
                        ->with('result', $this->data['result'])
                        ->attach($this->data['attachment']);
            }
        }
        return $this->view($this->data['template'])->subject($this->data['subject'])->with('result', $this->data['result']);
    }
}
