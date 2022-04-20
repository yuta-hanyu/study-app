<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
  use SoftDeletes;
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
  ];
  /**
   * ユーザーを取得
   */
  public function user()
  {
    return $this->belongsTo('/App/Models/User', 'id');
  }
}
