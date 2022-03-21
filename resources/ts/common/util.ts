import { Component,Mixins } from 'vue-property-decorator';
import Vue from 'vue';
import Const from './const';

@Component({
  name: "Util",
})

export default class Util extends Mixins(Const, Vue) {
  /**
   * 認証チェック
   * @param e エラー
   */
  public authCheck(e: any): void {
    if(e.response.status === 401) {
      window.alert(this.ERROR_MSG.EXPAIRED_SESSION);
      this.$store.dispatch('resetUserInfo');
      this.$router.push("/login");
    };
  };
  /**
   * サーバーエラー
   * @param e エラー
   */
  public serverError(e: any): void {
    if(e.response.status === 500) {
      window.alert(this.ERROR_MSG.SERVER_ERROR);
    };
  };
}
