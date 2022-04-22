<template>
  <div>
    <v-sheet class="kokuban my-0" width="600px" dark>
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title">仮会員登録</p>
              <v-col cols="12" v-if="alertMsgs.length">
                <alert-msg class="mt-4"
                  :alertType=alertType
                  :alertMsgs=alertMsgs>
                </alert-msg>
              </v-col>
              <v-col cols="10" class="mt-n5">
                <v-sheet class="px-2 py-2" color="#1B5E20" rounded>
                  <p class="mb-0">会員登録の仮登録を行います。<br/>
                  メールアドレスをご入力の上、仮登録ボタンを押下してください。<br/>
                  入力したメールアドレスへ本登録を行うURLを送信致しますので、メール記載のURLへアクセスし、本登録を行なってください。
                  </p>
                </v-sheet>
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
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'TemporarySignUp',
  components:{
    Complete,
    AlertMsg,
  }
})

export default class TemporarySignUp extends Mixins(Const, Util) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize();
      this.msgReset();
    };
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // 登録ユーザー情報
  private email: string = '';
  // 完了ダイアログ
  private comoleteDialog: boolean = false;
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
  }
  /**
   * 仮登録
   */
  private temporaryRegister(): void {
    this.msgReset();
    this.setLoading();
    Axios.post('/api/register',{
      email: this.email
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
      this.comoleteDialog = !this.comoleteDialog;
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    this.email = '';
  };
}
</script>

<style scoped>

</style>


