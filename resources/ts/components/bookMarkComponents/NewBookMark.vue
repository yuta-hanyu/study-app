<template>
  <div>
    <v-sheet width="600px" dark class="kokuban">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">ブックマーク登録</p>
            <v-col cols="12">
              <alert-msg class="mt-4"
                v-if="alertMsgs.length"
                :alertType=alertType
                :alertMsgs=alertMsgs>
              </alert-msg>
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
            <v-col cols="5" class="d-flex flex-column justify-space-between align-center">
              <v-checkbox
                v-model="newBookMark.fixed"
                label="上部へ固定する"
                value=1
                class="my-0 d-flex flex-column justify-space-between align-center">
              </v-checkbox>
            </v-col>
            <v-container>
              <v-row justify="center">
                <v-col
                  class="text-center"
                  cols="3">
                  <v-btn
                    class="back"
                    @click="back()">
                    戻る
                  </v-btn>
                </v-col>
                <v-col cols="3" class="text-center">
                  <v-btn
                    class="go"
                    @click="addBookMark">
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
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'NewBookMark',
  components: {
    AlertMsg,
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
      this.initialize();
    };
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // 新規登録ブックマーク情報
  private newBookMark: BookMarks = {}
  /**
   * データ初期化
   */
  private initialize(): void {
    this.newBookMark = Object.assign({}, {})
    this.msgReset();
  };
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
  }
  /**
   * リンクからタイトル取得
   */
  private getTitle(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/bookMark/getTitle', {
      newBookMark: {
        link: this.newBookMark.link
      }
    }).then((res) => {
      if(res.data.validateState === false) {
        this.alertType = 'error';
        this.alertMsgs.push(res.data.message.link[0]);
        return;
      }
      this.newBookMark.title = res.data;
      this.$forceUpdate();
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * ブックマーク登録
   */
  private addBookMark(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/bookMark',{
      newBookMark: this.newBookMark
    }).then((res) => {
      if(res.data.validateState === false) {
        this.alertType = 'error';
        for (let [key, value] of Object.entries(res.data.message)) {
          if(typeof value === 'object') {
            if(value !== undefined && value !== null){
              this.alertMsgs.push(value[0]);
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
    }).finally(() => this.closeLoading());
  }

}
</script>

<style scoped>

</style>
