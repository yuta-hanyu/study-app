<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookMark extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * @var array
   */
  protected $fillable = [
    'user_id',
    'book_mark_folders_id',
    'title',
    'link',
    'memo',
    'fixed',
  ];
  /**
   * ユーザーを取得
   */
  public function user()
  {
    return $this->belongsTo('/App/Models/User', 'id');
  }
  /**
   * ブックマークフォルダーを取得
   */
  public function bookMarkFolder()
  {
    return $this->belongsTo('/App/Models/BookMarkFolder.php', 'id');
  }
  /**
   * バリデーション
   */
  public function validate(array $input)
  {
    $rules = [
      'user_id' => 'required|integer',
      'title' => 'required|max:255',
      'link' => 'required|url'
    ];
    // 上部固定の場合はフォルダIDに関するバリデーションを行わない
    if((int)$input['fixed'] !== config('const.BOOK_MARK_FIXED')) {
      $rules = array_merge($rules,array('book_mark_folders_id'=>'required|integer'));
    }
    return Validator::make($input, $rules);
  }
  /**
   * リンクバリデーション
   */
  public function linkValidate(array $input)
  {
    $rules = [
      'link' => 'required|url'
    ];
    return Validator::make($input, $rules);
  }
}
