<?php

namespace App\Services;

use App\Mail\MailReceived;
use Illuminate\Support\Facades\Mail;

class MailService
{
    private $email = '';

    private $cc = [];

    private $view = '';

    private $subject = '';

    public function setEmailTo($email)
    {
        $this->email = $email;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function sendMessage($data = [])
    {
        if (env('SEND_MAIL')) {
            Mail::to($this->email)->cc($this->cc)->send(new MailReceived($this->view, $this->subject, $data));
        }
    }

    public function sendMessageArchive($data = [], $pdfPath = '')
    {

        $mail = new MailReceived($this->view, $this->subject, $data);
        $mail->attach($pdfPath);

        Mail::to($this->email)->cc($this->cc)->send($mail);
    }
}
