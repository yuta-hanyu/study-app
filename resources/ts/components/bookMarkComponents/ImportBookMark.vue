<template>
  <div>
    <v-sheet width="400px" dark class="kokuban">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">ブックマークインポート</p>
              <v-alert
                v-for="(error, index) in this.errors" :key=index
                dense
                text
                border="left"
                type="error"
                class="px-6"
                width="70%">
                {{error}}
              </v-alert>
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
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../../common/const';
import Util from '../../common/util';

import Loading from '../../global/Loading.vue'
import Axios from 'axios';

@Component({
  name: 'ImportBookMark',
  components: {
    Loading,
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
      this.initialize()
    };
  // ブックマークインポート
  private bookMarkImports: any = {};
  // フォームバリデーションエラー
  private errors: string[] = [];
  mounted() {
    this.initialize();
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    this.bookMarkImports = {};
    this.errors = [];
  };
  /**
   * インポート開始
   */
  private importBookMark(): void {
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    let succueseMsg: string = '';
    // ファイルの空判定
    if(this.bookMarkImports === null || !this.bookMarkImports.name) {
      this.errors.push('ファイルが登録されていません');
      return;
    }
    // ファイルの拡張子判定
    if(!this.bookMarkImports.name.match(/\.(html)$/i)) {
      this.errors.push('htmlファイルのみ取込可能です');
      return;
    }
    // ファイル送信のためformdataオブジェクト作成
    let formData = new FormData();
    formData.append("html", this.bookMarkImports);
    this.setLoading();
    Axios.post(`/api/bookMarks/import`,formData,
      {headers: {'Content-Type': 'multipart/form-data'}}
    ).then((res) => {
      this.closeLoading();
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
      let succueseMsg = this.SUCCESS_MSG.IMPORT_SUCCESS;
      this.importFinished(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    });
  };
}
</script>

<style>

</style>
