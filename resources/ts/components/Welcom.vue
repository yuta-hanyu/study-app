<template>
  <div style="background:#FFFFFF; padding-right: 10%; padding-left: 10%;">
    <v-app-bar
      app
      color="teal darken-4"
      elevation="0">
      <img
          class="my-1"
          height="60"
          width="60"
          src="/images/favicon.png" />
      <p class="mb-0 font">StudyApp</p>
      <v-spacer></v-spacer>
      <v-tooltip bottom color="#EEEEEE">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            v-bind="attrs"
            v-on="on"
            dark
            class="font-weight-black mx-3"
            width="150px"
            elevation="11"
            color="#E53935"
            @click="googleLogin">
            <v-icon
              dark
              left>
              mdi-google
            </v-icon>ログイン
          </v-btn>
        </template>
        <span style="color: black;">Google認証はこちら</span>
      </v-tooltip>
      <v-tooltip bottom color="#EEEEEE">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            v-bind="attrs"
            v-on="on"
            dark
            class="font-weight-black mx-3"
            width="150px"
            elevation="11"
            color="#880E4F"
            @click="guestlogin">
            ゲストログイン
          </v-btn>
        </template>
        <span style="color: black;">お試しはこちら</span>
      </v-tooltip>
      <v-tooltip bottom color="#EEEEEE">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            v-bind="attrs"
            v-on="on"
            class="font-weight-black mx-3"
            dark
            width="150px"
            elevation="11"
            @click="loginDialog=!loginDialog"
            color="#388E3C">
            ログイン
          </v-btn>
        </template>
        <span style="color: black;">会員登録済みの方はこちら</span>
      </v-tooltip>
      <v-tooltip bottom color="#EEEEEE">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
          @click="TemporarySignUpDialog = !TemporarySignUpDialog"
          class="font-weight-black mx-3"
          dark
          v-bind="attrs"
          v-on="on"
          width="150px"
          elevation="11"
          color="#0D47A1">
          会員登録
        </v-btn>
        </template>
        <span style="color: black;">新規会員登録はこちら</span>
      </v-tooltip>
    </v-app-bar>
    <div style="height: 100px;"></div>
    <div class="welcom-kokuban mt-10">
      <v-row justify="center">
        <v-col cols="10" class="title-container">
          <div class="sub-container">
            <h1 class="main-font">StudyApp</h1>
            <h3 class="sub-font">〜  学習・仕事をもっと効率的に  〜</h3>
          </div>
        </v-col>
      </v-row>
    </div>
    <div class="section-todo-title">
      <p>StudyAppの機能紹介</p>
    </div>
    <div class="section-todo-container">
      <!-- タスク管理 -->
      <div class="section-todo-1">
        <v-row class="mx-5 my-5">
          <v-col md="10" class="mt-10">
            <h1 class="section-todo-h1">
              <v-icon left x-large color="green">mdi-calendar-check</v-icon>
              タスク管理
            </h1>
            <h3>タスクを直感的な操作で管理することができます。</h3>
          </v-col>
          <v-col md="12">
            <v-row justify="center">
              <v-col md="4">
                <v-card
                  color="#E8F5E9"
                  class="mx-auto">
                  <p class="card-title">
                    タスクの状態によって色分け表示します。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/todo.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#E8F5E9"
                  class="mx-auto">
                  <p class="card-title">
                    タスクはドラッグ&ドロップで順番入れ替えが可能です。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/todo_drag.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#E8F5E9"
                  class="mx-auto">
                  <p class="card-title">
                    リマインド機能があり、期日の前日になると、ご登録のメールアドレスへリマインドメールを送信します。
                    <br>また、期日当日にはデスクトップ通知も行います。
                  </p>
                  <v-img
                    src="/img/todoMail.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                  <v-img
                    src="/img/push.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </div>
      <!-- ブックマーク管理 -->
      <div class="section-todo-2">
        <v-row class="mx-5 my-5">
          <v-col md="10" class="mt-10">
            <h1 class="section-todo-h1">
              <v-icon left x-large color="green">mdi-star</v-icon>
              ブックマークマネージャー
            </h1>
            <h3>ブックマークやお気に入り登録を一元管理し、管理の煩雑性からあなたを解放します。</h3>
          </v-col>
          <v-col md="12">
            <v-row justify="center">
              <v-col md="4">
                <v-card
                  color="#FFFFFF"
                  class="mx-auto">
                  <p class="card-title">
                    頻繁に使用するブックマークは上部に固定し、それ以外はフォルダごとに整理することできます。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/bookmark.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#FFFFFF"
                  class="mx-auto">
                  <p class="card-title">
                    google標準のブックマークからデータをインポートすることができるため、googleブックマークから本アプリへのデータ移行が簡単にできます。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/bookmarkImport.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#FFFFFF"
                  class="mx-auto">
                  <p class="card-title">
                    ブックマーク曖昧検索機能があり、簡単にお目当てのブックマークへアクセスすることができます。
                  </p>
                  <v-img
                    src="/img/bookmarkserch.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </div>
      <!-- トップページ -->
      <div class="section-todo-1">
        <v-row class="mx-5 my-5">
          <v-col md="10" class="mt-10">
            <h1 class="section-todo-h1">
              <v-icon left x-large color="green">mdi-newspaper-variant</v-icon>
              トップページ（ニュース、ランダム格言）
            </h1>
            <h3>トップページには本日のニュース等を表示します（天気予報等の機能拡張を実施中）。</h3>
          </v-col>
          <v-col md="12">
            <v-row justify="center">
              <v-col md="4">
                <v-card
                  color="#E8F5E9"
                  class="mx-auto">
                  <p class="card-title">
                    本日のニュースを掲載しています。<br>学習や仕事の合間にチェックすることができます。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/top_news.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#E8F5E9"
                  class="mx-auto">
                  <p class="card-title">
                    ニュースはカテゴリやキーワード検索で絞り込みができます。
                  </p>
                  <v-img
                    src="/img/top_news_categpry.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#E8F5E9"
                  class="mx-auto">
                  <p class="card-title">
                    管理者の好みの格言をランダムに表示しています。<br>何かのモチベーションになれば・・
                  </p>
                  <v-img
                    src="/img/top_maxim.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </div>
      <!-- 会員登録、お問い合わせ-->
      <div class="section-todo-2">
        <v-row class="mx-5 my-5">
          <v-col md="10" class="mt-10">
            <h1 class="section-todo-h1">
              <v-icon left x-large color="green">mdi-account-check</v-icon>
              会員登録
            </h1>
            <h3>ゲストログインからお試しでご利用ください。気に入っていただけましたら会員登録お願いします^_^。</h3>
          </v-col>
          <v-col md="12">
            <v-row justify="center">
              <v-col md="4">
                <v-card
                  color="#FFFFFF"
                  class="mx-auto">
                  <p class="card-title">
                    本ページ右上「ゲストログイン」ボタンからお試し利用が可能です。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/guestLogin.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#FFFFFF"
                  class="mx-auto">
                  <p class="card-title">
                    会員登録は本ページ右上「会員登録」ボタンを押下してください。
                    <br>その上で、お手数ですが、仮会員登録にてメール認証を行なっていただき、本会員登録をお願いいたします。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/karikaiinn.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
              <v-col md="4">
                <v-card
                  color="#FFFFFF"
                  class="mx-auto">
                  <p class="card-title">
                    会員登録後のお問い合わせは、サイドメニュー「お問い合わせ」からお願いします。
                    <br>期日、管理者よりご登録のメールアドレスへ回答をお送りさせていただきます。
                  </p>
                  <v-img
                    :aspect-ratio="16/11"
                    src="/img/otoiawase.png">
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </div>

    </div>

    <!-- ログインダイアログ -->
    <v-dialog
      v-model="loginDialog"
      persistent
      width="800px">
      <login
        @back="loginDialog=!loginDialog">
      </login>
    </v-dialog>
    <!-- 新規会員登録ダイアログ -->
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
import Login from './Login.vue';

@Component ({
  name: "Welcom",
  components: {
    AlertMsg,
    TemporarySignUp,
    Login
  },
})

export default class Welcom extends Mixins(Const, Util) {
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // 会員登録ダイアログ
  private signUpDialog: boolean = false;
  // 仮会員登録ダイアログ
  private TemporarySignUpDialog: boolean = false;
  // 会員登録ダイアログ
  private loginDialog: boolean = false;

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
   * gooleログイン押下
   */
  private googleLogin(): void {
    this.msgReset();
    this.setLoading();
    // ログイン認証開始
    Axios.get('/api/login/google').then((res) => {
      window.location.href = res.data.redirect_url;
    }).catch((e) => {
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * ゲストログイン押下
   */
  private guestlogin(): void {
    this.msgReset()
    this.setLoading();
    // クッキー認証
    Axios.get('/api/csrf-cookie', {withCredentials:true}).then((res) => {
      // ログイン認証開始
      Axios.post('/api/login', {
        email: this.GUEST_USER.email,
        password: this.GUEST_USER.password,
      },{withCredentials:true}).then((res) => {
        if(res.data.retultFlag === true ) {
          // vuexにユーザー情報をセット
          this.$store.dispatch('setLoginUser', {
            name: res.data.userInfo.name,
            user_id: res.data.userInfo.user_id,
          });
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
.welcom-kokuban {
  height: 500px;
  width: 100%;
  font-family: HuiFontP109 !important;
  color: whitesmoke !important;;
  background-color: #114400 !important;;
  margin: 10px 0 10px 0 !important;;
  padding: 15px !important;;
  /* margin-top: 120px !important;; */
  border: 9px solid #a60 !important;;
  border-radius: 3px !important;;
  box-shadow: 2px 2px 4px #666, 2px 2px 2px #111 inset !important;;
  text-shadow: 0px 0px 2px #000 !important;;
  line-height: 1.9 !important;;
}
.font {
  color: whitesmoke;
}
.main-font {
  letter-spacing: 5px;
  font-size: 60px;
  font-style: italic;
  color: whitesmoke;
  font-family: Caveat !important;
  text-shadow: 0px 0px 5px #000 !important;
}
.sub-font {
  font-size: 30px;
  color: whitesmoke;
  text-shadow: 0px 0px 5px #000 !important;
}
.title-container {
  width: 600px;
  margin-top: 3%;
  text-align: center;
  height: 300px;
	display: flex;
	align-items: center;
	justify-content: center;
}
.sub-container {
  display: block;
}
.title {
  font-family: HuiFontP109 !important;
  text-shadow: 0px 0px 2px #000 !important;
  display: flex;
  overflow: hidden;
  color: whitesmoke;
  font-weight: bold;
  margin-top: 5px;
}
.section-todo-title {
  color: #424242;
  text-align: center;
  margin: 20px;
}
.section-todo-container {
  color: #424242;
}
.section-todo-title p {
  display: inline;
  font-size: 40px;
  border-bottom: solid 3px #87CEFA;
}
.section-todo-h1 {
  margin: 10px;
  font-weight: bold;
  letter-spacing: 5px;
  font-style: italic;
  font-family: Caveat !important;
}
.section-todo-1 {
  background:#FFFFFF;
}
.card-title {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
  padding: 10px 10px;
  font-family: Caveat !important;
}
.section-todo-2 {
  background: #E8F5E9;
  padding-bottom: 10px;
}
</style>
