<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
  /**
  * ユーザー情報取得
  * @return Http response
  */
  public function index(Request $request)
  {
    Log::info('ユーザー情報取得開始');
    $id = Auth::id();
    $user = new User;
    $userInfo = $user
                  ->select(
                    'id',
                    'name',
                    'email'
                  )
                  ->where('id', '=', $id)->first();
    Log::info('ユーザー情報取得終了');
    return response()->json(['userInfo' => $userInfo]);
  }
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
      Log::warning("仮会員登録失敗_バリデーションエラー");
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
    $mail = app()->make('App\Http\Controllers\MailController');
    $mail->temporaryRegisterSend($emailVerification);
    Log::info('仮会員登録終了');
  }
  /**
  * トークン認証
  * @return Http response
  */
  public function tokenVerify(Request $request)
  {
    Log::info('トークン認証開始');
    $resultFlag = false; // 認証判定結果初期化
    $message = ''; // 認証失敗MSG初期化
    $emailVerification = EmailVerification::findByToken($request['token']);
    // 未認証のトークンの存在チェック
    if (empty($emailVerification)
        || $emailVerification->status !== config('const.SEND_MAIL')
        || Carbon::now() > $emailVerification->expiration_datetime) {
      $resultFlag = false;
      $message = "メールアドレスの認証に失敗しました。【email】". $emailVerification->email;
      Log::warning("仮会員登録トークン認証失敗_認証対象なし");
      return response()->json(['resultFlag' => $resultFlag, 'message' => $message]);
    }
    // 仮登録のステータス更新開始
    DB::beginTransaction();
    try {
        $emailVerification->status = config('const.MAIL_VERIFYED'); // ステータスをメール認証済みに変更する
        $emailVerification->update(); // DB更新
        DB::commit();
        return response()->json(['email' => $emailVerification->email]);
      } catch (\Exception $e) {
        Log::error("仮会員登録トークン認証失敗");
        DB::rollBack();
        return response()->json([], 500);
      }
    Log::info('トークン認証終了');
  }
  /**
  * 本会員登録
  * @return Http response
  */
  public function store(Request $request)
  {
    Log::info('本会員登録開始');
    $input = $request['newUserInfo'];
    // バリデーション
    $user = new User();
    $validate = $user->validate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::warning("本会員登録失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    $input['password'] = Hash::make($input['password']);
    $emailVerification = EmailVerification::findByToken($request['token']);
    DB::beginTransaction();
    try{
      // 仮会員登録情報更新
      $emailVerification->status = config('const.REGISTERED'); // ステータスを本会員登録完了に変更する
      $emailVerification->update(); // DB更新
      // 本登録情報保存
      $user->fill($input);
      $user->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error('本会員登録失敗');
      DB::rollBack();
      return response()->json([], 500);
    }
    Log::info('本会員登録終了');
  }
  /**
  * 会員情報編集
  * @return Http response
  */
  public function update(Request $request)
  {
    Log::info('会員情報編集開始');
    $userInfo = User::find(Auth::id());
    $user = new User();
    // ゲストユーザー編集不可チェック
    $checkGuest = [];
    $checkGuest['gestUserEmail'] = $userInfo->email;
    $validate = $user->guestUserValidate($checkGuest);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::warning("会員情報編集失敗_ゲストユーザー編集不可チェックバリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 既存パスワードとの相関チェック
    $checkPassword = [];
    $checkPassword = [
      'passwordComparison' => [
        'hashPassword' => $userInfo->password,
        'inputPassword' => $request['oldPassword'],
      ]
    ];
    $validate = $user->passwordComparisonValidate($checkPassword);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::warning("会員情報編集失敗_既存パスワードとの相関チェックバリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 会員情報更新バリデーション
    $input = [];
    $input = $request['editUserInfo'];
    $input['password'] = $request['newPassword'];
    $editFlag = true;
    $validate = $user->validate($input, $editFlag);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::warning("会員情報編集失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 更新開始
    DB::beginTransaction();
    try {
      $input['password'] = Hash::make($input['password']);
      $userInfo->fill($input);
      $userInfo->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('会員情報編集失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('会員情報編集終了');
    return;
  }

}
