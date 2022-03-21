<template>
  <div>
    <v-sheet width="600px" dark>
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="text-h6 py-3">ブックマーク編集</p>
            <v-col cols="10">
                <v-alert
                  v-for="(error, index) in this.errors" :key=index
                  dense
                  text
                  border="left"
                  type="error"
                  class="px-6 mx-auto"
                  width="70%">
                  {{error}}
                </v-alert>
            </v-col>
            <v-col cols="10">
              <v-text-field
                v-model="editBookMark.link"
                @change="getTitle()"
                prepend-icon="mdi-open-in-new"
                label="リンク">
              </v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n8">
              <v-text-field
                v-model="editBookMark.title"
                prepend-icon="mdi-folder-plus-outline"
                placeholder="リンクからページタイトルを表示します"
                label="タイトル">
              </v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n3">
              <v-autocomplete
                prepend-icon="mdi-folder"
                v-model="editBookMark.book_mark_folders_id"
                :items="bookMarkFolders"
                item-text="title"
                item-value="id"
                label="フォルダ選択"
                dark
                item-color="primary"
                no-data-text="選択されたフォルダはありません"
                dense>
              </v-autocomplete>
            </v-col>
            <v-col cols="10" class="mt-n8">
              <v-textarea
                v-model="editBookMark.memo"
                prepend-icon="mdi-book-open"
                label="メモ">
              </v-textarea>
            </v-col>
            <v-container>
              <v-row justify="center">
                <v-col
                  class="text-center"
                  cols="2">
                  <v-btn
                    class="font-weight-black"
                    color="grey lighten-1"
                    width="25%"
                    @click="back()"
                    rounded
                    elevation="20">
                    戻る
                  </v-btn>
                </v-col>
                <v-col cols="2" class="text-center">
                  <v-btn
                    class="font-weight-black"
                    @click="editToBookMark"
                    width="25%"
                    color="orange lighten-2"
                    elevation="20"
                    rounded>
                    編集
                  </v-btn>
                </v-col>
                <v-col cols="2" class="text-center">
                  <v-btn
                    class="font-weight-black"
                    @click="removeBookMark"
                    width="25%"
                    color="red darken-1"
                    elevation="20"
                    rounded>
                    削除
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-row>
        </v-container>
      </v-form>
    </v-sheet>
  </div>
</template>

<script lang="ts">
import { Component,Mixins,Emit,Prop } from 'vue-property-decorator';
import Const from '../../common/const';
import Util from '../../common/util';
import { BookMarks } from '../../interfaces/BookMarks';
import { BookMarkFolders } from '../../interfaces/BookMarkFolders';
import Axios from 'axios';

@Component({
  name: 'EditBookMark',
  components: {
  },
})

export default class EditBookMark extends Mixins(Const, Util) {
  // ブックマーク
  @Prop({type: Object, default: false})
    editBookMark!: BookMarks;
  // ブックマークフォルダ
  @Prop({type: Array, default: false})
    bookMarkFolders!: BookMarkFolders;
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      // エラーMSGリセット
      this.errors = [];
    };
  // 登録成功
  @Emit('bookMark-edited')
    bookMarkEdited(succueseMsg: string): void {
    };
  // フォームバリデーションエラー
  private errors: string[] = [];
  /**
   * リンクからタイトル取得
   */
  private getTitle(): void {
    Axios.post('/api/bookMark/getTitle', {
      // 新規登録時とロジックを共有するためkeyはnewBookMarkとする
      newBookMark: {
        link: this.editBookMark.link
      }
    }).then((res) => {
      if(res.data.validateState === false) {
        this.errors.push(res.data.message.link[0]);
        return;
      }
      this.editBookMark.title = res.data;
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    })
  }
  /**
   * ブックマーク編集
   */
  private editToBookMark(): void {
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    let succueseMsg: string = '';
    Axios.post('/api/editBookMark',{
      editBookMark: this.editBookMark,
      user_id: this.$store.state.userInfo.userId
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
      let succueseMsg = `「${this.editBookMark.title}」${this.SUCCESS_MSG.EDIT_SUCCESS}`;
      this.bookMarkEdited(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    })
  }
  /**
   * ブックマーク削除
   */
  private removeBookMark(): void {
    if(!window.confirm(`「${this.editBookMark.title}」${this.CONFIRM_MSG.DELETE}`)) {
      return;
    };
    Axios.post('/api/removeBookMark',{
      editBookMark: this.editBookMark,
      user_id: this.$store.state.userInfo.userId
    }).then((res) => {
      let succueseMsg = `「${this.editBookMark.title}」${this.SUCCESS_MSG.DELETE_SUCCESS}`;
      this.bookMarkEdited(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    })
  }

}
</script>

<style scoped>

</style>
