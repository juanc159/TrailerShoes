<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public $view;

    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($view, $subject, $data)
    {
        $this->view = $view;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view($this->view)
            ->subject($this->subject)
            ->with('data', $this->data);
    }
}
