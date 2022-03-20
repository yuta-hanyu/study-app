<template>
  <div>
    <v-sheet width="400px" dark>
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="text-h6 py-3">フォルダ編集</p>
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
                v-model="editFolder.title"
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
                    v-model="editFolder.color"
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
                   @click="editBookMarkFolder"
                    width="25%"
                    color="orange lighten-2"
                    elevation="20"
                    rounded>
                    編集
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
import {Component, Mixins, Emit,Prop} from 'vue-property-decorator';
import Const from '../../common/const';
import ColorPicker from '../../components/utilComponent/ColorPicker.vue'
import { BookMarkFolders } from '../../interfaces/BookMarkFolders';
import Axios from 'axios';

@Component({
  name: 'EditBookMarkFolder',
  components: {
    ColorPicker,
  },
})

export default class EditBookMarkFolder extends Mixins(Const) {
  // 編集対象情報
  @Prop({type: Object, default: false})
    editFolder!: BookMarkFolders;
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      // エラーMSGリセット
      this.errors = [];
    };
  // 登録成功
  @Emit('folder-edited')
    folderEdited(succueseMsg: string): void {
    };
  // カラーピッカーダイアログ
  private colorPockerDialog: boolean = false;
  // フォームバリデーションエラー
  private errors: string[] = [];
  /**
   * カラーセット
   */
  private setColor(color: string): void {
    this.editFolder.color = color;
    this.colorPockerDialog = false;
  };
  /**
   * フォルダー編集
   */
  private editBookMarkFolder(): void {
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    let succueseMsg: string = '';
    Axios.post('/api/editBookMarkFolder',{
      editFolder: this.editFolder,
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
      let succueseMsg = `「${this.editFolder.title}」${this.SUCCESS_MSG.EDIT_SUCCESS}`;
      this.folderEdited(succueseMsg);
    }).catch((e) => {
      if(e.response.status === 401) {
        alert(this.ERROR_MSG.EXPAIRED_SESSION);
        this.$store.dispatch('resetUserInfo');
        this.$router.push("/login");
      };
      if(e.response.status === 500) {
        window.alert(this.ERROR_MSG.SERVER_ERROR);
      };
    })
  }
}
</script>

<style>

</style>
