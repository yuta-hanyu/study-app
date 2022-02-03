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

Route::post('/todos', 'App\Http\Controllers\TodoController@store');
Route::get('/todos', 'App\Http\Controllers\TodoController@index');
Route::delete('/todos/{id}', 'App\Http\Controllers\TodoController@destroy');
Route::put('/todos/{id}', 'App\Http\Controllers\TodoController@update');


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });
