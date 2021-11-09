<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Verifymail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $data;
    public $url;

   public function __construct($email,$data)
{
    $this->email = $email;
    $this->data = $data;
    $this->url = "http://127.0.0.1:8000/api/login";

}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject('Thank you for subscribing to our newsletter')
        ->markdown('emails.verifymail');
    }
}
