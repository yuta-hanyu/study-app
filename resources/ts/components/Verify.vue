<template>
  <v-row justify="center" align-content="center">
    <v-col cols="8" class="my-10">
      <v-sheet class="kokuban" dark>
        <v-form>
          <v-container>
            <v-row justify="center">
              <v-col md="10">
                <p class="dialog-title text-center">会員本登録</p>
              </v-col>
              <v-col cols="10" class="mt-n5">
                <v-sheet class="px-2 py-2 text-center" color="#1B5E20" rounded>
                  <p class="mb-0">仮会員登録の認証が完了しました！<br/>
                    以下をご入力の上、登録ボタンを押下し、本登録を行なってください<br/>
                  </p>
                </v-sheet>
              </v-col>
              <v-col cols="12" v-if="alertMsgs.length">
                <alert-msg class="mt-4"
                  :alertType=alertType
                  :alertMsgs=alertMsgs>
                </alert-msg>
                </v-col>
                <v-col cols="10">
                  <v-row justify="center">
                    <v-col cols="10">
                      <v-text-field
                        v-model="newUserInfo.name"
                        prepend-icon="mdi-account"
                        label="お名前">
                      </v-text-field>
                    </v-col>
                    <v-col cols="10">
                      <v-text-field
                        v-model="newUserInfo.email"
                        prepend-icon="mdi-email"
                        label="メールアドレス">
                      </v-text-field>
                    </v-col>
                    <v-col cols="10">
                      <v-text-field
                        dark
                        v-model="newUserInfo.password"
                        :type="showPassword ? 'text' : 'password'"
                        @click:append="showPassword = !showPassword"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :counter="8"
                        label="パスワード"
                        required
                        prepend-icon="mdi-lock"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="10">
                      <v-text-field
                        dark
                        v-model="confirmPassword"
                        :type="confirmShowPassword ? 'text' : 'password'"
                        @click:append="confirmShowPassword = !confirmShowPassword"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :counter="8"
                        label="確認用パスワード"
                        required
                        prepend-icon="mdi-lock-outline"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                <v-row justify="center">
                  <v-col
                    class="text-center"
                    cols="3">
                    <v-btn
                      class="back mr-5"
                      width="180px"
                      @click="backLogin">
                      戻る
                    </v-btn>
                  </v-col>
                  <v-col cols="3" class="text-center">
                    <v-btn
                      class="go ml-5"
                      width="200px"
                      @click="userRegister">
                      本登録
                    </v-btn>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </v-sheet>
    </v-col>
    <!-- アラートダイアログ -->
    <v-dialog
      width="600px"
      v-model="AlertDialog"
      persistent>
      <alert
        @back="AlertDialog = !AlertDialog, backLogin()">
        <template #title>{{tokenVerifyFeilMsg}}</template>
        <template #content>ログイン画面に遷移します。<br/>お手数ですが、再度、仮会員登録をお願いいたします。</template>
      </alert>
    </v-dialog>
    <!-- 完了ダイアログ -->
    <v-dialog
      width="400px"
      v-model="completeDialog"
      persistent>
      <complete
        @back="completeDialog = !completeDialog, login()">
        <template #title>会員本登録が完了しました！</template>
      </complete>
    </v-dialog>
  </v-row>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../common/const';
import Util from '../common/util';
import Alert from './utilComponent/Alert.vue';
import Complete from './utilComponent/Complete.vue';
import { User } from '../interfaces/User';
import AlertMsg from '../components/utilComponent/AlertMsg.vue';

@Component({
  name: 'Verify',
  components:{
    Alert,
    Complete,
    AlertMsg,
  }
})

export default class Verify extends Mixins(Const, Util) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize();
      this.msgReset();
    };

  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // パスワード表示非表示フラグ
  private showPassword: boolean = false;
  // 確認用パスワード表示非表示フラグ
  private confirmShowPassword: boolean = false;
  // フォームバリデーションエラー
  private tokenVerifyFeilMsg: string = '';
  // 登録ユーザー情報
  private email: string = '';
  // アラートダイアログ
  private AlertDialog: boolean = false;
  // 完了ダイアログ
  private completeDialog: boolean = false;
  // 新規登録会員情報
  private newUserInfo: User = {};
  // 確認用パスワード
  private confirmPassword: string = '';

  mounted(){
    this.tokenVerify();
  };
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
    this.tokenVerifyFeilMsg = '';
  }
  /**
   * トークン認証
   */
  private tokenVerify(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/register/tokenVerify',{
      token: this.$route.params.token
    }).then((res) => {
      if(res.data.resultFlag === false) {
        this.tokenVerifyFeilMsg = res.data.message;
        this.AlertDialog = !this.AlertDialog;
        return;
      }
      this.newUserInfo.email = res.data.email; //仮登録時のメールを初期値としてセット
      this.$forceUpdate(); //強制反映
    }).catch((e) => {
      console.log(e);
      this.serverError(e);
    }).finally(() => this.closeLoading())
  };
  /**
   * 会員本登録
   */
  private userRegister(): void {
    this.msgReset();
    // パスワード一致チェック
    if (this.newUserInfo.password !== this.confirmPassword) {
      this.alertType = 'error';
      this.alertMsgs.push('パスワードが一致していません。');
      return;
    }
    if (!this.newUserInfo.password || !this.confirmPassword) {
      this.alertType = 'error';
      this.alertMsgs.push('パスワードを入力してください。');
      return;
    }
    if(!window.confirm(`ご入力いただいた内容で本登録を行いますか？`)) {
      return;
    };
    this.setLoading();
    Axios.post('/api/register/newUser',{
      token: this.$route.params.token,
      newUserInfo: this.newUserInfo
    }).then((res) => {
      if(res.data.validateState === false) {
        this.alertType = 'error';
        for (let [key, value] of Object.entries(res.data.message)) {
          if(typeof value === 'object') {
            if(value !== undefined && value !== null){
              this.alertMsgs.push(value[0]);
            }
          }
        };
        return;
      }
      this.completeDialog = !this.completeDialog;
    }).catch((e) => {
      this.serverError(e);
    }).finally(() => this.closeLoading())
  };
  /**
   * 会員登録成功後のログイン認証
   */
  private login(): void {
    this.msgReset();
    this.setLoading();
    Axios.get('/api/csrf-cookie', {withCredentials:true}).then((res) => {
      // ログイン認証開始
      Axios.post('/api/login', {
        email: this.newUserInfo.email,
        password: this.newUserInfo.password,
      },{withCredentials:true}).then((res) => {
        if(res.data.retultFlag === true ) {
          // vuexにユーザー情報をセット
          this.$store.dispatch('setLoginUser', {
            name: res.data.userInfo.name,
            user_id: res.data.userInfo.user_id,
          });
          this.$router.push("/bookMark");
        }
        if(res.data.retultFlag === false) {
          this.alertType = 'error';
          for (let [key, value] of Object.entries(res.data.validatMessage)) {
            if(typeof value === 'object') {
              if(value !== undefined && value !== null){
                this.alertMsgs.push(value[0]);
              }
            }
          };
          return;
        }
      }).catch((e) => {
        //認証エラー
        this.alertMsgs.push(this.ERROR_MSG.LOGIN_FAILD);
      });
    }).catch((e) => {
      //認証エラー
      window.alert(this.ERROR_MSG.AUTH_FAILD);
    }).finally(() => this.closeLoading());
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    this.newUserInfo = Object.assign({}, {})
  };
  /**
   * ログイン画面へ戻る
   */
  private backLogin(): void {
    this.$router.push("/");
  };
}
</script>

<style scoped>

</style>


