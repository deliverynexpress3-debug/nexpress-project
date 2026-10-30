<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class mail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details, $view)
    {
        $this->details = $details;
        $this->view = $view;
    }
    public function build()
    {
        return $this->subject($this->details['title'])
            ->view($this->view);
    }
}
