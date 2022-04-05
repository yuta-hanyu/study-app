<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class LoginController extends Controller
{
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
      // 論理削除は認証失敗
      if(Auth::user()->is_deleted === 1) {
        $retultFlag = false;
        Log::info('ログイン認証失敗');
        return response()->json(['retultFlag' => $retultFlag]);
      }
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
