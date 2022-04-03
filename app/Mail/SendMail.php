<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $type;
    protected $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailType, $name, $type, $content)
    {
      // お問い合わせメールの場合の値をセット
      if($mailType === config('const.Inquiry')) {
        $this->title = sprintf('%sさん、お問い合わせ頂きありがとうございます。', $name);
        $this->type = $type;
        $this->content = $content;
      }
    }

    /**
     * Build the message.
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
                      ]);
    }
}
