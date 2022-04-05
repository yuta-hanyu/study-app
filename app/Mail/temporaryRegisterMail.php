<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class temporaryRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $expiration_datetime;
    protected $studyAppUrl;

    /**
     * 仮会員登録専用
     *
     * @return void
     */
    public function __construct($emailVerification)
    {
        $this->title = '登録手続きのご案内';
        $this->expiration_datetime = $emailVerification->expiration_datetime;
        $this->studyAppUrl = env('APP_URL')."verify/".$emailVerification->token;
    }

    /**
     * 仮会員登録専用
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('emails.temporaryRegister')
                    ->subject($this->title)
                    ->with([
                      'expiration_datetime' => $this->expiration_datetime,
                      'studyAppUrl' => $this->studyAppUrl,
                    ]);
    }
}
