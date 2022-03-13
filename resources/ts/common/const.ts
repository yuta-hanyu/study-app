import { Component } from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: "Const",
})
export default class Const extends Vue {
  // ゲストユーザー
  GUEST_USER = Object.freeze({
    email: 'guest@gmail.com',
    password: 'guest'
  });
  // サイドメニュー
  MENU_ITEMS = Object.freeze([
    {name: 'トップ', url: '/'},
    {name: 'タスク管理', url: '/todo'},
    {name: 'ブックマーク', url: '/bookMark'},
  ]);
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
  });
}
