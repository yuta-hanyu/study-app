<template>
  <div class="bg">
    <v-container class="container">
      <v-row justify="center" align-content="center">
        <v-card color="basil">
          <v-tabs
            v-model="tab"
            background-color="transparent"
            color="basil"
            grow>
            <v-tab @click="alertMsgs = []" href="#tab-1">会員情報</v-tab>
            <v-tab @click="alertMsgs = []" href="#tab-2">会員情報編集</v-tab>
            <v-tab @click="alertMsgs = []" href="#tab-3">退会</v-tab>
            <v-tabs-slider color="orange" />
          </v-tabs>
          <v-tabs-items v-model="tab">
            <!-- 会員情報 -->
            <v-tab-item value="tab-1">
              <v-card
                width="800px"
                color="basil"
                flat>
                  <v-row justify="center">
                    <v-col cols="8" style="margin-top: 50px;">
                      <v-text-field
                        readonly
                        v-model="userInfo.name"
                        prepend-icon="mdi-account"
                        label="お名前">
                      </v-text-field>
                    </v-col>
                    <v-col cols="8" style="margin-bottom: 50px;">
                      <v-text-field
                        readonly
                        v-model="userInfo.email"
                        prepend-icon="mdi-email"
                        label="メールアドレス">
                      </v-text-field>
                    </v-col>
                  </v-row>
              </v-card>
            </v-tab-item>
            <!-- 会員情報編集 -->
            <v-tab-item value="tab-2" v-if="editUserInfo">
              <v-card
                width="800px"
                color="basil"
                flat
                style="padding-top: 50px;">
                <v-form>
                  <alert-msg
                    v-if="alertMsgs.length"
                    :alertType=alertType
                    :alertMsgs=alertMsgs>
                  </alert-msg>
                  <v-row justify="center">
                      <v-col cols="10">
                        <v-row justify="center">
                          <v-col cols="10">
                            <v-text-field
                              v-model="editUserInfo.name"
                              prepend-icon="mdi-account"
                              label="お名前">
                            </v-text-field>
                          </v-col>
                          <v-col cols="10">
                            <v-text-field
                              v-model="editUserInfo.email"
                              prepend-icon="mdi-email"
                              label="メールアドレス">
                            </v-text-field>
                          </v-col>
                          <v-col cols="10">
                            <v-text-field
                              v-model="oldPassword"
                              :type="showOldPassword ? 'text' : 'password'"
                              @click:append="showOldPassword = !showOldPassword"
                              :append-icon="showOldPassword ? 'mdi-eye' : 'mdi-eye-off'"
                              :counter="8"
                              label="今のパスワード"
                              required
                              prepend-icon="mdi-lock"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="5">
                            <v-text-field
                              v-model="newPassword"
                              :type="showPassword ? 'text' : 'password'"
                              @click:append="showPassword = !showPassword"
                              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                              :counter="8"
                              label="新しいパスワード"
                              required
                              prepend-icon="mdi-lock"
                            ></v-text-field>
                          </v-col>
                        <v-col cols="5">
                          <v-text-field
                            v-model="confirmPassword"
                            :type="confirmShowPassword ? 'text' : 'password'"
                            @click:append="confirmShowPassword = !confirmShowPassword"
                            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            :counter="8"
                            label="新しいパスワード確認用"
                            required
                            prepend-icon="mdi-lock-outline"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="10" class="text-center py-10">
                          <v-btn
                            dark
                            @click="editUser()"
                            class="go"
                            width="200px">
                            編集
                          </v-btn>
                        </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                </v-form>
              </v-card>
            </v-tab-item>
            <!-- 退会 -->
            <v-tab-item value="tab-3">
                <v-sheet
                  class="px-7 pt-7 pb-4 mx-auto text-center d-inline-block"
                  color="black"
                  dark width="800px" height="300px">
                  <alert-msg
                    v-if="alertMsgs.length"
                    :alertType=alertType
                    :alertMsgs=alertMsgs>
                  </alert-msg>
                  <div class="grey--text text--lighten-1 text-h6 mb-4" align="center" style="padding-top: 50px;">
                    退会すると全てのコンテンツが削除となります。<br>よろしいですか？<br>
                    ※ 元には戻りません
                  </div>
                  <v-row justify="center">
                    <v-col cols="4">
                      <v-btn
                        class="ma-1"
                        color="error"
                        plain
                        @click="removeUserConfirm()">
                        <div class="text-h6">退会</div>
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-sheet>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-row>
    <!-- アラートダイアログ -->
      <v-dialog
        width="600px"
        v-model="AlertDialog">
        <alert
          @back="AlertDialog = !AlertDialog, removeUser()">
          <template #title>再確認</template>
          <template #content>退会でお間違いないですか？</template>
        </alert>
      </v-dialog>
    <!-- 退会完了ダイアログ -->
      <v-dialog
        width="400px"
        v-model="completeDialog"
        persistent>
        <complete
          @back="completeDialog = !completeDialog, backLogin()">
          <template #title>退会しました</template>
        </complete>
      </v-dialog>
    </v-container>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../common/const';
import Util from '../common/util';
import { User } from '../interfaces/User';
import AlertMsg from '../components/utilComponent/AlertMsg.vue';
import Complete from './utilComponent/Complete.vue';
import Alert from './utilComponent/Alert.vue';

@Component({
  name: 'Account',
  components: {
    Alert,
    AlertMsg,
    Complete
  },
})

export default class Account extends Mixins(Const, Util) {
  // 現パスワード表示非表示フラグ
  private showOldPassword: boolean = false;
  // パスワード表示非表示フラグ
  private showPassword: boolean = false;
  // 確認用パスワード表示非表示フラグ
  private confirmShowPassword: boolean = false;
  // 現パスワード
  private oldPassword: string = '';
  // 新パスワード
  private newPassword: string = '';
  // 確認用パスワード
  private confirmPassword: string = '';
  // タブ管理
  private tab: null = null;
  // ログインユーザー情報
  private userInfo: User = {};
  // 編集用ログインユーザー情報
  private editUserInfo: User | null = null;
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // 処理成功MSG
  private succueseMsg: string = '';
  // 完了ダイアログ
  private completeDialog: boolean = false;
  // アラートダイアログ
  private AlertDialog: boolean = false;

  mounted(){
    this.getUserInfo();
  };
  /**
   * ユーザー情報取得
   */
  private getUserInfo(): void {
    this.setLoading();
    Axios.get(`/api/register/index`).then((res) => {
      this.userInfo = Object.assign({}, res.data.userInfo);
      this.editUserInfo = Object.assign({}, this.userInfo);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  };
  /**
   * 会員情報編集
   */
  private editUser(): void {
    // エラーMSGリセット
    this.alertMsgs = [];
    this.alertType = '';
    // パスワード一致チェック
    if (this.newPassword !== this.confirmPassword) {
      this.alertType = 'error';
      this.alertMsgs.push('パスワードが一致していません。');
      return;
    }
    if (!this.newPassword || !this.confirmPassword) {
      this.alertType = 'error';
      this.alertMsgs.push('パスワードを入力してください。');
      return;
    }
    if(!window.confirm(`ご入力いただいた内容で会員情報を編集しますか？`)) {
      return;
    };
    this.setLoading();
    Axios.post('/api/register/edit_user',{
      editUserInfo: this.editUserInfo,
      oldPassword: this.oldPassword,
      newPassword: this.newPassword
    }).then((res) => {
      if(res.data.validateState === false) {
        for (let [key, value] of Object.entries(res.data.message)) {
          if(typeof value === 'object') {
            if(value !== undefined && value !== null){
              this.alertType = 'error';
              this.alertMsgs.push(value[0]);
            }
          }
        };
        return;
      }
      this.alertType = 'success';
      this.alertMsgs.push('会員情報を編集しました');
      this.setSuccueseMsg();
      this.getUserInfo();
    }).catch((e) => {
      this.serverError(e);
      this.authCheck(e);
    }).finally(() => this.closeLoading())
  };

  private removeUserConfirm(): void {
    this.AlertDialog = !this.AlertDialog;
  }
  /**
   * 退会
   */
   private removeUser(): void {
    // エラーMSGリセット
    this.alertMsgs = [];
    this.alertType = '';
    this.setLoading();
    Axios.delete('/api/register/remove_user',).then((res) => {
      if(res.data.validateState === false) {
        for (let [key, value] of Object.entries(res.data.message)) {
          if(typeof value === 'object') {
            if(value !== undefined && value !== null){
              this.alertType = 'error';
              this.alertMsgs.push(value[0]);
            }
          }
        };
        return;
      }
      this.$store.dispatch('resetUserInfo');
      this.completeDialog = !this.completeDialog;
    }).catch((e) => {
      this.serverError(e);
      this.authCheck(e);
    }).finally(() => this.closeLoading());
  };
  /**
   * 処理成功MSG表示
   */
  private setSuccueseMsg(): void {
    setTimeout(() => {
      this.succueseMsg = '';
    }, 10000);
  }
  /**
   * ログイン画面へ戻る
   */
  private backLogin(): void {
    this.$router.push("/login");
  };
}
</script>

<style scoped>
.bg {
  padding-top: 3%;
  left: 0;
  min-height: 1200px;
  background-size: contain;
  background: url("../../../public/images/account.jpeg") center center / cover no-repeat fixed;
  background-attachment: fixed;
}
.v-tab {
  font-weight: bold !important;
  font-size: 20px;
  color: #356859;
}
.basil {
  background-color: #FFFBE6 !important;
}
.container {
  padding: 10% 3%;
  margin-top: 50px;
}
</style>


