<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookMarkFolder;
use App\Models\BookMark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

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
                    'id',
                    'book_marks.book_mark_folders_id',
                    'book_marks.title',
                    'book_marks.link',
                    'book_marks.memo',
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
      return response()->json([], 500);
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
      return response()->json([], 500);
    }
    Log::info('ブックマークフォルダ編集終了');
    return;
  }
  /**
  * ブックマークフォルダー削除
  * @return Http response
  */
  public function bookMarkFolderRemove(Request $request)
  {
    Log::info('ブックマークフォルダ削除開始');
    $input = $request['editFolder'];
    // user_idをマージ
    $input = array_merge($input,array('user_id'=>$request['user_id']));
    // 更新開始
    DB::beginTransaction();
    try {
      $bookMarkFolder = new BookMarkFolder();
      $updateBookMarkFolder = $bookMarkFolder
                              ->where('id', '=' ,$input['id'])
                              ->where('user_id', '=' ,$input['user_id'])
                              ->first();
      $updateBookMarkFolder->delete();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('ブックマークフォルダ削除失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('ブックマークフォルダ削除終了');
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
      return response()->json([], 500);
    }
    Log::info('ブックマーク登録終了');
  }
  /**
  * ブックマーク編集
  * @return Http response
  */
  public function bookMarkEdit(Request $request)
  {
    Log::info('ブックマーク編集開始');
    $input = $request['editBookMark'];
    // ログインユーザーID取得
    $user_id = Auth::id();
    // user_idをマージ
    $input = array_merge($input,array('user_id'=>$user_id));
    // バリデーション
    $bookMark= new BookMark();
    $validate = $bookMark->validate($input);
    if($validate->fails()) {
      $message = $validate->errors();
      $validateState = false;
      Log::error("ブックマーク編集失敗_バリデーションエラー");
      return response()->json(['message' => $message, 'validateState' => $validateState]);
    }
    // 更新開始
    DB::beginTransaction();
    try {
      $bookMark= new BookMark();
      $updateBookMark= $bookMark
                        ->where('id', '=' ,$input['id'])
                        ->where('user_id', '=' ,$input['user_id'])
                        ->first();
      $updateBookMark->fill($input);
      $updateBookMark->save();
      DB::commit();
    } catch (\Exception $e) {
      Log::info('ブックマーク編集失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('ブックマーク編集終了');
    return;
  }
  /**
  * ブックマーク削除
  * @return Http response
  */
  public function bookMarkRemove(Request $request)
  {
    Log::info('ブックマーク削除開始');
    $input = $request['editBookMark'];
    // user_idをマージ
    $input = array_merge($input,array('user_id'=>$request['user_id']));
    // 更新開始
    DB::beginTransaction();
    try {
      $bookMark= new BookMark();
      $removeBookMark = $bookMark
                        ->where('id', '=' ,$input['id'])
                        ->where('user_id', '=' ,$input['user_id'])
                        ->first();
      $removeBookMark->delete($input);
      DB::commit();
    } catch (\Exception $e) {
      Log::info('ブックマーク削除失敗');
      Log::info($e);
      DB::rollback();
      return response()->json([], 500);
    }
    Log::info('ブックマーク削除終了');
    return;
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
  /**
  * ブックマーク_インポート
  * @return Http response
  */
  public function bookMarksImport(Request $request)
  {
    Log::info('ブックマークインポート登録開始');
    // ログインユーザーID取得
    $user_id = Auth::id();
    // ブックマークフォルダID初期化
    $bookMarkFoldersId = null;
    // ファイル情報取得
    $fileName = $request['html'];
    // ファイル読み込み開始
    $file = fopen($fileName, "r");
    // htmlファイルの最終行読込完了するまでループ
    while (!feof($file)) {
      // 読み込み情報取得
      $str = fgets($file);
      preg_match("/<h3(?:[^>]*?)>(.*?)<\/h3>/i", $str, $h3); // ブックマークフォルダ名取得(h3タグに格納されている)
      preg_match("/<a href(?:[^>]*?)>(.*?)<\/a>/i", $str, $title); // ブックマークタイトル取得(aタグに格納されている)
      preg_match('/<a href="(.*?)"/i', $str, $link); // ブックマークリンクURL取得(aタグに格納されている)
      // 読み込み行がh3タグ（＝ブックマークフォルダ）の場合、ブックマークフォルダ登録開始
      if($h3){
        $input = [
          'user_id' => $user_id,
          'title' => $h3[1]
        ];
        $bookMarkFolder = new BookMarkFolder();
        $validate = $bookMarkFolder->validate($input);
        if($validate->fails()) {
          $message = $validate->errors();
          $validateState = false;
          Log::error("ブックマークフォルダインポート失敗_バリデーションエラー");
          return response()->json(['message' => $message, 'validateState' => $validateState]);
        }
        // 登録開始
        DB::beginTransaction();
        try{
          $bookMarkFolder->fill($input);
          $bookMarkFolder->save();
          DB::commit();
        } catch (\Exception $e) {
          Log::error($e->getMessage());
          Log::error('ブックマークフォルダインポート失敗');
          DB::rollBack();
          return response()->json([], 500);
        }
        $bookMarkFoldersId = $bookMarkFolder->id; //ブックマーク登録用のid
      }
      // 読み込み行がaタグ（＝ブックマーク）の場合、ブックマーク登録開始
      if($title || $link){
        $input = [
          'user_id' => $user_id,
          'book_mark_folders_id' => $bookMarkFoldersId,
          'title' => $title[1] ? $title[1] : $link[1], // タイトル不存在の場合はタイトルはURLとする
          'link' => $link[1],
        ];
        // バリデーション
        $bookMark = new BookMark();
        $validate = $bookMark->validate($input);
        if($validate->fails()) {
          $message = $validate->errors();
          $validateState = false;
          Log::error("ブックマークインポート失敗_バリデーションエラー");
          return response()->json(['message' => $message, 'validateState' => $validateState]);
        }
        // 登録開始
        DB::beginTransaction();
        try{
          $bookMark->fill($input);
          $bookMark->save();
          DB::commit();
        } catch (\Exception $e) {
          Log::error($e->getMessage());
          Log::error('ブックマークインポート失敗');
          DB::rollBack();
          return response()->json([], 500);
        }
      }
    }
    fclose($file);
    Log::info('ブックマークインポート終了');
  }
}
