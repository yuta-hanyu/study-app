<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMark extends Model
{
  use HasFactory;
  /**
   * @var array
   */
  protected $fillable = [
    'user_id',
    'book_mark_folders_id',
    'title',
    'link',
    'memo',
    'is_deleted'
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
}
