<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TodoReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $todos;
    protected $studyAppUrl;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($group)
    {
      $this->title = sprintf('%sさん、StudyAppのタスク管理からのご連絡です。', $group[0]['users_name']);
      $this->todos = $group;
      $this->studyAppUrl = env('APP_URL')."login";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('emails.todoReminder')
                    ->subject($this->title)
                    ->with([
                        'todos' => $this->todos,
                        'title' => $this->title,
                        'studyAppUrl' => $this->studyAppUrl,
                      ]);
    }
}
