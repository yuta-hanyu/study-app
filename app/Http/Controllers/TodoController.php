<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
  /**
  * 一覧表示
  * @return Http response
  */
  public function index($user_id) :object
  {
    Log::info('todo一覧取得開始');
    $todos = new Todo();
    $result = $todos
                ->where('user_id', '=', $user_id)
                ->orderBy('updated_at', 'desc')
                ->get();
    Log::info('todo一覧取得終了');
    return response()->json([
      'result' => $result
    ]);
  }
  /**
  * 新規登録
  * @return Http response
  */
  public function store(Request $request)
  {
    Log::info('todo新規登録開始');
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
    // 登録開始
    DB::beginTransaction();
    try{
      $todo = new Todo();
      $todo->user_id = $request->userId;
      $todo->title = $request->title;
      $todo->content = $request->content;
      $todo->state = $request->state;
      $todo->book_mark = $request->bookMark;
      $todo->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
    Log::info('todo新規登録終了');
    return;
  }
  /**
   * 更新
   */
  public function update(Request $request)
  {
    // $id->update($request->all());
    Log::info($request);
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
    // 更新開始
    DB::beginTransaction();
    try{
      $todo = new Todo();
      $updateTodo = $todo->where('id', $request->id)->first();
      $updateTodo->title = $request->title;
      $updateTodo->content = $request->content;
      $updateTodo->state = $request->state;
      $updateTodo->book_mark = $request->bookMark;
      $updateTodo->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
    Log::info('todo更新終了');
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
