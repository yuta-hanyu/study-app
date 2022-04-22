<template>
  <div>
    <v-sheet width="600px" dark class="kokuban">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">ブックマーク編集</p>
            <v-col cols="12">
              <alert-msg class="mt-4"
                v-if="alertMsgs.length"
                :alertType=alertType
                :alertMsgs=alertMsgs>
              </alert-msg>
            </v-col>
            <v-col cols="10">
              <v-text-field
                v-model="targetEditBookMark.link"
                @change="getTitle()"
                prepend-icon="mdi-open-in-new"
                label="リンク">
              </v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n8">
              <v-text-field
                v-model="targetEditBookMark.title"
                prepend-icon="mdi-folder-plus-outline"
                placeholder="リンクからページタイトルを表示します"
                label="タイトル">
              </v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n3">
              <v-autocomplete
                prepend-icon="mdi-folder"
                v-model="targetEditBookMark.book_mark_folders_id"
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
                v-model="targetEditBookMark.memo"
                prepend-icon="mdi-book-open"
                label="メモ">
              </v-textarea>
            </v-col>
            <v-col cols="5" class="d-flex flex-column justify-space-between align-center">
              <v-checkbox
                v-model="targetEditBookMark.fixed"
                label="上部へ固定する"
                :value=1
                class="my-0 d-flex flex-column justify-space-between align-center">
              </v-checkbox>
            </v-col>
            <v-container>
              <v-row justify="center">
                <v-col
                  class="text-center"
                  cols="2">
                  <v-btn
                    class="back"
                    @click="back()">
                    戻る
                  </v-btn>
                </v-col>
                <v-col cols="2" class="text-center">
                  <v-btn
                    class="go"
                    @click="editToBookMark">
                    編集
                  </v-btn>
                </v-col>
                <v-col cols="2" class="text-center">
                  <v-btn
                    class="delete"
                    @click="removeBookMark">
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
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'EditBookMark',
  components: {
    AlertMsg,
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
      this.msgReset();
    };
  // 登録成功
  @Emit('bookMark-edited')
    bookMarkEdited(succueseMsg: string): void {
    };
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // ブックマーク（編集用）
  private targetEditBookMark: BookMarks | null = Object.assign({}, this.editBookMark);
  /**
   * ブックマーク（編集用）データセット
   */
  private setInitializeValue(): void {
    this.targetEditBookMark = Object.assign({}, this.editBookMark)
  }
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
      // 新規登録時とロジックを共有するためkeyはnewBookMarkとする
      newBookMark: {
        link: this.targetEditBookMark!.link
      }
    }).then((res) => {
      if(res.data.validateState === false) {
        this.alertType = 'error';
        this.alertMsgs.push(res.data.message.link[0]);
        return;
      }
      this.targetEditBookMark!.title = res.data;
      this.$forceUpdate(); //強制的にDOM更新（変更がDOMに反映されないため）
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * ブックマーク編集
   */
  private editToBookMark(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/editBookMark',{
      editBookMark: this.targetEditBookMark,
      user_id: this.$store.state.userInfo.user_id
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
      let succueseMsg = `「${this.targetEditBookMark!.title}」${this.SUCCESS_MSG.EDIT_SUCCESS}`;
      this.bookMarkEdited(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading())
  }
  /**
   * ブックマーク削除
   */
  private removeBookMark(): void {
    this.msgReset();
    if(!window.confirm(`「${this.editBookMark.title}」${this.CONFIRM_MSG.DELETE}`)) {
      return;
    };
    this.setLoading();
    Axios.post('/api/removeBookMark',{
      editBookMark: this.editBookMark,
      user_id: this.$store.state.userInfo.user_id
    }).then((res) => {
      let succueseMsg = `「${this.editBookMark.title}」${this.SUCCESS_MSG.DELETE_SUCCESS}`;
      this.bookMarkEdited(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading())
  }

}
</script>

<style scoped>

</style>
