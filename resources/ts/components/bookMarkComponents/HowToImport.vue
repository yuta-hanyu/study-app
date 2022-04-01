<template>
  <div>
    <v-sheet dark width="800px" class="kokuban">
      <v-row justify="center" class="px-2">
        <v-col md=12 class="text-center">
          <p class="dialog-title">使い方</p>
        </v-col>
        <v-col md=12>
          <dd>１．以下のサイトを参考にgoogleからブックマークをエクスポートしてください</dd>
          <a href="https://pc-karuma.net/google-chrome-export-import-bookmarks/" target=”_blank”>Chrome | ブックマークをエクスポート・インポートする方法 | PC設定のカルマ</a>
        </v-col>
        <v-col md=12>
          <dd>２．エクスポートされたhtmlファイルをアップロードし、登録ボタンを押下してください</dd>
          <div class="d-flex flex-column justify-space-between align-center pt-1">
            <v-img
              d-flex flex-column justify-space-between align-center
              style="border: 3px solid white;"
              max-height="500"
              max-width="700"
              src="/images/example1.png">
            </v-img>
          </div>
        </v-col>
        <v-col md=12>
          <dd>３．以下のメッセージが表示されるとインポートは完了です</dd>
            <div class="d-flex flex-column justify-space-between align-center pt-1">
              <v-img
                style="border: 3px solid white;"
                max-height="200"
                max-width="700"
                src="/images/example2.png">
              </v-img>
            </div>
        </v-col>
        <v-col
          class="text-center"
          md="12">
          <v-btn
            class="back"
            @click="back()">
            戻る
          </v-btn>
        </v-col>
      </v-row>
  </v-sheet>
  </div>
</template>

<script lang="ts">
import {Component, Emit} from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: 'HowToImport'
})

export default class HowToImport extends Vue {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
    };
}
</script>

<style>

</style>
