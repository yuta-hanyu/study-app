<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
// class User extends Authenticatable
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = [
      'name',
      'email',
      'password',
      'is_deleted',
    ];
    /**
     * バリデーション
     */
    public function validate(array $input)
    {
      Log::info($input);
      $rules = [
        'name' => 'required',
        'email' => 'required|string|email|unique:users,email',
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
}
