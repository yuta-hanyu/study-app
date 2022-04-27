<!DOCTYPE html>
<html lang="ja">
<body>
  <p>※本メールは自動配信メールです。</p>
  <h3>{{$title}}</h3>
  <h3>明日期日を迎えるタスクがあります。</h3>
  <hr>
    @foreach($todos as $todo)
    <ul>
      <li>件名：{{$todo->todos_title}}</li>
      <li>期日：{{$todo->todos_reminder}}</li>
    </ul>
    @endforeach
  <a href="{{ $studyAppUrl }}">StudyAppへログイン</a>
</body>
</html>
