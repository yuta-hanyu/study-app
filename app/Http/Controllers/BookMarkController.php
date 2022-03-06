<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookMarkFolder;
use App\Models\BookMark;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BookMarkController extends Controller
{
  /**
  * 一覧表示
  * @return Http response
  */
  public function index($user_id)
  {
    Log::info('BookMark_BookMarkFolder一覧取得開始');
    $bookMarkFolder = new BookMarkFolder();
    $bookMark = new BookMark();
    // userIdに紐づくフォルダ情報取得
    $bookMarkFolders = $bookMarkFolder
                        ->select(
                          'id',
                          'title',
                          'color',
                        )
                        ->where('book_mark_folders.is_deleted', '=', 0)
                        ->where('book_mark_folders.user_id', '=', $user_id)
                        ->orderBy('book_mark_folders.created_at', 'desc')
                        ->get()
                        ->toArray();
    // userIdに紐づくブックマーク情報取得
    $bookMarks = $bookMark
                  ->select(
                    'book_marks.book_mark_folders_id',
                    'book_marks.title as bookMarkTitle',
                    'book_marks.link as bookMarkLink',
                    'book_marks.memo as bookMarkMemo',
                  )
                  ->where('book_marks.user_id', '=', $user_id)
                  ->where('book_marks.is_deleted', '=', 0)
                  ->orderBy('book_marks.created_at', 'desc')
                  ->get()
                  ->toArray();
    Log::info('BookMark_BookMarkFolder一覧取得終了');
    return response()->json([
      'bookMarks' => $bookMarks,
      'bookMarkFolders' => $bookMarkFolders,
    ]);
  }
  /**
  * ブックマークフォルダ登録
  * @return Http response
  */
  public function store(Request $request)
  {
    Log::info('ブックマークフォルダ登録開始');
    $input = $request['newBookMarkFolder'];
    // バリデーション
    $bookMarkFolder = new BookMarkFolder();
    $validate = $bookMarkFolder->validate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("ブックマークフォルダ登録失敗_バリデーションエラー");
      Log::error($message);
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 登録開始
    DB::beginTransaction();
    try{
      $bookMarkFolder = new BookMarkFolder();
      $bookMarkFolder->fill($input);
      $bookMarkFolder->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error('ブックマークフォルダ登録失敗');
      DB::rollBack();
    }
    Log::info('ブックマークフォルダ登録終了');
  }
}
