import { Component } from 'vue-property-decorator';
import Vue from 'vue';

@Component
export default class constant extends Vue {
  // サイドメニュー
  MENU_ITEMS = Object.freeze([
    Object.freeze({name: 'トップ', url: '/'}),
    Object.freeze({name: 'TODO', url: '/todo'}),
    // Object.freeze({name: 'ログアウト', url: '/logout'}),
    // Object.freeze({name: 'トップ', url: '/'})
  ]);
  // エラーMSG
  ERROR_MSG = Object.freeze({
    EXPAIRED_SESSION: 'セッションの有効期間が切れています。再度、ログインしてください',
    SERVER_ERROR: 'サーバーエラーが発生しました',
    LOGOUT_FAILD: 'ログアウトに失敗しました',
    LOGIN_FAILD: 'ログインに失敗しました',
    AUTH_FAILD: '認証エラーです'
  });
}
