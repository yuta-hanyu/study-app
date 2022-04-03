<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Http\Request;

class MailController extends Controller
{
  /**
  * お問い合わせ確認メール送信
  * @return void
  */
  public function inquiryReplaySend($input) {
    $mailType = config('const.Inquiry');
    $user = new User();
    $InquiryUser = $user->select('name', 'email')
                            ->where('id', '=', $input['user_id'])
                            ->first();
    $name = $InquiryUser['name'];
    $type = $input['type'];
    $content = $input['content'];
    $to = $InquiryUser['email'];
    $bcc = config('const.TO_BCC_MAIL');
    Mail::to($to)
          ->bcc($bcc)
          ->send(new SendMail($mailType, $name, $type, $content));
  }
}
