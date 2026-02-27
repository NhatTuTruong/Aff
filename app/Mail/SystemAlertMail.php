<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;

    public string $message;

    /**
     * @param  string  $title
     * @param  string  $message
     */
    public function __construct(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function build(): self
    {
        return $this
            ->subject($this->title)
            ->view('emails.system-alert');
    }
}

