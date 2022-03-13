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
  /**
  * ブックマークフォルダー編集
  * @return Http response
  */
  public function bookMarkFolderEdit(Request $request)
  {
    Log::info('ブックマークフォルダ編集開始');
    $input = $request['editFolder'];
    // user_idをマージ
    $input = array_merge($input,array('user_id'=>$request['user_id']));
    // バリデーション
    $bookMarkFolder = new BookMarkFolder();
    $validate = $bookMarkFolder->validate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("ブックマークフォルダ編集失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 更新開始
    DB::beginTransaction();
    try {
      $bookMarkFolder = new BookMarkFolder();
      $updateBookMarkFolder = $bookMarkFolder
                              ->where('id', '=' ,$input['id'])
                              ->where('user_id', '=' ,$input['user_id'])
                              ->first();
      $updateBookMarkFolder->fill($input);
      $updateBookMarkFolder->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('ブックマークフォルダ編集失敗');
      Log::info($e);
      DB::rollback();
    }
    Log::info('ブックマークフォルダ編集終了');
    return;
  }
  /**
  * ブックマーク登録
  * @return Http response
  */
  public function bookMarkStore(Request $request)
  {
    Log::info('ブックマーク登録開始');
    $input = $request['newBookMark'];
    // バリデーション
    $bookMark = new BookMark();
    $validate = $bookMark->validate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("ブックマーク登録失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 登録開始
    DB::beginTransaction();
    try{
      $bookMark = new BookMark();
      $bookMark->fill($input);
      $bookMark->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      Log::error('ブックマーク登録失敗');
      DB::rollBack();
    }
    Log::info('ブックマーク登録終了');
  }
  /**
  * ブックマーク_タイトル取得
  * @return Http response
  */
  public function getTitle(Request $request)
  {
    Log::info('ブックマークタイトル取得開始');
    $input = $request['newBookMark'];
    // バリデーション
    $bookMark = new BookMark();
    $validate = $bookMark->linkValidate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("ブックマークタイトル取得失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // ページタイトル取得
    $result = ''; // 結果初期化
    $url = $input['link'];
    $html = mb_convert_encoding(file_get_contents($url), "utf-8", "auto"); //文字化け防止した上でページ読み込み
    preg_match("/<title>(.*?)<\/title>/i", $html, $title); //タイトルタグ内容取得
    if(!$title) { //タイトルが取得できない場合はURLを返す
      $result = $url;
      return response()->json($result);
    }
    $result = $title[1]; //配列１つ目がタイトル
    Log::info('ブックマークタイトル取得終了');
    return response()->json($result);
  }
}
