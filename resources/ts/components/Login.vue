<template>
  <div class="bg">
    <v-container>
      <v-row justify="center" style="height: 800px;">
        <v-col cols="10" class="title-container">
          <div :class="title">
            <span>S</span>
            <span>T</span>
            <span>U</span>
            <span>D</span>
            <span>Y</span>
            <span>&nbsp;</span>
            <span>A</span>
            <span>P</span>
            <span>P</span>
          </div>
        </v-col>
        <v-col cols="12" v-if="alertMsgs.length">
          <alert-msg class="mt-4"
            :alertType=alertType
            :alertMsgs=alertMsgs>
          </alert-msg>
        </v-col>
        <v-col md="10" mx="10">
          <v-row justify="center">
            <v-col md="10" mx="10">
              <v-form>
                <v-col md="12" align="center">
                  <v-text-field
                    dark
                    class="px-10"
                    v-model="email"
                    label="メールアドレス"
                    required
                    :counter="255"
                  ></v-text-field>
                </v-col>
                <v-col md="12" align="center">
                  <v-text-field
                    dark
                    class="px-10"
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    @click:append="showPassword = !showPassword"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :counter="8"
                    label="パスワード"
                    required
                  ></v-text-field>
                </v-col>
                <v-col class="checkbox">
                  <v-checkbox
                    dark
                    v-model="omitEmailSend"
                    label="次回以降、メールアドレスの入力をスキップ"
                    color="red"
                    dense
                  ></v-checkbox>
                </v-col>
              </v-form>
            </v-col>
          </v-row>
          <v-row justify="center">

          <v-col md="3" align="right" class="mr-0">
            <v-tooltip bottom color="#EEEEEE">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                @click="TemporarySignUpDialog = !TemporarySignUpDialog"
                class="font-weight-black"
                dark
                v-bind="attrs"
                v-on="on"
                width="60%"
                elevation="11"
                color="#0D47A1">
                会員登録
              </v-btn>
              </template>
              <span style="color: black;">新規会員登録はこちら</span>
            </v-tooltip>
          </v-col>

          <v-col md="3" align="center">
            <v-tooltip bottom color="#EEEEEE">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-bind="attrs"
                  v-on="on"
                  class="font-weight-black"
                  dark
                  width="60%"
                  elevation="11"
                  @click="login(false)"
                  color="#388E3C">
                  ログイン
                </v-btn>
              </template>
              <span style="color: black;">会員登録済みの方はこちら</span>
            </v-tooltip>
          </v-col>

          <v-col md="3" align="left" class="mr-0">
            <v-tooltip bottom color="#EEEEEE">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-bind="attrs"
                  v-on="on"
                  dark
                  class="font-weight-black"
                  align="right"
                  width="60%"
                  elevation="11"
                  color="#880E4F"
                  @click="login(true)">
                  ゲストログイン
                </v-btn>
              </template>
              <span style="color: black;">お試しはこちら</span>
            </v-tooltip>
          </v-col>

          </v-row>
        </v-col>
      </v-row>
      <!-- 新規会員登録ダイアログ -->
    </v-container>
      <v-dialog
        v-model="TemporarySignUpDialog"
        persistent
        width="600px">
        <temporary-sign-up
          @back="TemporarySignUpDialog=!TemporarySignUpDialog"
          @sign-uped="TemporarySignUpDialog=!TemporarySignUpDialog">
        </temporary-sign-up>
      </v-dialog>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Const from '../common/const';
import Axios from 'axios';
import Util from '../common/util';
import TemporarySignUp from './accountComponents/TemporarySignUp.vue';
import AlertMsg from './utilComponent/AlertMsg.vue';

@Component ({
  name: "Login",
  components: {
    AlertMsg,
    TemporarySignUp
  },
})

export default class Login extends Mixins(Const, Util) {
  // 認証メールアドレス
  private email: string = this.$store.state.userInfo.omitEmail;
  // 認証パスワード
  private password: string = "";
  // パスワード表示非表示フラグ
  private showPassword: boolean = false;
    // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // メールアドレススキップ
  private omitEmailSend: boolean = false;
  // アプリ名タイトル
  private title: string = 'title';
  // 会員登録ダイアログ
  private signUpDialog: boolean = false;
  // 仮会員登録ダイアログ
  private TemporarySignUpDialog: boolean = false;

  mounted() {
    this.closeLoading();
    // タイトル表示制御
    setInterval(() => {
      this.title += " -visible";
      setTimeout(() => {
        this.title = "title";
      }, 1000);
    }, 2000);
  }
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
  }
  /**
   * ログインボタン押下
   */
  private login(gestFlag: boolean): void {
    this.msgReset()
    this.setLoading();
    // ゲストログインの場合
    if(gestFlag === true) {
        this.email = this.GUEST_USER.email;
        this.password = this.GUEST_USER.password;
    };
    // クッキー認証
    Axios.get('/api/csrf-cookie', {withCredentials:true}).then((res) => {
      // ログイン認証開始
      Axios.post('/api/login', {
        email: this.email,
        password: this.password,
      },{withCredentials:true}).then((res) => {
        if(res.data.retultFlag === true ) {
          // vuexにユーザー情報をセット
          this.$store.dispatch('setLoginUser', {
            name: res.data.userInfo.name,
            user_id: res.data.userInfo.user_id,
          });
          // vuexにメールアドレスをセット
          if(this.omitEmailSend === true) {
            this.$store.dispatch('setOmitEmail', {
              omitEmail: this.email
            });
          };
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
}
</script>

<style scoped>
.bg {
  left: 0;
  min-height: 1000px;
  background-size: contain;
  background: url("../../../public/images/login.jpg") center center / cover no-repeat fixed;
  background-attachment: fixed;
}
.checkbox {
	display: flex;
	align-items: center;
	justify-content: center;
}
.title-container {
  width: 600px;
  margin-top: 3%;
  text-align: center;
  height: 300px;
	display: flex;
	align-items: center;
	justify-content: center;
  background: #104300;
  border: 8px solid #a60;
  box-shadow: 2px 2px 4px #999, 2px 2px 2px #020 inset;
}
.title {
  display: flex;
  overflow: hidden;
  color: #FFFF8D;
  font-family: 'Josefin Sans', sans-serif;
}
.title span {
  display: block;
  transform: translate(0, 115%);
  transition: transform cubic-bezier(0.215, 0.61, 0.355, 1) 0.5s;
  font-weight: bold;
  font-size: 50px;
  height: 50px;
  margin-right: 7px;
  margin-top: 10px;
  margin-bottom: 2px;
}
.title.-visible span {
  transform: translate(0, 0);
}
.title span:nth-child(2) {
  transition-delay: 0.06s;
}
.title span:nth-child(3) {
  transition-delay: 0.12s;
}
.title span:nth-child(4) {
  transition-delay: 0.18s;
}
.title span:nth-child(5) {
  transition-delay: 0.24s;
}
.title span:nth-child(6) {
  transition-delay: 0.30s;
}
.title span:nth-child(7) {
  transition-delay: 0.36s;
}
.title span:nth-child(8) {
  transition-delay: 0.42s;
}
.title span:nth-child(9) {
  transition-delay: 0.48s;
}
.title span:nth-child(10) {
  transition-delay: 0.54s;
}
.title span:nth-child(11) {
  transition-delay: 0.6s;
}
.title span:nth-child(12) {
  transition-delay: 0.66s;
}
.title span:nth-child(13) {
  transition-delay: 0.72s;
}
.title span:nth-child(14) {
  transition-delay: 0.78s;
}
</style>
