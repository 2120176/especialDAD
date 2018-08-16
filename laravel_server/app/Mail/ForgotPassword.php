<?php

namespace App\Mail;

use App\Token;
use Illuminate\Http\Request;
//use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    public $user;
    public $config;


    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->config = DB::table('config')->first();

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //$url = $this->request->input('url');

        return $this->from($this->config->platform_email)
            ->view('emails.forgot-password')
            ->with(['token' => $this->token,]);
    }
}
