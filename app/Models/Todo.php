<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    'user_id',
    'title',
    'content',
    'state',
    'book_mark',
    'remainder',
    'is_deleted'
  ];
  /**
   * ユーザーを取得
   */
  public function user()
  {
    return $this->belongsTo('/App/Models/User', 'id');
  }
}
