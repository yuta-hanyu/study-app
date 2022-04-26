<template>
<div>
    <v-sheet dark class="kokuban">
      <v-form>
          <v-row justify="center">
            <p class="dialog-title">タスク編集・削除</p>
            <v-col cols="12" v-if="alertMsgs.length">
              <alert-msg class="mt-4"
                :alertType=alertType
                :alertMsgs=alertMsgs>
              </alert-msg>
            </v-col>
            <v-col cols="10">
              <v-text-field
                required
                v-model="editTodo.title"
                :counter="15"
                label="タイトル(必須)"
              ></v-text-field>
            </v-col>
            <v-col cols="10" class="mt-n8">
              <v-textarea
                v-model="editTodo.content"
                :counter="255"
                label="コンテンツ"
              ></v-textarea>
            </v-col>
            <v-col cols="10" height='10%' class="my-n3" >
              <v-radio-group
                v-model="editTodo.state"
                row
                label="ステータス(必須)　　　　　　　　　　　　　　　　　　　　　　　　"
                dense
                class="my-0 pr-4">
                <v-radio
                  label="未対応"
                  :value="TOD0_STATUS.UNTREATED"
                ></v-radio>
                <v-radio
                  label="対応中"
                  :value="TOD0_STATUS.ON_THE_WAY"
                ></v-radio>
                <v-radio
                  label="保留"
                  :value="TOD0_STATUS.ON_HPLD"
                ></v-radio>
                <v-radio
                  label="完了"
                  :value="TOD0_STATUS.FINISH"
                ></v-radio>
              </v-radio-group>
            </v-col>
              <v-col cols="10" height='10%' class="mt-3">
              <v-dialog
                v-model="datePickerDialog"
                persistent
                width="290px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      class="pt-0 pb-3 label-font"
                      v-model="editTodo.reminder"
                      label="リマインダー日付"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                <date-picker
                  @choice-date="setReminderDate">
                </date-picker>
              </v-dialog>
            </v-col>
            <v-col cols="10" class="d-flex flex-column justify-space-between align-center">
              <v-checkbox
                v-model="editTodo.book_mark"
                label="上部へ固定する"
                :value="TODO_BOOKMARK_FIXED"
                class="my-0 d-flex flex-column justify-space-between align-center">
              </v-checkbox>
            </v-col>
            <v-row justify="center" class="mb-3">
              <v-col
                class="text-center"
                cols="2">
                <v-btn
                  class="back"
                  @click="back">
                  戻る
                </v-btn>
              </v-col>
              <v-col cols="2" class="text-center">
                <v-btn
                  class="go"
                  @click="updateTodo">
                  編集
                </v-btn>
              </v-col>
              <v-col cols="2" class="text-center">
                <v-btn
                  class="delete"
                  @click="removeTodo">
                  削除
                </v-btn>
              </v-col>
            </v-row>
          </v-row>
      </v-form>
    </v-sheet>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Emit} from 'vue-property-decorator';
import DatePicker from '../utilComponent/DatePicker.vue';
import Const from '../../common/const';
import { Todos } from '../../interfaces/Todos';
import Axios from 'axios';
import Util from '../../common/util';
import AlertMsg from '../utilComponent/AlertMsg.vue';

@Component({
  name: 'DetailTodo',
  components: {
    DatePicker,
    AlertMsg,
  }
})

export default class DetailTodo extends Mixins(Util, Const) {
  @Prop({type: Object, default: false})
    detailTodo!: Todos;
    // 戻るボタン押下
  @Emit('back')
    back(): void {
      this.initialize();
      this.msgReset();
    };
  // 削除ボタン押下
  @Emit('delete-todo')
    emitDeleteTodos(succueseMsg: string): void {
      this.initialize();
      this.msgReset();
  };
  // 登録成功
  @Emit('todo-update')
    register(succueseMsg: string): void {
      this.initialize();
      this.msgReset();
    };

// 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // 編集用todo
  private editTodo: Todos | null = Object.assign({}, this.detailTodo);
  // 日付カレンダーダイアログ表示フラグ
  private datePickerDialog: boolean = false;
  /**
   * データ初期化
   */
  private initialize(): void {
    this.editTodo = Object.assign({}, {})
  };
  /**
   * メッセージ初期化
   */
  private msgReset(): void {
    this.alertMsgs = [];
    this.alertType = '';
  }
  /**
   * 編集ボタン押下
   */
  private updateTodo(): void {
    this.msgReset();
    this.setLoading();
    Axios.post(`/api/todo/update`, {
      editTodo: this.editTodo,
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
      let succueseMsg = `「${this.editTodo!.title}」を編集しました`
      this.register(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  };
  /**
   * 初期データセット
   */
  private setInitializeValue(): void {
    this.editTodo = Object.assign({}, this.detailTodo);
  };
  /**
   * 削除ボタン押下
   */
  private removeTodo(): void {
    this.setLoading();
    Axios.post(`/api/todo/remove`,{
      editTodo: this.editTodo,
    }).then((res) => {
      let succueseMsg = `"${this.editTodo!.title}"を削除しました`
      this.register(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    });
  };
    /**
   * リマインダー日付セット
   */
  private setReminderDate(date: string): void {
    this.editTodo!.reminder = date;
    this.datePickerDialog = false;
  };
}
</script>

<style scoped>
</style>
