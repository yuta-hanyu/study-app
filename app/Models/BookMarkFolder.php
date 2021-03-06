<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;


class BookMarkFolder extends Model
{
  use HasFactory;
  use SoftDeletes;
  /**
   * @var array
   */
  protected $fillable = [
    'user_id',
    'title',
    'color',
  ];
  /**
   * ユーザーを取得
   */
  public function user()
  {
    return $this->belongsTo('/App/Models/User', 'id');
  }
  /**
   * ブックマークを取得
   */
  public function bookMarks()
  {
    return $this->hasMany('App/Models/BookMark', 'book_mark_folders_id');
  }
  /**
   * バリデーション
   */
  public function validate(array $input)
  {
    $rules = [
      'user_id' => 'required|integer',
      'title' => 'required|max:255',
      'color' => 'max:15',
    ];
    return Validator::make($input, $rules);
  }
}
