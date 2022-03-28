<template>
  <div class="bg">
    <v-container>
    <v-alert
      v-if="this.succueseMsg"
      align="center"
      dense
      dark
      color="success">
      {{this.succueseMsg}}
    </v-alert>
    <v-row class="mt-4">
      <v-col md="6" sm="12">
        <v-autocomplete
          dark
          class="font-weight-black"
          @change="clickSerch()"
          v-model="serchBookMark"
          :items="bookMarks"
          item-text="title"
          item-value="link"
          label="ブックマーク検索"
          no-data-text="ヒットしません">
        </v-autocomplete>
      </v-col>
      <v-col md="6">
        <v-row justify="end">
          <v-tooltip bottom color="#FFEE58">
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="#FFEE58"
                v-bind="attrs"
                v-on="on"
                size="50"
                class="mx-2"
                @click="addBookMarkDialog = !addBookMarkDialog">
                mdi-bookmark-multiple
              </v-icon>
            </template>
            <span style="color: black;">ブックマークを追加</span>
          </v-tooltip>
          <v-tooltip bottom color="#FFEE58">
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="#FFEE58"
                v-bind="attrs"
                v-on="on"
                size="60"
                class="mx-2"
                @click="addFolderDialog = !addFolderDialog">
                mdi-folder
              </v-icon>
            </template>
            <span style="color: black;">フォルダを追加</span>
          </v-tooltip>
          <v-tooltip bottom color="#FFEE58">
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="#FFEE58"
                v-bind="attrs"
                v-on="on"
                size="60"
                class="mx-2"
                @click="importBookMarkDialog = !importBookMarkDialog">
                mdi-cloud-upload
              </v-icon>
            </template>
            <span style="color: black;">ブックマークインポート</span>
          </v-tooltip>
        </v-row>
      </v-col>
    </v-row>
    <!-- ブックマーク一覧 -->
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
              <v-tooltip bottom color="#1E1E1E">
                <template v-slot:activator="{ on, attrs }">
                  <th
                    class="text-left text-h6 font-weight-black"
                    v-on="on"
                    v-bind="attrs"
                    @click="chengeEditFolder(bookMarkFolder)">
                    {{bookMarkFolder.title}}
                  </th>
                </template>
                <span style="color: white;">フォルダを編集</span>
              </v-tooltip>
            </tr>
          </thead>
          <tbody>
            <div class="table-body">
              <tr
                v-for="(getBookMarkFolder,index) in getBookMarkFolders(bookMarkFolder.id)"
                :key="index">
                <td class="link px-4">
                  <v-tooltip bottom color="#1E1E1E">
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        v-on="on"
                        v-bind="attrs"
                        class="ml-n3"
                        dark
                        dense
                        left
                        @click="chengeEditBoookMark(getBookMarkFolder)">
                        mdi-bookmark
                      </v-icon>
                    </template>
                    <span style="color: white;">ブックマークを編集</span>
                  </v-tooltip>
                  <v-tooltip bottom color="#1E1E1E">
                  <template v-slot:activator="{ on, attrs }">
                    <a
                    :href="getBookMarkFolder.link"
                    target="_blank"
                    v-on="on"
                    v-bind="attrs">
                      {{getBookMarkFolder.title}}
                    </a>
                  </template>
                    <span style="color: white;">リンクへ遷移</span>
                  </v-tooltip>
                </td>
              </tr>
            </div>
          </tbody>
        </v-simple-table>
      </v-col>
    </v-row>
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
        ref="child"
        :editFolder=editFolder
        @back="editFolderDialog=!editFolderDialog"
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
    <!-- ブックマーク編集ダイアログ -->
    <v-dialog
      v-model="editBookMarkDialog"
      persistent
      width="600px">
      <edit-book-mark
        ref="child"
        :bookMarkFolders=bookMarkFolders
        :editBookMark=editBookMark
        @back="editBookMarkDialog=!editBookMarkDialog"
        @bookMark-edited="registered">
      </edit-book-mark>
    </v-dialog>
    <!-- ブックマークインポートダイアログ -->
    <v-dialog
      v-model="importBookMarkDialog"
      persistent
      width="400px">
      <import-book-mark
        @back="importBookMarkDialog=!importBookMarkDialog"
        @import-Finished="registered">
      </import-book-mark>
    </v-dialog>
  </v-container>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../common/const';
import Util from '../common/util';
import { BookMarkFolders } from '../interfaces/BookMarkFolders';
import { BookMarks } from '../interfaces/BookMarks';
import NewBookMarkFolder from './bookMarkComponents/NewBookMarkFolder.vue';
import EditBookMarkFolder from './bookMarkComponents/EditBookMarkFolder.vue';
import NewBookMark from './bookMarkComponents/NewBookMark.vue';
import EditBookMark from './bookMarkComponents/EditBookMark.vue';
import ImportBookMark from './bookMarkComponents/ImportBookMark.vue';
import Loading from '../global/Loading.vue'

@Component({
  name: 'BookMark',
  components: {
    NewBookMarkFolder,
    EditBookMarkFolder,
    NewBookMark,
    EditBookMark,
    ImportBookMark,
    Loading,
  },
})

export default class BookMark extends Mixins(Const, Util) {
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
  // ブックマークインポートダイアログ
  private importBookMarkDialog: boolean = false;
  // ブックマークフォルダー（編集用）
  private editFolder: BookMarkFolders | null = null;
  // ブックマーク（編集用）
  private editBookMark: BookMarks | null = null;;
  // ブックマークフォルダー（表示用）
  private bookMarkFolders: BookMarkFolders[] = [];
  // ブックマーク
  private bookMarks: BookMarks[] = [];
  // ブックマーク（検索用）
  private serchBookMark: string = '';
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
    this.setLoading();
    Axios.get(`/api/bookMarks/${this.$store.state.userInfo.userId}`).then((res) => {
      this.bookMarkFolders = res.data.bookMarkFolders;
      this.bookMarks = res.data.bookMarks;
      this.closeLoading();
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
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
    this.importBookMarkDialog = false;
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 10000);
    this.getBookMarks();
  };
  /**
   * ブックマークフォルダー編集ボタン押下
   */
  async chengeEditFolder(bookMarkFolder: BookMarkFolders): Promise<void> {
    await (
      this.editFolder = bookMarkFolder,
      this.editFolderDialog = !this.editFolderDialog
    );
    this.$refs.child.setInitializeValue();
  }
  /**
   * ブックマーク編集ボタン押下
   */
  async chengeEditBoookMark(getBookMarkFolder: BookMarks): Promise<void>  {
    await (
      this.editBookMark = getBookMarkFolder,
      this.editBookMarkDialog = !this.editBookMarkDialog
    );
    this.$refs.child.setInitializeValue();
  }
  /**
   * ブックマーク検索
   */
  private clickSerch(): void {
    window.open(this.serchBookMark, '_blank');
  }
}
</script>

<style scoped>
  .bg {
    top: 20px;
    left: 0;
    background-size: cover;
    background-image: url("../../../public/images/school.jpeg");
  }

  .v-application a {
    color: white;
    text-decoration: none;
  }
  .table-body {
    max-height: 300px;
    overflow-y: scroll;
  }
  .table th,td.link{
    border-top: 1px solid #5a5858;
    width: 800px;
  }
</style>


