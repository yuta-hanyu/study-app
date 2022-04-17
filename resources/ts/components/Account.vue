<template>
<v-container class="container">
<v-row justify="center" align-content="center">

  <v-card color="basil">

    <v-tabs
      v-model="tab"
      background-color="transparent"
      color="basil"
      grow
      >
      <v-tab href="#tab-1">会員情報</v-tab>
      <v-tab href="#tab-2">会員情報編集</v-tab>
      <v-tab href="#tab-3">退会</v-tab>
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
      <v-tab-item value="tab-2">
        <v-card
          width="800px"
          color="basil"
          flat
          style="padding-top: 50px;">
          <v-form>

            <v-alert
              v-for="(error, index) in this.errors" :key=index
              dense
              border="left"
              type="error"
              class="px-6 mx-auto"
              width="70%">
              {{error}}
            </v-alert>
            <v-alert
              v-if="succueseMsg"
              border="left"
              type="success"
              class="px-6 mx-auto"
              width="70%">
                {{succueseMsg}}
            </v-alert>

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
            <div class="grey--text text--lighten-1 text-h6 mb-4" align="center" style="padding-top: 50px;">
              退会すると全てのコンテンツが削除となります。<br>よろしいですか？<br>
              ※ 元には戻りません
            </div>
            <v-row justify="center">
              <v-col cols="4">
                <v-btn
                  class="ma-1"
                  color="error"
                  plain>
                  <div class="text-h6">退会</div>
                </v-btn>
              </v-col>
            </v-row>
          </v-sheet>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</v-row>
</v-container>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../common/const';
import Util from '../common/util';
import { User } from '../interfaces/User';

@Component({
  name: 'Account',
  components: {
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
  // バリデーションエラー
  private errors: string[] = [];
  // 処理成功MSG
  private succueseMsg: string = '';

  mounted(){
    this.getUserInfo();
  };
  /**
   * ユーザー情報取得
   */
  private getUserInfo(): void {
    this.setLoading();
    Axios.get(`/api/register/index`).then((res) => {
      console.log(res)
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
    this.errors = [];
    // パスワード一致チェック
    if (this.newPassword !== this.confirmPassword) {
      this.errors.push('パスワードが一致していません。');
      return;
    }
    if (!this.newPassword || !this.confirmPassword) {
      this.errors.push('パスワードを入力してください。');
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
              this.errors.push(value[0]);
            }
          }
        };
        return;
      }
      this.setSuccueseMsg();
      this.getUserInfo();
    }).catch((e) => {
      this.serverError(e);
    }).finally(() => this.closeLoading())
  };
  /**
   * 処理成功MSG表示
   */
  private setSuccueseMsg(): void {
    this.succueseMsg = '会員情報を編集しました';
    setTimeout(() => {
      this.succueseMsg = '';
    }, 10000);
  }

}
</script>

<style scoped>
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


