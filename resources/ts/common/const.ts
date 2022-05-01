import { Component } from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: "Const",
})
export default class Const extends Vue {
  /** -----ゲストユーザー----- */
  GUEST_USER = Object.freeze({
    email: 'guest@gmail.com',
    password: 'guest'
  });
  /** -----サイドメニュー----- */
  MENU_ITEMS = Object.freeze([
    {name: 'トップ', url: '/'},
    {name: 'タスク管理', url: '/todo'},
    {name: 'ブックマーク管理', url: '/bookMark'},
    {name: '会員情報', url: '/account'},
  ]);
  /** -----プロパティ関連----- */
  // Top画面・ニュースカテゴリー
  NEWS_CATEGORES = Object.freeze([
    '','business', 'entertainment', 'general', 'health','science','sports','technology',
  ]);
  // ブックマーク上部固定
  BOOKMARKFIXED = Object.freeze(1);
  // todoステータス
  TOD0_STATUS = Object.freeze({
    UNTREATED: 0, //未対応
    ON_THE_WAY: 1, //対応中
    ON_HPLD: 2, //保留
    FINISH: 3, //完了
  });
  // todoブックマーク上部固定
  TODO_BOOKMARK_FIXED = Object.freeze(1);
  /** -----メッセージ関連----- */
  // エラーMSG
  ERROR_MSG = Object.freeze({
    EXPAIRED_SESSION: 'セッションの有効期間が切れています。再度、ログインしてください',
    SERVER_ERROR: 'サーバーエラーが発生しました',
    LOGOUT_FAILD: 'ログアウトに失敗しました',
    LOGIN_FAILD: 'ログインに失敗しました',
    AUTH_FAILD: '認証エラーです',
    UPDATE_ERROR: 'データの更新に失敗しました',
    GET_ERROR: 'データの取得に失敗しました',
  });
  // サクセスMSG
  SUCCESS_MSG = Object.freeze({
    STORE_SUCCESS: 'を新たに登録しました',
    EDIT_SUCCESS: 'を編集しました',
    DELETE_SUCCESS: 'を削除しました',
    ALL_ELETE_SUCCESS: 'を全て削除しました',
    IMPORT_SUCCESS: '取込が完了しました',
    REGISTER_SUCCESS: '登録が完了しました',
    REGISTER_TEMPORART_SIGN_UP: '仮登録メールをお送りしました',
  });
  // 確認MSG
  CONFIRM_MSG = Object.freeze({
    DELETE: 'を削除しますか？',
    CANSEL: 'キャンセルしました。',
    REGISTER: '以下の内容にて登録しますか？'
  });
}
