<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Rules\CustomPasswordComparisonValidation;
use App\Rules\CustomGuestUserValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
// class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
      'name',
      'email',
      'password',
    ];
    /**
     * 既存バリデーションとの相関チェックバリデーション
     */
    public function passwordComparisonValidate(array $input)
    {
      $rules = [
        'passwordComparison' => new CustomPasswordComparisonValidation()
      ];
      return Validator::make($input, $rules);
    }
    /**
     * ゲストユーザー編集不可チェックバリデーション
     */
    public function guestUserValidate(array $input)
    {
      $rules = [
        'gestUserEmail' => new CustomGuestUserValidation()
      ];
      return Validator::make($input, $rules);
    }
    /**
     * バリデーション
     */
    public function validate(array $input, bool $editFlag = false)
    {
      $rules = [
        'name' => 'required',
        'email'=>['required','email', Rule::unique('users','email')->ignore(Auth::id())->whereNull('deleted_at')],
        'password' => 'required|max:8'
      ];
      return Validator::make($input, $rules);
    }
    /**
     * todoを取得
     */
    public function todos()
    {
      return $this->hasMany('App/Models/Todo', 'users_id');
    }
    /**
     * ブックマークフォルダを取得
     */
    public function bookMarkFolders()
    {
      return $this->hasMany('App/Models/BookMarkFolder', 'users_id');
    }
    /**
     * ブックマークを取得
     */
    public function bookMarks()
    {
      return $this->hasMany('App/Models/BookMark', 'users_id');
    }
    /**
     * SNS認証を取得
     */
    function IdentityProvider()
    {
      return $this->hasMany('App/Models/IdentityProvider', 'users_id');
    }
}
