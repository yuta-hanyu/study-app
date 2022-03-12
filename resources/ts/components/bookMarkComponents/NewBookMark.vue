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
              <v-combobox
                dark
                prepend-icon="mdi-folder"
                v-model="select"
                :items="comboboxItems"
                label="フォルダ選択"
                dense>
              </v-combobox>
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
import {Component, Mixins,Emit} from 'vue-property-decorator';
import Const from '../../common/const';
import { BookMarks } from '../../interfaces/BookMarks';
import Axios from 'axios';

@Component({
  name: 'NewBookMark',
  components: {
  },
})

export default class NewBookMark extends Mixins(Const) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      // this.initialize()
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
  private select: string = 'Vuetify';
  private comboboxItems: string[] = [
      'Programming',
          'Design',
          'Vue',
          'Vuetify',
  ];
  // mounted() {
  //   this.initialize();
  // }
  /**
   * データ初期化
   */
  // private initialize(): void {
  //   Object.keys(this.newBookMarkFolder).forEach(key => this.newBookMarkFolder[key] = '');
  //   this.newBookMarkFolder.user_id = this.$store.state.userInfo.userId;
  // };
  /**
   * リンクからタイトル取得
   */
  private getTitle(): void {
    Axios.post('/api/bookMark/getTitle', {
      newBookMark: {
        link: this.newBookMark.link
      }
    }).then((res) => {
      console.log(res)
      console.log(res.data)
      if(res.data.validateState === false) {
        this.errors.push(res.data.message.link[0]);
        return;
      }
      this.newBookMark.title = res.data;
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
