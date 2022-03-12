<template>
  <div>
    <v-container class="my-5">
      <v-alert
        v-if="this.succueseMsg"
        align="center"
        dense
        dark
        color="green">
        {{this.succueseMsg}}
      </v-alert>
      <div class="d-flex justify-end" flat tile>
        <v-btn
          class="mx-2"
          @click="addBookMark = !addBookMark"
          color="success">
          <v-icon left>
            mdi-plus
          </v-icon>
          ブックマーク追加
        </v-btn>
        <v-btn
          class="mx-2"
          @click="addFolder = !addFolder"
          color="success">
          <v-icon left>
            mdi-plus
          </v-icon>
          フォルダー追加
        </v-btn>
      </div>
    </v-container>
    <!-- ブックマーク一覧 -->
    <v-container>
      <v-row>
        <v-col md="3"
          v-for="(bookMarkFolder,index) in bookMarkFolders" :key=index>
          <v-simple-table
            :style="{backgroundColor: bookMarkFolder.color}"
            dark
            dense
            fixed-header>
            <thead>
              <tr>
                <th class="text-left text-h6">
                  {{bookMarkFolder.title}}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(getBookMarkFolder,index) in getBookMarkFolders(bookMarkFolder.id)"
                :key="index">
                <td class="link">
                  <a :href="getBookMarkFolder.bookMarkLink" target="_blank">{{getBookMarkFolder.bookMarkTitle}}</a>
                </td>
              </tr>
            </tbody>
          </v-simple-table>
        </v-col>
      </v-row>
    </v-container>
    <!-- ブックマークフォルダー追加ダイアログ -->
    <v-dialog
      v-model="addFolder"
      persistent
      width="400px">
      <new-book-mark-folder
        @back="addFolder = !addFolder"
        @folder-registered="folderRegistered">
      </new-book-mark-folder>
    </v-dialog>
    <!-- ブックマーク追加ダイアログ -->
    <v-dialog
      v-model="addBookMark"
      persistent
      width="600px">
      <new-book-mark
        @back="addBookMark = !addBookMark">
      </new-book-mark>
    </v-dialog>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../common/const';
import { BookMarkFolders } from '../interfaces/BookMarkFolders';
import { BookMarks } from '../interfaces/BookMarks';
import NewBookMarkFolder from './bookMarkComponents/NewBookMarkFolder.vue';
import NewBookMark from './bookMarkComponents/NewBookMark.vue';

@Component({
  name: 'BookMark',
  components: {
    NewBookMarkFolder,
    NewBookMark,
  },
})

export default class BookMark extends Mixins(Const) {
  // 処理成功MSG
  private succueseMsg: string = '';
  // ブックマーク追加ダイアログ
  private addBookMark: boolean = false;
  // ブックマークフォルダー追加ダイアログ
  private addFolder: boolean = false;
  // ブックマークフォルダー
  private bookMarkFolders: BookMarkFolders[] = [];
  // ブックマーク
  private bookMarks: BookMarks[] = [];
  // ブックマーク（フォルダーと紐付け）
  get getBookMarkFolders(): any {
    return (id: number) => {
      const filterBookMarks = this.bookMarks.filter(bookMark => bookMark.book_mark_folders_id === id)
      return filterBookMarks;
    }
  }
  mounted(){
    this.getBookMarks()
  };
  /**
   * ブックマークフォルダ、ブックマーク取得
   */
  private getBookMarks(): void {
    Axios.get(`/api/bookMarks/${this.$store.state.userInfo.userId}`).then((res) => {
      this.bookMarkFolders = res.data.bookMarkFolders;
      this.bookMarks = res.data.bookMarks;
    }).catch((e) => {
      //認証エラー
      if(e.response.status === 401) {
        alert(this.ERROR_MSG.EXPAIRED_SESSION);
        this.$store.dispatch('resetUserInfo');
        this.$router.push("/login");
      };
      alert(this.ERROR_MSG.GET_ERROR);
      console.log(e);
    });
  };
  /**
   * ブックマークフォルダー新規登録完了
   */
  private folderRegistered(succueseMsg: string): void {
    console.log(succueseMsg);
    this.addFolder = false;
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 3000);
    this.getBookMarks();
  };
}
</script>

<style scoped>
  .v-application a {
    color: white;
    text-decoration: none;
  }
</style>


