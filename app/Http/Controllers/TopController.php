<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopController extends Controller
{
  /**
  * ニュース取得
  * @return Http response
  */
  public function getNews(Request $request)
  {
    Log::info('ニュース情報取得開始');
    $params = array('apiKey' => config('const.apiKey'),
                    'country' => config('const.country'),
                    'pageSize' => config('const.newsPage'),
                    'category' => $request['newsCategory'],
                    'q' => $request['newsWordSerch'],
                  );
    $url = 'https://newsapi.org/v2/top-headlines?'.http_build_query($params);
    $options = [
      CURLOPT_URL => $url,
      CURLOPT_SSL_VERIFYPEER => false, // サーバー証明書の検証を行わない
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    curl_close($ch);
    Log::info('ニュース情報取得終了');
    return;
  }
}
