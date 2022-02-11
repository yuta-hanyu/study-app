<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class LoginController extends Controller
{
  public function login(Request $request)
  {
    Log::info('ログイン認証開始');
    Log::info($request);
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
      return response()->json(['message' => 'Login successful'], 200);
      Log::info('ログイン認証終了');
    }
    return response()->json(['message' => 'User not found'], 422);
    Log::info('ログイン認証失敗');
  }


  public function logout()
  {
    Auth::logout();
    return response()->json(['message' => 'Logged out'], 200);
  }
}
