<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class User extends Model
class User extends Authenticatable
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
