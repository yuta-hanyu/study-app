<!DOCTYPE html>
<html lang="ja">
<body>
  <h3>Webサービス「StudyApp」への仮会員登録が完了しました。</h3>
  <p>以下のリンクからログインし、本登録を行なってください。</p>
  <p>以下の内容について承りました。</p>
  <hr>
  <a href="{{ $studyAppUrl }}">本登録を行う</a>
  <p>※仮登録の有効期限：{{ $expiration_datetime }}</p>
  <hr>
</body>
</html>
