<!DOCTYPE html>
<html lang="ja">
<body>
  <h3>お問い合わせを受け付けました。</h3>
  <p>Webサービス「StudyApp」をご利用頂き誠にありがとうございます。</p>
  <p>以下の内容について承りました。</p>
  <hr>
  <p>お問い合わせ種別：{{$type}}</p>
  <p>お問い合わせ内容：{{$content}}</p>
  <hr>
  <a href="{{ $studyAppUrl }}">StudyAppへログイン</a>
</body>
</html>
