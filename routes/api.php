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
* todo一覧表示
*/
Route::get('/todos/{user_id}', 'App\Http\Controllers\TodoController@index');
/**
* todo新規登録
*/
Route::post('/todos', 'App\Http\Controllers\TodoController@store');
/**
 * todo編集
 */
Route::put('/todo/update', 'App\Http\Controllers\TodoController@update');
/**
* todo削除
*/
Route::delete('/todo/{id}', 'App\Http\Controllers\TodoController@destroy');
/**
* todo全削除
*/
Route::delete('/todos/{user_id}', 'App\Http\Controllers\TodoController@allDestroy');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });
