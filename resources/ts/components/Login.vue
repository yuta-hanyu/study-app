<template>
  <div>
    <v-container>
      <v-row justify="center" align-content="center" style="height: 600px;">
        <v-col cols="8" align="center">
        <v-sheet
        color="amber lighten-5"
        elevation="1"
        width="100%"
        height="100%">
        <div class="text-h4 py-5">ログイン</div>
          <v-row
            class="mt-3"
            v-if="this.validatMessage.length"
            justify="center">
            <v-alert
              v-for="(message, index) in this.validatMessage" :key=index
              dense
              text
              border="left"
              type="error"
              class="px-6"
              width="70%">
              {{message}}
            </v-alert>
          </v-row>
          <v-row
            class="mt-3"
            v-if="this.message"
            justify="center">
            <v-alert
              dense
              text
              border="left"
              type="error"
              class="px-6"
              width="70%">
              {{message}}
            </v-alert>
          </v-row>
          <v-form>
            <v-col cols="10" align="center">
              <v-text-field
                class="px-10"
                v-model="email"
                label="メールアドレス"
                required
                :counter="255"
              ></v-text-field>
            </v-col>
            <v-col cols="10" align="center">
              <v-text-field
                class="px-10"
                v-model="password"
                :counter="8"
                label="パスワード"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-checkbox
                v-model="omitEmailSend"
                label="次回以降、メールアドレスの入力をスキップ"
                color="red"
                dense
            ></v-checkbox>
            </v-col>
            <v-col cols="10" align="center">
              <v-btn
                class="mr-4"
                @click="login"
                color="orange lighten-2">
                ログイン
              </v-btn>
            </v-col>
          </v-form>
        </v-sheet>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../common/const';
import Axios from 'axios';

@Component ({
  name: "Login",
})

export default class Login extends Mixins(Const){
  // 認証メールアドレス
  private email: string = this.$store.state.userInfo.omitEmail;
  // 認証パスワード
  private password: string = "";
  // バリデーションMSG
  private validatMessage: string[] = [];
  // 結果MSG
  private message: string = "";
  // メールアドレススキップ
  private omitEmailSend: boolean = false;
  /**
   * ログインボタン押下
   */
  login(): void {
    // エラーメッセージ初期化;
    this.validatMessage = [];
    this.message = '';
    // クッキー認証
    Axios.get('/api/csrf-cookie', {withCredentials:true}).then((res) => {
      // ログイン認証開始
      Axios.post('/api/login', {
        email: this.email,
        password: this.password,
      },{withCredentials:true}).then((res) => {
        if( res.data.retultFlag == true ) {
          // vuexにユーザー情報をセット
          this.$store.dispatch('setLoginUser', {
            loginUserName: res.data.userInfo.name,
            userId: res.data.userInfo.userId,
          });
          // vuexにメールアドレスをセット
          if(this.omitEmailSend == true) {
            this.$store.dispatch('setOmitEmail', {
              omitEmail: this.email
            });
          };
          this.$router.push("/todo");
        } else {
          this.validatMessage = this.changeErrors(res.data.validatMessage);
        };
      }).catch((e) => {
        //認証エラー
        console.log(e);
        this.message = this.ERROR_MSG.LOGIN_FAILD
      });
    }).catch((e) => {
      //認証エラー
      window.alert(this.ERROR_MSG.AUTH_FAILD);
      console.log(e);
    });
  }
  /**
   * エラーメッセージをオブジェクトから配列へ変換
   */
  private changeErrors(message: string[]): string[] {
    let errors: string[] = [];
    for (let [key, value] of Object.entries(message)) {
      errors.push(value[0]);
    }
    return errors;
  }
}
</script>

<style>
</style>
