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
          width="800px" height="400px"
          color="basil"
          flat
          style="padding-top: 100px;">
          <v-form>
              <v-row justify="center">
                <v-col cols="10">
                    <v-row justify="center">
                      <v-col cols="10">
                        <v-text-field
                          readonly
                          v-model="userInfo.name"
                          prepend-icon="mdi-email"
                          label="お名前">
                        </v-text-field>
                      </v-col>
                      <v-col cols="10">
                        <v-text-field
                          readonly
                          v-model="userInfo.email"
                          prepend-icon="mdi-email"
                          label="メールアドレス">
                        </v-text-field>
                      </v-col>
                    </v-row>
                </v-col>
              </v-row>
          </v-form>
        </v-card>
      </v-tab-item>
      <!-- 会員情報編集 -->
      <v-tab-item value="tab-2">
        <v-card
          width="800px" height="600px"
          color="basil"
          flat
          style="padding-top: 50px;">
          <v-form>
              <v-row justify="center">
                <v-col cols="10">
                    <v-row justify="center">
                      <v-col cols="10">
                        <v-text-field
                          prepend-icon="mdi-email"
                          label="お名前">
                        </v-text-field>
                      </v-col>
                      <v-col cols="10">
                        <v-text-field
                          prepend-icon="mdi-email"
                          label="メールアドレス">
                        </v-text-field>
                      </v-col>
                      <v-col cols="10">
                        <v-text-field
                          dark
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
                        v-model="userInfo.password"
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
                    <v-col cols="6" class="text-center">
                      <v-btn
                        class="go m-5"
                        width="200px">
                        本登録
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
import { BookMarkFolders } from '../interfaces/BookMarkFolders';
import { BookMarks } from '../interfaces/BookMarks';
import NewBookMarkFolder from './bookMarkComponents/NewBookMarkFolder.vue';
import EditBookMarkFolder from './bookMarkComponents/EditBookMarkFolder.vue';
import NewBookMark from './bookMarkComponents/NewBookMark.vue';
import EditBookMark from './bookMarkComponents/EditBookMark.vue';
import ImportBookMark from './bookMarkComponents/ImportBookMark.vue';
import Loading from '../global/Loading.vue';

@Component({
  name: 'Account',
  components: {
  },
})

export default class Account extends Mixins(Const, Util) {
  // パスワード表示非表示フラグ
  private showPassword: boolean = false;
  // タブ管理
  private tab: null = null;
  // ログインユーザー情報
  private userInfo: User = {};
    // 確認用パスワード
  private confirmPassword: string = '';
  // private items:any = ['会員情報', '会員情報編集', '退会',];
  // $refs: any = {}
  // // 処理成功MSG
  // private succueseMsg: string = '';
  // // ブックマーク追加ダイアログ
  // private addBookMarkDialog: boolean = false;
  // // ブックマークフォルダー追加ダイアログ
  // private addFolderDialog: boolean = false;
  // // ブックマークフォルダー編集ダイアログ
  // private editFolderDialog: boolean = false;

  mounted(){
    this.getUserInfo();
  };
  /**
   * ブックマークフォルダ、ブックマーク取得
   */
  private getUserInfo(): void {
    this.setLoading();
    Axios.get(`/api/register/index`).then((res) => {
      console.log(res)
      this.userInfo = Object.assign({}, res.data.userInfo);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  };
  // /**
  //  * ブックマーク/フォルダー新規登録・編集完了
  //  */
  // private registered(succueseMsg: string): void {
  //   this.addBookMarkDialog = false;
  //   this.addFolderDialog = false;
  //   this.editFolderDialog = false;
  //   this.editBookMarkDialog = false;
  //   this.importBookMarkDialog = false;
  //   this.succueseMsg = succueseMsg;
  //   setTimeout(() => {
  //     this.succueseMsg = '';
  //   }, 10000);
  //   this.getBookMarks();
  // };
  // /**
  //  * ブックマークフォルダー編集ボタン押下
  //  */
  // async chengeEditFolder(bookMarkFolder: BookMarkFolders): Promise<void> {
  //   await (
  //     this.editFolder = bookMarkFolder,
  //     this.editFolderDialog = !this.editFolderDialog
  //   );
  //   this.$refs.child.setInitializeValue();
  // }
  // /**
  //  * ブックマーク編集ボタン押下
  //  */
  // async chengeEditBoookMark(getBookMarkFolder: BookMarks): Promise<void>  {
  //   await (
  //     this.editBookMark = getBookMarkFolder,
  //     this.editBookMarkDialog = !this.editBookMarkDialog
  //   );
  //   this.$refs.child.setInitializeValue();
  // }
  // /**
  //  * ブックマーク検索
  //  */
  // private clickSerch(): void {
  //   window.open(this.serchBookMark, '_blank');
  // }
}
</script>

<style scoped>
/* .bg {
  padding-top: 3%;
  left: 0;
  min-height: 1200px;
  background-size: contain;
  background: url("../../../public/images/school.jpeg") center center / cover no-repeat fixed;
  background-attachment: fixed;
} */
/* .v-application a {
  text-decoration: none;
} */
.v-tab {
  font-weight: bold !important;
  font-size: 20px;
  color: #356859;
}
.basil {
  background-color: #FFFBE6 !important;
}
.container {
  padding: 3% 3%;
  margin-top: 50px;
}

</style>


