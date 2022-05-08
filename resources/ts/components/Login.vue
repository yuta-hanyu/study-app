<template>
  <div>
    <v-sheet width="800px" dark class="kokuban my-0">
      <p style="text-align: center;" class="dialog-title">ログイン</p>
      <v-row justify="center">
        <v-col cols="12" v-if="alertMsgs.length">
          <alert-msg class="mt-4"
            :alertType=alertType
            :alertMsgs=alertMsgs>
          </alert-msg>
        </v-col>
        <v-col cols="12">
          <v-row justify="center">
            <v-col cols="12">
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
            <v-col cols="3">
              <v-btn
                class="back"
                width="80%"
                @click="back()">
                戻る
              </v-btn>
            </v-col>
            <v-col cols="3">
              <v-btn
                class="go"
                width="80%"
                @click="login">
                ログイン
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-sheet>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../common/const';
import Axios from 'axios';
import Util from '../common/util';
import AlertMsg from './utilComponent/AlertMsg.vue';

@Component ({
  name: "Login",
  components: {
    AlertMsg,
  },
})

export default class Login extends Mixins(Const, Util) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {};
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

  mounted() {
    this.closeLoading();
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
          this.$router.push("/top");
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
