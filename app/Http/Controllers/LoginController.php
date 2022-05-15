<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\IdentityProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
  /**
   * SNS認証ページURLを返却する
   * @return Http response
   */
  public function redirectToProvider($social)
  {
    Log::info('SNS認証認証ページへリダイレクト開始');
    $redirectUrl = Socialite::driver($social)->redirect()->getTargetUrl();
    Log::info('SNS認証認証ページへリダイレクト終了');
    return response()->json(['redirect_url' => $redirectUrl]);
  }
  /**
   * SNS認証後のレコード更新・ログイン処理
   * @return Http response
   */
  public function handleProviderCallback($provider)
  {
    Log::info($provider.'SNS認証開始');
    $userInfo = [];
    $provider_user = Socialite::driver($provider)->user();
    Log::info(print_r($provider_user, true));
    $identity_provider = IdentityProvider::where('provider_id', $provider_user->getId())->first();
    Log::info($identity_provider);

    if($identity_provider) { //ログイン
      Log::info('SNS認証ログイン開始');
      Auth::loginUsingId($identity_provider->user_id);
      // ユーザー情報作成
      $userInfo = [
        'user_id' => Auth::user()->id,
        'name' => Auth::user()->name,
      ];
    } else { //identity_provider新規作成
      Log::info('SNS認証新規登録開始');
      DB::beginTransaction();
      try {
        $user = User::where('email', $provider_user->getEmail())->first();
        if(!$user) { // userテーブルに情報がなければ新規作成
          $user = User::create([
            'name' => ($provider_user->getName()) ? $provider_user->getName() : $provider_user->getNickname(),
            'email' => $provider_user->getEmail(),
          ]);
          $user->save();
        }
        IdentityProvider::create([
          'user_id' => $user->id,
          'provider_name' => $provider,
          'provider_id' => $provider_user->getId(),
        ]);
        DB::commit();
      } catch (\Exception $e) {
        Log::info('todoソート更新失敗');
        Log::info($e);
        DB::rollback();
        return response()->json([], 500);
      };
      Auth::loginUsingId($user->id);
      $userInfo = [
        'user_id' => Auth::user()->id,
        'name' => Auth::user()->name,
      ];
    }
    Log::info($provider.'SNS認証終了');
    return response()->json(['userInfo' => $userInfo]);
  }
  /**
   * ログイン認証
   * @return Http response
   */
  public function login(Request $request)
  {
    Log::info('ログイン認証開始');
    // 認証判定フラグ
    $retultFlag = false;
    // バリデーションMSG
    $validatMessage = '';
    // バリデーション
    $validate = Validator::make($request->all(), [
      'email' => 'required',
      'password' => 'required',
    ]);
    if ($validate->fails()) {
      $validatMessage = $validate->errors();
      $retultFlag = false;
      return response()->json(['validatMessage' => $validatMessage, 'retultFlag' => $retultFlag]);
    };
    // 認証情報
    $credentials = $request->only('email', 'password');
    // 認証開始
    if(Auth::attempt($credentials)) {
      $retultFlag = true;
      // ユーザー情報作成
      $userInfo = [
        'user_id' => Auth::user()->id,
        'name' => Auth::user()->name,
      ];
      Log::info('ログイン認証終了（成功）');
      return response()->json(['retultFlag' => $retultFlag, 'userInfo' => $userInfo]);
    }
    $retultFlag = false;
    Log::info('ログイン認証終了（失敗）');
    return response()->json(['retultFlag' => $retultFlag]);
  }
  /**
   * ログアウト処理
   * @return Http response
   */
  public function logout()
  {
    Log::info('ログアウト開始');
    // ログアウト判定フラグ
    $retultFlag = false;
    // ログアウト処理
    Auth::logout();
    // ログアウト成功チェック
    if(Auth::check()) {
      $retultFlag = false;
      return response()->json(['retultFlag' => $retultFlag]);
    };
    $retultFlag = true;
    Log::info('ログアウト完了');
    return response()->json(['retultFlag' => $retultFlag]);
  }
}
