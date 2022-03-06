<template>
  <div>
    <v-sheet width="400px" dark>
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="text-h6 py-3">フォルダ登録</p>
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
                <v-col
                  cols="4"
                  class="text-center">
                  <v-btn
                    @click="addBookMarkFolder"
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
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../../common/const';
import ColorPicker from '../../components/utilComponent/ColorPicker.vue'
import { BookMarkFolders } from '../../interfaces/BookMarkFolders';
import Axios from 'axios';

@Component({
  name: 'NewBookMarkFolder',
  components: {
    ColorPicker,
  },
})

export default class NewBookMarkFolder extends Mixins(Const) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize()
    };
  // 登録成功
  @Emit('folder-registered')
    folderRegistered(succueseMsg: string): void {
      console.log(succueseMsg);
      this.initialize()
    };
  // 新規登録フォルダ情報
  private newBookMarkFolder: BookMarkFolders = {
    title: '',
    color: '',
    user_id: this.$store.state.userInfo.userId,
  }
  // カラーピッカーダイアログ
  private colorPockerDialog: boolean = false;
  // フォームバリデーションエラー
  private errors: string[] = [];

  mounted() {
    this.initialize();
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    Object.keys(this.newBookMarkFolder).forEach(key => this.newBookMarkFolder[key] = '');
    this.newBookMarkFolder.user_id = this.$store.state.userInfo.userId;
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
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    let succueseMsg: string = '';
    Axios.post('/api/bookMarkFolder',{
      newBookMarkFolder: this.newBookMarkFolder
    }).then((res) => {
      if(res.data.validateState === false) {
        for (let [key, value] of Object.entries(res.data.message)) {
          this.errors.push(value[0]);
        };
        return;
      }
      let succueseMsg = `「${this.newBookMarkFolder.title}」${this.SUCCESS_MSG.STORE_SUCCESS}`;
      this.folderRegistered(succueseMsg);
    }).catch((e) => {
      console.log(e);
      window.alert(this.ERROR_MSG.UPDATE_ERROR);
    })
  }
}
</script>

<style>

</style>
