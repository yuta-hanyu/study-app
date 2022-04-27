<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryMail;
use App\Mail\temporaryRegisterMail;
use App\Mail\TodoReminderMail;
use App\Models\User;
use Illuminate\Http\Request;

class MailController extends Controller
{
  /**
  * お問い合わせ確認メール送信
  * @return void
  */
  public function inquiryReplaySend($input) {
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
          ->send(new InquiryMail($name, $type, $content));
  }
  /**
  * 仮会員登録メール送信
  * @return void
  */
  public function temporaryRegisterSend($emailVerification) {
    $to = $emailVerification->email;
    $bcc = config('const.TO_BCC_MAIL');
    Mail::to($to)
          ->bcc($bcc)
          ->send(new temporaryRegisterMail($emailVerification));
  }
  /**
  * タスク管理_リマインダーメール通知_バッチ処理_メール送信
  * @return void
  */
  public function todoReminderSend($group) {
    $to = $group[0]['email'];
    $bcc = config('const.TO_BCC_MAIL');
    Mail::to($to)
          ->bcc($bcc)
          ->send(new TodoReminderMail($group));
  }
}
