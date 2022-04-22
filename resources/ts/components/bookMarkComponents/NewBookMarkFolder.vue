<template>
  <div>
    <v-sheet width="450px" dark class="kokuban">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">フォルダ登録</p>
            <v-col cols="12">
              <alert-msg class="mt-4"
                v-if="alertMsgs.length"
                :alertType=alertType
                :alertMsgs=alertMsgs>
              </alert-msg>
            </v-col>
            <v-col cols="10">
              <v-text-field
                v-model="newBookMarkFolder.title"
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
                    v-model="newBookMarkFolder.color"
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
                    class="go"
                    @click="addBookMarkFolder">
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
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../../common/const';
import Util from '../../common/util';
import ColorPicker from '../../components/utilComponent/ColorPicker.vue'
import { BookMarkFolders } from '../../interfaces/BookMarkFolders';
import Axios from 'axios';
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'NewBookMarkFolder',
  components: {
    ColorPicker,
    AlertMsg,
  },
})

export default class NewBookMarkFolder extends Mixins(Const,Util) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize();
      this.msgReset();
    };
  // 登録成功
  @Emit('folder-registered')
    folderRegistered(succueseMsg: string): void {
      this.initialize();
      this.msgReset();
    };
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // 新規登録フォルダ情報
  private newBookMarkFolder: BookMarkFolders = {}
  // カラーピッカーダイアログ
  private colorPockerDialog: boolean = false;

  mounted() {
    this.initialize();
  }
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    this.newBookMarkFolder = Object.assign({}, {})
  };
  /**
   * カラーセット
   */
  private setColor(color: string): void {
    this.newBookMarkFolder.color = color;
    this.colorPockerDialog = false;
  };
  /**
   * フォルダー登録
   */
  private addBookMarkFolder(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/bookMarkFolder',{
      newBookMarkFolder: this.newBookMarkFolder
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
      let succueseMsg = `「${this.newBookMarkFolder.title}」${this.SUCCESS_MSG.STORE_SUCCESS}`;
      this.folderRegistered(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
}
</script>

<style>

</style>
