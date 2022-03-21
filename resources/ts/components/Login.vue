<template>
  <!-- <div> -->
    <v-container>
      <v-row justify="center" style="height: 800px;">
        <v-col
        class="d-flex title-container">
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
          <div class="chalk1"></div>
<div class="chalk2"></div>
        </v-col>
        <v-col
          md="10"
          sm="10"
          v-if="this.message">
          <v-row
            class="mt-3"
            justify="center">
            <v-alert
              dense
              text
              border="left"
              type="error"
              class="px-auto"
              width="70%">
              {{message}}
            </v-alert>
          </v-row>
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
            <v-col md="3" align="center">
              <v-btn
                class="font-weight-black"
                dark
                width="70%"
                @click="login(false)"
                color="orange lighten-2">
                ログイン
              </v-btn>
            </v-col>
            <v-col md="3">
              <v-btn
                class="font-weight-black"
                align="right"
                width="70%"
                color="success"
                @click="login(true)">
                ゲストログイン
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
  <!-- </div> -->
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
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
  private title: string = 'title';
  mounted() {
    // タイトル表示制御
    setInterval(() => {
      this.title += " -visible";
      console.log(this.title)
      setTimeout(() => {
        this.title = "title";
        console.log(this.title)
      }, 1500);
    }, 3000);
  }
  /**
   * ログインボタン押下
   */
  login(gestFlag: boolean): void {
    // ゲストログインの場合
    if(gestFlag === true) {
        this.email = this.GUEST_USER.email;
        this.password = this.GUEST_USER.password;
    };
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
        this.message = this.ERROR_MSG.LOGIN_FAILD
      });
    }).catch((e) => {
      //認証エラー
      window.alert(this.ERROR_MSG.AUTH_FAILD)
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

<style scoped>
.checkbox {
	display: flex;
	align-items: center;
	justify-content: center;
}
.title-container {
  margin-top: 10%;
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
