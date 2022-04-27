<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $type;
    protected $content;
    protected $studyAppUrl;

    /**
     * お問い合わせ専用
     *
     * @return void
     */
    public function __construct($name, $type, $content)
    {
        $this->title = sprintf('%sさん、お問い合わせ頂きありがとうございます。', $name);
        $this->type = $type;
        $this->content = $content;
        $this->studyAppUrl = env('APP_URL')."login";
    }

    /**
     * お問い合わせ専用
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('emails.inquiryReplay')
                    ->subject($this->title)
                    ->with([
                        'type' => $this->type,
                        'content' => $this->content,
                        'studyAppUrl' => $this->studyAppUrl,
                      ]);
    }
}
