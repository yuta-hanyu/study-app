<template>
  <div class="bg">
    <v-sheet class="kokuban my-0" width="600px" dark>
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">仮会員登録</p>
              <v-col cols="12" v-if="errors.length">
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
                <v-row justify="center">
                  <v-col cols="10">
                    <v-text-field
                      v-model="email"
                      prepend-icon="mdi-email"
                      label="メールアドレス">
                    </v-text-field>
                  </v-col>
                </v-row>
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
                    @click="temporaryRegister">
                    仮登録
                  </v-btn>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-sheet>
    <!-- 完了ダイアログ -->
    <v-dialog
      width="400px"
      v-model="comoleteDialog"
      persistent>
      <complete
        @back="comoleteDialog = !comoleteDialog, back()">
         <template #title>{{SUCCESS_MSG.REGISTER_TEMPORART_SIGN_UP}}</template>
         <template #content>送信させて頂いたメールより本登録を行なってください。</template>
      </complete>
    </v-dialog>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../../common/const';
import Util from '../../common/util';
import Complete from '../utilComponent/Complete.vue';

@Component({
  name: 'TemporarySignUp',
  components:{
    Complete,
  }
})

export default class TemporarySignUp extends Mixins(Const, Util) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize()
    };
  // フォームバリデーションエラー
  private errors: string[] = [];
  // 登録ユーザー情報
  private email: string = '';
  // 完了ダイアログ
  private comoleteDialog: boolean = false;
  /**
   * 仮登録
   */
  private temporaryRegister(): void {
    // エラーMSGリセット
    this.errors = [];
    this.setLoading();
    Axios.post('/api/register',{
      email: this.email
    }).then((res) => {
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
      this.comoleteDialog = !this.comoleteDialog;
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    })
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    this.email = '';
    this.errors = [];
  };
}
</script>

<style scoped>

</style>


