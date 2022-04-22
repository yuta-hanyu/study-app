<template>
  <div>
    <v-sheet width="400px" dark class="kokuban">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">フォルダ編集</p>
              <v-col cols="12">
                <alert-msg class="mt-4"
                  v-if="alertMsgs.length"
                  :alertType=alertType
                  :alertMsgs=alertMsgs>
                </alert-msg>
            </v-col>
            <v-col cols="10">
              <v-text-field
                v-model="targetEditBookMarkFolder.title"
                prepend-icon="mdi-folder-plus-outline"
                label="タイトル">
              </v-text-field>
            </v-col>
            <v-col
              cols="10">
              <v-dialog
                width="300px"
                persistent
                v-model="colorPockerDialog">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    class="pt-0 pb-3 label-font"
                    v-model="targetEditBookMarkFolder.color"
                    label="カラー"
                    prepend-icon="mdi-palette"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <color-picker
                 @choice-color=setColor>
                </color-picker>
              </v-dialog>
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
                <v-col
                  cols="3"
                  class="text-center">
                  <v-btn
                    @click="editBookMarkFolder"
                    class="go">
                    編集
                  </v-btn>
                </v-col>
                <v-col
                  cols="3"
                  class="text-center">
                  <v-btn
                    class="delete"
                    @click="removeBookMarkFolder">
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
import { Component, Mixins, Emit, Prop } from 'vue-property-decorator';
import Const from '../../common/const';
import Util from '../../common/util';
import ColorPicker from '../../components/utilComponent/ColorPicker.vue'
import { BookMarkFolders } from '../../interfaces/BookMarkFolders';
import Axios from 'axios';
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'EditBookMarkFolder',
  components: {
    ColorPicker,
    AlertMsg,
  },
})

export default class EditBookMarkFolder extends Mixins(Const, Util) {
  // 編集対象情報
  @Prop({type: Object, default: false})
    editFolder!: BookMarkFolders;
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.msgReset();
    };
  // 登録成功
  @Emit('folder-edited')
    folderEdited(succueseMsg: string): void {
      this.msgReset();
    };
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // カラーピッカーダイアログ
  private colorPockerDialog: boolean = false;
    // ブックマークフォルダ（編集用）
  private targetEditBookMarkFolder: BookMarkFolders | null = Object.assign({}, this.editFolder);
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
  }
  /**
   * ブックマークフォルダ（編集用）データセット
   */
  private setInitializeValue(): void {
    this.targetEditBookMarkFolder = Object.assign({}, this.editFolder)
  }
  /**
   * カラーセット
   */
  private setColor(color: string): void {
    this.targetEditBookMarkFolder!.color = color;
    this.colorPockerDialog = false;
  };
  /**
   * フォルダー編集
   */
  private editBookMarkFolder(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/editBookMarkFolder',{
      editFolder: this.targetEditBookMarkFolder!,
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
      let succueseMsg = `「${this.targetEditBookMarkFolder!.title}」${this.SUCCESS_MSG.EDIT_SUCCESS}`;
      this.folderEdited(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * フォルダー削除
   */
  private removeBookMarkFolder(): void {
    this.msgReset();
    if(!window.confirm(`「${this.editFolder.title}」${this.CONFIRM_MSG.DELETE}`)) {
      return;
    };
    this.setLoading();
    Axios.post('/api/removeBookMarkFolder',{
      editFolder: this.editFolder,
      user_id: this.$store.state.userInfo.user_id
    }).then((res) => {
      let succueseMsg = `「${this.editFolder.title}」${this.SUCCESS_MSG.DELETE_SUCCESS}`;
      this.folderEdited(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
}
</script>

<style>

</style>
