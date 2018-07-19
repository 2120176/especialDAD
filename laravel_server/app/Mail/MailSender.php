<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    public $sendThisView;
    public $data;
    public $config;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $data)
    {
        $this->sendThisView = $view;
        $this->data = $data;


        $this->config = DB::table('config')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->config->platform_email)
                    ->view($this->sendThisView)->with('data', $this->data);
    }
}
