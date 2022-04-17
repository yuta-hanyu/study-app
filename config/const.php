<?php
return [
  /** -----URL----- */
  // 'STUDY_APP_URL' => env('APP_URL'),
  /** -----ブックマーク関連----- */
  'BOOK_MARK_FIXED' => 1,
  'BOOK_MARK_NOT_FIXED' => 0,
  /** -----メール送信関連----- */
  'Inquiry' => 1,
  'TO_BCC_MAIL' => 'remakeremake9011@gmail.com',
  /** -----仮会員登録関連----- */
  'SEND_MAIL' => 0,   // 仮会員登録のメール送信
  'MAIL_VERIFYED' => 1, //メールアドレス認証
  'REGISTERED' => 2,    // 本会員登録完了
  'TEMPORARY_EXPIRATION_DATETIME' => 1, // 仮登録有効期限
  'GUEST_USER_Email' => 'guest@gmail.com', // ゲストユーザー
];



