<?php

namespace App\Http\Controllers;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class InquiryController extends Controller
{
  /**
  * お問い合わせ内容登録・メール送信
  * @return Http response
  */
  public function store(Request $request)
  {
    Log::info('お問い合わせ登録開始');
    $input = $request['inquiry'];
    $user_id = Auth::id();
    // user_idをマージ
    $input = array_merge($input, array('user_id'=>$user_id));
    // バリデーション
    $Inquiry = new Inquiry();
    $validate = $Inquiry->validate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("お問い合わせ登録失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 登録開始
    DB::beginTransaction();
    try{
      $Inquiry->fill($input);
      $Inquiry->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error('お問い合わせ登録失敗');
      DB::rollBack();
      return response()->json([], 500);
    }
    // お問い合わせ受付メールを送信
    $mail = app()->make('App\Http\Controllers\MailController');
    $mail->inquiryReplaySend($input);

    Log::info('お問い合わせ登録終了');
  }
}
