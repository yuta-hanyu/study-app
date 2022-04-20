<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
  use HasFactory;
  use SoftDeletes;
  /**
   * @var array
   */
  protected $fillable = [
    'user_id',
    'type',
    'content',
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
