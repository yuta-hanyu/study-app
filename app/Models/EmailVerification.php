<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class EmailVerification extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    'email',
    'token',
    'status',
    'expiration_datetime',
  ];
  /**
   * 初期データをセット
   */
  protected static function build($email)
  {
    $hours = config('const.TEMPORARY_EXPIRATION_DATETIME'); // 仮登録有効期限
    $emailVerification = new self([
      'email' => $email,
      'token' => Str::random(250),
      'status' => config('const.SEND_MAIL'),
      'expiration_datetime' => Carbon::now()->addHours($hours),
    ]);
    return $emailVerification;
  }
  /**
   * バリデーション
   */
  public function validate($input)
  {
    $rules = [
      'email' => 'required|string|email|unique:users,email',
    ];
    return Validator::make($input->input(), $rules);
  }
  /**
   * 認証対象tokenの抽出
   */
  public static function findByToken($token)
  {
    return self::where('token', '=', $token)->first();
  }

  // /**
  //  * 本登録完了後のステータス更新
  //  */
  // public function register()
  // {
  //   $this->status = config('const.REGISTERED');
  // }
}
