<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * ログイン
 */
Route::post('/login', 'App\Http\Controllers\LoginController@login');
/**
 * ログアウト
 */
Route::post('/logout', 'App\Http\Controllers\LoginController@logout');
/**
 *仮会員登録
 */
Route::post('/register', 'App\Http\Controllers\RegisterController@emailVerification');
/**
 *トークン認証
 */
Route::post('/register/tokenVerify', 'App\Http\Controllers\RegisterController@tokenVerify');
/**
 *本会員登録
 */
Route::post('/register/newUser', 'App\Http\Controllers\RegisterController@store');


Route::middleware('auth:sanctum')->group(function () {
  /**
   * todo一覧表示
   */
  Route::get('/todos/{user_id}', 'App\Http\Controllers\TodoController@index');
  /**
   * todo新規登録
   */
  Route::post('/todos', 'App\Http\Controllers\TodoController@store');
  /**
   * todo更新
   */
  Route::put('/todo/update', 'App\Http\Controllers\TodoController@update');
  /**
   * todoリマインダー更新
   */
  Route::put('/todo/updateRemaind', 'App\Http\Controllers\TodoController@updateRemaind');
  /**
   * todo削除
   */
  Route::delete('/todo/{id}', 'App\Http\Controllers\TodoController@destroy');
  /**
   * todo全削除
   */
  Route::delete('/todos/{user_id}', 'App\Http\Controllers\TodoController@allDestroy');
  /**
   * ブックマーク一覧表示
   */
  Route::get('/bookMarks/{user_id}', 'App\Http\Controllers\BookMarkController@index');
  /**
   * ブックマークフォルダ登録
   */
  Route::post('/bookMarkFolder', 'App\Http\Controllers\BookMarkController@store');
  /**
   * ブックマークタイトル取得
   */
  Route::post('/bookMark/getTitle', 'App\Http\Controllers\BookMarkController@getTitle');
  /**
   * ブックマーク登録
   */
  Route::post('/bookMark', 'App\Http\Controllers\BookMarkController@bookMarkStore');
  /**
   * ブックマークフォルダ編集
   */
  Route::post('/editBookMarkFolder', 'App\Http\Controllers\BookMarkController@bookMarkFolderEdit');
  /**
   * ブックマークフォルダ削除
   */
  Route::post('/removeBookMarkFolder', 'App\Http\Controllers\BookMarkController@bookMarkFolderRemove');
  /**
   * ブックマーク編集
   */
  Route::post('/editBookMark', 'App\Http\Controllers\BookMarkController@bookMarkEdit');
  /**
   * ブックマーク削除
   */
  Route::post('/removeBookMark', 'App\Http\Controllers\BookMarkController@bookMarkRemove');
  /**
   * ブックマークインポート
   */
  Route::post('/bookMarks/import', 'App\Http\Controllers\BookMarkController@bookMarksImport');
  /**
   * お問い合わせ
   */
  Route::post('/inquiry', 'App\Http\Controllers\InquiryController@store');
  /**
   * ユーザー情報取得
   */
  Route::get('/register/index', 'App\Http\Controllers\RegisterController@index');
  /**
   *会員情報編集
  */
  Route::post('/register/edit_user', 'App\Http\Controllers\RegisterController@update');
});


// Route::prefix('auth')->group(function () {
// });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });
