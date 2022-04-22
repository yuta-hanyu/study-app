<template>
  <div>
    <v-sheet width="400px" dark class="kokuban">
      <v-form>
        <v-container>
          <v-row justify="center">
            <v-col md="12" class="d-flex flex-row-reverse my-n3 py-0 pr-0">
              <v-btn
                class="font-weight-black"
                text
                color="#03A9F4"
                dark
                @click="howToImportDialog = true">
                <v-icon
                  dark
                  left>
                  mdi-open-in-new
                </v-icon>使い方
              </v-btn>
            </v-col>
            <p class="dialog-title">ブックマークインポート</p>
              <alert-msg class="mt-4"
                v-if="alertMsgs.length"
                :alertType=alertType
                :alertMsgs=alertMsgs>
              </alert-msg>
            <v-col
              cols="10">
              <v-file-input
                messages="※ インポート対象はgoogleのブックマークのみとなります"
                label="ファイルアップロード"
                dark
                show-size
                v-model="bookMarkImports">
              </v-file-input>
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
                    @click="importBookMark">
                    取込
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-row>
        </v-container>
      </v-form>
    </v-sheet>
    <!-- 使い方ダイアログ -->
      <v-dialog
        width="800px"
        v-model="howToImportDialog"
        persistent>
        <how-to-import
          @back="howToImportDialog = !howToImportDialog">
        </how-to-import>
      </v-dialog>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../../common/const';
import Util from '../../common/util';
import Axios from 'axios';
import HowToImport from './HowToImport.vue';
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'ImportBookMark',
  components: {
    HowToImport,
    AlertMsg,
  }
})

export default class ImportBookMark extends Mixins(Const, Util) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize()
    };
  @Emit('loading-Start')
    loadingStart(): void {
    };
  // 取込完了
  @Emit('import-Finished')
    importFinished(succueseMsg: string): void {
      this.initialize();
    };
  // 使い方ダイアログ
  private howToImportDialog: boolean = false;
  // ブックマークインポート
  private bookMarkImports: any = {};
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';

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
    this.bookMarkImports = {};
    this.msgReset();
  };
  /**
   * インポート開始
   */
  private importBookMark(): void {
    this.msgReset();
    // ファイルの空判定
    if(this.bookMarkImports === null || !this.bookMarkImports.name) {
      this.alertType = 'error';
      this.alertMsgs.push('ファイルが登録されていません');
      return;
    }
    // ファイルの拡張子判定
    if(!this.bookMarkImports.name.match(/\.(html)$/i)) {
      this.alertType = 'error';
      this.alertMsgs.push('htmlファイルのみ取込可能です');
      return;
    }
    // ファイル送信のためformdataオブジェクト作成
    let formData = new FormData();
    formData.append("html", this.bookMarkImports);
    this.setLoading();
    Axios.post(`/api/bookMarks/import`,formData,
      {headers: {'Content-Type': 'multipart/form-data'}}
    ).then((res) => {
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
      let succueseMsg = this.SUCCESS_MSG.IMPORT_SUCCESS;
      this.importFinished(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  };
}
</script>

<style>

</style>
