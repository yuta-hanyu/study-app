<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
   * バリデーション
   */
  public function validate(array $input)
  {
    $rules = [
      'title' => 'required|max:15',
      'content' => 'max:255',
      'state' => 'required',
    ];
    return Validator::make($input, $rules);
  }
  /**
   * ユーザーを取得
   */
  public function user()
  {
    return $this->belongsTo('/App/Models/User', 'id');
  }
}
