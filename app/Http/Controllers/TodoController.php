<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
  /**
  * 一覧表示
  * @return Http response
  */
  public function index(Request $request)
  {
    Log::info('todo一覧取得開始');
    $todo = new Todo();
    $todos = $todo
                ->where('user_id', '=', $request['userInfo']['id'])
                ->orderBy('updated_at', 'desc')
                ->get();
    // リマインド（reminder）をフロント表示用に分割
    // foreach($result as $todo) {
    //   $todo->reminderDate = substr($todo->reminder, 0, 10);
    //   $todo->reminderTime = substr($todo->reminder, 11, 5);
    //   unset($todo->reminder);
    // };
    Log::info('todo一覧取得終了');
    return response()->json([
      'todos' => $todos
    ]);
  }
  /**
  * 新規登録
  * @return Http response
  */
  public function store(Request $request)
  {
    Log::info($request['userInfo']['id']);
    Log::info($request);
    Log::info('todo新規登録開始');
    // バリデーション
    $todo = new Todo();
    $validate = $todo->validate($request['newTodo']);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("todo登録失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // リマインド日付、時間を合算し保存（空の場合はnullを保存）
    // if(!is_null($request->reminderDate) && !is_null($request->reminderTime)) {
    //   $reminder = $request->reminderDate.' '.$request->reminderTime.':00';
    // } else {
    //   $reminder = null;
    // };
    $input = $request['newTodo'];
    $input = array_merge($input, array('user_id'=>$request['userInfo']['id']));
    // 登録開始
    DB::beginTransaction();
    try{
      $todo->fill($input);
      $todo->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error('todo新規登録失敗');
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('todo新規登録終了');
    return;
  }
  /**
   * 更新
   */
  public function update(Request $request)
  {
    Log::info('todo更新開始');
    // バリデーション
    $validate = Validator::make($request->all(), [
      'title' => 'required|max:15',
      'content' => 'max:255',
      'state' => 'required',
    ]);
    if ($validate->fails()) {
      // バリデーションエラーメッセージ
      $message = $validate->errors();
      // エラー判定
      $validateState = false;
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // リマインド日付、時間を合算し保存（空の場合はnullを保存）
    if(!is_null($request->reminderDate) && !is_null($request->reminderTime)) {
      $reminder = $request->reminderDate.' '.$request->reminderTime.':00';
    } else {
      $reminder = null;
    };
    // 更新開始
    DB::beginTransaction();
    try {
      $todo = new Todo();
      $updateTodo = $todo->where('id', $request->id)->first();
      $updateTodo->title = $request->title;
      $updateTodo->content = $request->content;
      $updateTodo->state = $request->state;
      $updateTodo->book_mark = $request->book_mark;
      $updateTodo->reminder = $reminder;
      $updateTodo->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('todo更新失敗');
      Log::info($e);
      DB::rollback();
    };
    Log::info('todo更新終了');
    return;
  }
  /**
   * リマインダー更新
   */
  public function updateRemaind(Request $request)
  {
    Log::info('todoリマンダー更新開始');
    DB::beginTransaction();
    try {
      $todo = new Todo();
      $updateTodo = $todo->where('user_id', $request->user_id)->get();
      foreach($request->id as $id){
        $updateTodo = $updateTodo->where('id', $id)->first();
        $updateTodo->reminder = null;
        $updateTodo->save();
        DB::commit();
      };
    } catch (\Exception $e) {
      Log::info('todoリマンダー更新失敗');
      Log::info($e);
      DB::rollback();
    };
    Log::info('todoリマンダー更新終了');
    return;
  }
  /**
  * 削除
  */
  public function destroy(Request $request)
  {
    Log::info('todo削除開始');
    $todo = new Todo();
    $todo->where('id', '=', $request->id)->delete();
    Log::info('todo削除終了');
    return;
  }
  /**
  * 全削除
  * @return Http response
  */
  public function allDestroy($user_id)
  {
    Log::info('todo全削除開始');
    $todo = new Todo();
    $todo->where('user_id', '=', $user_id)->delete();
    Log::info('todo全削除終了');
    return;
  }
}
