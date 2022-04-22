<template>
  <div>
    <v-sheet width="500px" dark class="kokuban my-0">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="dialog-title mb-n3">お問い合わせ</p>
            <v-col cols="12">
              <alert-msg class="mt-4"
                v-if="alertMsgs.length"
                :alertType=alertType
                :alertMsgs=alertMsgs>
              </alert-msg>
            </v-col>
            <v-col cols="10 pt-n1">
              <p prepend-icon="mdi-book-open" style="color: hsla(0,0%,100%,.7)">
                <v-icon
                  dark
                  left>
                  mdi-check-circle
                </v-icon>
                お問い合わせ種別
              </p>
              <v-radio-group
                class="ml-8"
                v-model="inquiry.type"
                row>
                <v-radio
                  label="ご質問"
                  value="ご質問">
                </v-radio>
                <v-radio
                  label="ご要望"
                  value="ご要望">
                </v-radio>
                <v-radio
                  label="その他"
                  value="その他">
                </v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="10" class="mt-n8">
              <v-textarea
                v-model="inquiry.content"
                prepend-icon="mdi-book-open"
                label="お問い合わせ内容">
              </v-textarea>
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
                <v-col cols="3" class="text-center">
                  <v-btn
                    class="go"
                    @click="submitMail">
                    登録
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
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
         <template #title>{{SUCCESS_MSG.REGISTER_SUCCESS}}</template>
         <template #content>ご登録のメールアドレスへお問い合わせ内容確認のメールを送付しております。</template>
      </complete>
    </v-dialog>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../common/const';
import Util from '../common/util';
import Axios from 'axios';
import Complete from './utilComponent/Complete.vue';
import { Inquirys } from '../interfaces/Inquirys';
import AlertMsg from '../components/utilComponent/AlertMsg.vue';

@Component({
  name: "Inquiry",
  components: {
    Complete,
    AlertMsg,
  }
})

export default class Inquiry extends Mixins(Const, Util) {
  // 戻るボタン押下
  @Emit('back-inquiry')
  back(): void {
    this.initialize();
    this.msgReset();
  };
  // 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // お問い合わせ内容
  private inquiry: Inquirys = {};
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
   * データ初期化
   */
  private initialize(): void {
    this.inquiry = Object.assign({}, {})
  };
  /**
   * お問い合わせ開始
  */
  private submitMail(): void {
    this.msgReset();
    if(!window.confirm(this.CONFIRM_MSG.REGISTER)) {
      return;
    };
    this.setLoading();
    Axios.post('/api/inquiry',{
      inquiry: this.inquiry
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
}
</script>

<style>

</style>
