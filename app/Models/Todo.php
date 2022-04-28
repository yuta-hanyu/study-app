<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Rules\CustomReminderValidation;


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
    'reminder',
    'sort_order',
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
      'reminder' => ['bail', 'date_format:Y-m-d', new CustomReminderValidation],
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
  /**
   * リマインド対象データ取得
   */
  public function getRemaindTodos($startToday, $lastToday, $user_id = false)
  {
    $targetTodos = [];
    $targetTodos = $this->select(
                    'users.id as users_id',
                    'users.name as users_name',
                    'users.email as email',
                    'todos.id as todos_id',
                    'title as todos_title',
                    'state as todos_state',
                    'reminder as todos_reminder',
                  )
                  ->join('users', 'todos.user_id', '=', 'users.id')
                  ->where('state', '!=', config('const.FINISH'))
                  ->when($user_id !== false, function ($query) use($user_id) {
                    return $query->where('user_id', '=', $user_id);
                  })
                  ->whereBetween('reminder', [$startToday, $lastToday])
                  ->get();
    return $targetTodos;
  }
}
