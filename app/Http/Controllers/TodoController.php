<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

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
    $bookMarkTodos = $todo
                ->where('user_id', '=', $request['userInfo']['id'])
                ->where('book_mark', '=', config('const.TODO_FIXED'))
                ->when(!$request['finishFlag'], function ($query) {
                  return $query->where('state', '!=', config('const.FINISH'));
                })
                ->orderBy('sort_order', 'asc')
                ->get();
    // リマインド（reminder）をフロント表示用に整形
    foreach($bookMarkTodos as $bookMarkTodo) {
      $bookMarkTodo->reminder = substr($bookMarkTodo->reminder, 0, 10);
    };
    $todos = $todo
                ->where('user_id', '=', $request['userInfo']['id'])
                ->where('book_mark', '!=', config('const.TODO_FIXED'))
                ->when(!$request['finishFlag'], function ($query) {
                  return $query->where('state', '!=', config('const.FINISH'));
                })
                ->orderBy('sort_order', 'asc')
                ->get();
    // リマインド（reminder）をフロント表示用に整形
    foreach($todos as $todo) {
      $todo->reminder = substr($todo->reminder, 0, 10);
    };

    // 通知対象データ取得
    $onRemindTodos = [];
    $startToday = Carbon::today();
    $lastToday = str_replace('00:00:00', '23:59:59', $startToday);
    $onRemindTodos = $todo->getRemaindTodos($startToday, $lastToday, $request['userInfo']['id']);

    Log::info('todo一覧取得終了');
    return response()->json([
      'bookMarkTodos' => $bookMarkTodos,
      'todos' => $todos,
      'onRemindTodos' => $onRemindTodos,
    ]);
  }
  /**
   * 編集
   */
  public function updateSort(Request $request)
  {
    Log::info('todoソート更新開始');
    // 更新開始
    DB::beginTransaction();
    try {
      $todo = new Todo();
      foreach($request['targetTodos'] as $inputTodo) {
        $updateTodo = $todo
                        ->where('id', '=' ,$inputTodo['id'])
                        ->where('user_id', '=' ,$inputTodo['user_id'])
                        ->first();
        $updateTodo->sort_order = $inputTodo['sort_order'];
        $updateTodo->save();
      }
      DB::commit();
    } catch (\Exception $e) {
      Log::info('todoソート更新失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    };
    Log::info('todoソート更新終了');
    return;
  }
  /**
  * 新規登録
  * @return Http response
  */
  public function store(Request $request)
  {
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
   * 編集
   * @return Http response
   */
  public function update(Request $request)
  {
    Log::info('todo更新開始');
    // バリデーション
    $todo = new Todo();
    $validate = $todo->validate($request['editTodo']);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("todo編集失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    $input = $request['editTodo'];
    $input = array_merge($input, array('user_id'=>$request['userInfo']['id']));
    // 更新開始
    DB::beginTransaction();
    try {
      $updateTodo = $todo
                    ->where('id', '=' ,$input['id'])
                    ->where('user_id', '=' ,$input['user_id'])
                    ->first();
      $updateTodo->fill($input);
      $updateTodo->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('todo更新失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
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
    // 更新開始
    DB::beginTransaction();
    try {
      $todo = new Todo();
      $deleteTodo = $todo
                    ->where('id', '=' ,$request['editTodo']['id'])
                    ->where('user_id', '=' ,$request['userInfo']['id'])
                    ->first();
      $deleteTodo->delete();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('todo削除失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('todo削除終了');
    return;
  }
  /**
  * 全削除
  * @return Http response
  */
  public function allDestroy(Request $request)
  {
    Log::info('todo全削除開始');
    // 更新開始
    DB::beginTransaction();
    try {
      $todo = new Todo();
      $deleteTodos = $todo
                    ->where('user_id', '=' ,$request['userInfo']['id'])
                    ->delete();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('todo全削除失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('todo全削除終了');
    return;
  }
}
