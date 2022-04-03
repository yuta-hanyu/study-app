<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class Inquiry extends Model
{
  use HasFactory;
  /**
   * @var array
   */
  protected $fillable = [
    'user_id',
    'type',
    'content',
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
   * バリデーション
   */
  public function validate(array $input)
  {
    $rules = [
      'user_id' => 'required|integer',
      'type' => 'required|string',
      'content' => 'required|max:1000'
    ];
    return Validator::make($input, $rules);
  }
}
