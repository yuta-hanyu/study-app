<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Todo;
// use Validator;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
  /**
  * 一覧表示
  * @ return object
  */
  public function index() :object
  {
    return Todo::orderBy('updated_at', 'desc')->get();
  }
  /**
  * 新規登録
  * @ return object
  */
  public function store(Request $request)
  {
    $validate = Validator::make($request->all(), [
      'title' => 'required|max:15',
      'state' => 'required',
    ]);

    if ($validate->fails()) {
      // バリデーションエラー
      $message = $validate->errors();
      // エラー判定
      $validateState = false;
      return response(['message' => $message, 'validateState' => $validateState]);
    }
    return Todo::create($request->all());
  }
  /**
  * 削除
  */
  public function destroy(Todo $id)
  {
    return $id->delete();
  }
  /**
  * 更新
  */
  public function update(Request $request, Todo $id)
  {
    $id->update($request->all());
    return $id;
  }
}
