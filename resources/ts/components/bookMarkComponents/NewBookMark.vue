<template>
  <div>
    <v-sheet width="600px" dark>
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="text-h6 py-3">ブックマーク登録</p>
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
                v-model="newBookMark.link"
                @change="getTitle()"
                prepend-icon="mdi-open-in-new"
                label="リンク">
              </v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n8">
              <v-text-field
                v-model="newBookMark.title"
                prepend-icon="mdi-folder-plus-outline"
                placeholder="リンクからページタイトルを表示します"
                label="タイトル">
              </v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n3">
              <v-autocomplete
                prepend-icon="mdi-folder"
                v-model="newBookMark.book_mark_folders_id"
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
                v-model="newBookMark.memo"
                prepend-icon="mdi-book-open"
                label="メモ">
              </v-textarea>
            </v-col>
            <v-container>
              <v-row justify="center">
                <v-col
                  class="text-center"
                  cols="4">
                  <v-btn
                    color="grey lighten-1"
                    width="25%"
                    @click="back()"
                    rounded
                    elevation="20">
                    戻る
                  </v-btn>
                </v-col>
                <v-col cols="4" class="text-center">
                  <v-btn
                    @click="addBookMark"
                    width="25%"
                    color="orange lighten-2"
                    elevation="20"
                    rounded>
                    登録
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
  name: 'NewBookMark',
  components: {
  },
})

export default class NewBookMark extends Mixins(Const, Util) {
  // ブックマークフォルダ
  @Prop({type: Array, default: false})
    bookMarkFolders!: BookMarkFolders;
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize()
    };
  // 登録成功
  @Emit('bookMark-registered')
    bookMarkRegistered(succueseMsg: string): void {
      this.initialize()
    };
  // フォームバリデーションエラー
  private errors: string[] = [];
  // 新規登録ブックマーク情報
  private newBookMark: BookMarks = {
    book_mark_folders_id: null,
    title: '',
    link: '',
    memo: '',
    user_id: this.$store.state.userInfo.userId,
  }
  mounted() {
    this.initialize();
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    Object.keys(this.newBookMark).forEach(key => this.newBookMark[key] = '');
    this.newBookMark.user_id = this.$store.state.userInfo.userId;
  };
  /**
   * リンクからタイトル取得
   */
  private getTitle(): void {
    Axios.post('/api/bookMark/getTitle', {
      newBookMark: {
        link: this.newBookMark.link
      }
    }).then((res) => {
      if(res.data.validateState === false) {
        this.errors.push(res.data.message.link[0]);
        return;
      }
      this.newBookMark.title = res.data;
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    })
  }
  /**
   * ブックマーク登録
   */
  private addBookMark(): void {
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    let succueseMsg: string = '';
    Axios.post('/api/bookMark',{
      newBookMark: this.newBookMark
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
      let succueseMsg = `「${this.newBookMark.title}」${this.SUCCESS_MSG.STORE_SUCCESS}`;
      this.bookMarkRegistered(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    })
  }

}
</script>

<style scoped>

</style>
