<template>
  <div>
    <v-container class="my-5">
      <v-alert
        v-if="this.succueseMsg"
        align="center"
        dense
        dark
        color="success">
        {{this.succueseMsg}}
      </v-alert>
      <div class="d-flex justify-end" flat tile>
        <v-btn
          class="mx-2 font-weight-black"
          @click="addBookMarkDialog = !addBookMarkDialog"
          color="primary">
          <v-icon left>
            mdi-plus
          </v-icon>
          ブックマーク追加
        </v-btn>
        <v-btn
          class="mx-2 font-weight-black"
          @click="addFolderDialog = !addFolderDialog"
          color="primary">
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
        <v-col md="4" sm="6"
          v-for="(bookMarkFolder,index) in bookMarkFolders" :key=index>
          <v-simple-table  class="table"
            :style="{backgroundColor: bookMarkFolder.color}"
            dark
            dense
            fixed-header
            items-per-page="1">
            <thead>
              <tr>
                <th class="text-left text-h6">
                  <v-icon
                  class="ml-n3"
                  dark
                  dense
                  @click="chengeEditFolder(bookMarkFolder)"
                  left>
                    mdi-circle-edit-outline
                  </v-icon>
                  {{bookMarkFolder.title}}
                </th>
              </tr>
            </thead>
            <tbody>
              <div class="table-body">
                <tr
                  v-for="(getBookMarkFolder,index) in getBookMarkFolders(bookMarkFolder.id)"
                  :key="index">
                  <td class="link px-4">
                    <v-icon
                    class="ml-n3"
                    dark
                    dense
                    left
                    @click="chengeEditBoookMark(getBookMarkFolder)">
                      mdi-file
                    </v-icon>
                    <a :href="getBookMarkFolder.link" target="_blank">{{getBookMarkFolder.title}}</a>
                  </td>
                </tr>
              </div>
            </tbody>
          </v-simple-table>
        </v-col>
      </v-row>
    </v-container>
    <!-- ブックマークフォルダー追加ダイアログ -->
    <v-dialog
      v-model="addFolderDialog"
      persistent
      width="400px">
      <new-book-mark-folder
        @back="addFolderDialog=!addFolderDialog"
        @folder-registered="registered">
      </new-book-mark-folder>
    </v-dialog>
    <!-- ブックマークフォルダー編集ダイアログ -->
    <v-dialog
      v-model="editFolderDialog"
      persistent
      width="400px">
      <edit-book-mark-folder
        :editFolder=editFolder
        @back="editFolderDialog=!editFolderDialog,getBookMarks()"
        @folder-edited="registered">
      </edit-book-mark-folder>
    </v-dialog>
    <!-- ブックマーク追加ダイアログ -->
    <v-dialog
      v-model="addBookMarkDialog"
      persistent
      width="600px">
      <new-book-mark
        :bookMarkFolders=bookMarkFolders
        @back="addBookMarkDialog=!addBookMarkDialog"
        @bookMark-registered="registered">
      </new-book-mark>
    </v-dialog>
    <!-- ブックマークフォルダー編集ダイアログ -->
    <v-dialog
      v-model="editBookMarkDialog"
      persistent
      width="600px">
      <edit-book-mark
        :bookMarkFolders=bookMarkFolders
        :editBookMark=editBookMark
        @back="editBookMarkDialog=!editBookMarkDialog,getBookMarks()"
        @bookMark-edited="registered">>
      </edit-book-mark>
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
import EditBookMarkFolder from './bookMarkComponents/EditBookMarkFolder.vue';
import NewBookMark from './bookMarkComponents/NewBookMark.vue';
import EditBookMark from './bookMarkComponents/EditBookMark.vue';

@Component({
  name: 'BookMark',
  components: {
    NewBookMarkFolder,
    EditBookMarkFolder,
    NewBookMark,
    EditBookMark,
  },
})

export default class BookMark extends Mixins(Const) {
  $refs: any = {}
  // 処理成功MSG
  private succueseMsg: string = '';
  // ブックマーク追加ダイアログ
  private addBookMarkDialog: boolean = false;
  // ブックマークフォルダー追加ダイアログ
  private addFolderDialog: boolean = false;
  // ブックマークフォルダー編集ダイアログ
  private editFolderDialog: boolean = false;
  // ブックマーク編集ダイアログ
  private editBookMarkDialog: boolean = false;
  // ブックマークフォルダー（編集用）
  private editFolder: BookMarkFolders | null = null;
  // ブックマーク（編集用）
  private editBookMark: BookMarks | null = null;;
  // ブックマークフォルダー（表示用）
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
      if(e.response.status === 500) {
        window.alert(this.ERROR_MSG.SERVER_ERROR);
      };
    });
  };
  /**
   * ブックマーク/フォルダー新規登録完了
   */
  private registered(succueseMsg: string): void {
    this.addBookMarkDialog = false;
    this.addFolderDialog = false;
    this.editFolderDialog = false;
    this.editBookMarkDialog = false;
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 3000);
    this.getBookMarks();
  };
  /**
   * ブックマークフォルダー編集ボタン押下
   */
  private chengeEditFolder(bookMarkFolder: BookMarkFolders): void {
    this.editFolder = bookMarkFolder;
    this.editFolderDialog = !this.editFolderDialog;
  }
  /**
   * ブックマーク編集ボタン押下
   */
  private chengeEditBoookMark(getBookMarkFolder: BookMarks): void {
    this.editBookMark = getBookMarkFolder;
    this.editBookMarkDialog = !this.editBookMarkDialog;
  }
}
</script>

<style scoped>
  .v-application a {
    color: white;
    text-decoration: none;
  }
  .table-body {
    max-height: 300px;
    overflow-y: scroll;
  }
  ::-webkit-scrollbar {
    width: 5px;
    height: 10px;
  }
  ::-webkit-scrollbar-thumb {
    background: #1E1E1E;
  }
  .table th,td{
    border: 1px solid #1E1E1E;
  }
</style>


