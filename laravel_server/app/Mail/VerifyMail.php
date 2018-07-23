<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    private $token;
    public $user;
    public $config;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->view('emails.verifyUser')
                    ->with(['token' => $this->token,])
                    ->from($this->config->platform_email);
    }
}
