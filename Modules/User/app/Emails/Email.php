<?php

namespace Modules\User\app\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public $title, $body, $email;

    /**
     * Create a new message instance.
     */
    public function __construct($title, $body, $email)
    {
        $this->title = $title;
        $this->body = $body;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject($this->title)
            ->view('emails.customerClub')
            ->with(['body' => $this->body, 'email' => $this->email]);
    }
}
