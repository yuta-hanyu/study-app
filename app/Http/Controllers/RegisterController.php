<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
  /**
  * 仮登録情報を登録
  * @return Http response
  */
  public function emailVerification(Request $request)
  {
    Log::info('仮会員登録開始');
    // バリデーション
    $emailVerification = new EmailVerification();
    $validate = $emailVerification->validate($request);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("仮会員登録失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 登録開始
    DB::beginTransaction();
    try {
      $emailVerification = EmailVerification::build($request->email);
      $emailVerification->saveOrFail();
      DB::commit();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error('仮会員登録失敗');
      DB::rollBack();
      return response()->json([], 500);
    }
    // 仮会員メールを送信
    // $expiration_datetime = $emailVerification;
    $mail = app()->make('App\Http\Controllers\MailController');
    $mail->temporaryRegisterSend($emailVerification);
    Log::info('仮会員登録終了');
  }
  /**
  * 仮登録情報を登録
  * @return Http response
  */
  public function store(Request $request)
  {
    Log::info('仮会員登録終了');
    Log::info('仮会員登録終了');

  }
}
