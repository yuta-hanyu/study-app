<template>
  <v-card py="3">
    <v-card-title class="justify-center title text-h4">Todo追加</v-card-title>
    <v-row
      class="mt-3"
      v-if="this.errors.length"
      justify="center">
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
    </v-row>
    <v-form ref="form">
    <v-row class="px-6">
      <v-col cols="12">
      <v-text-field
        required
        v-model="newTodo.title"
        :counter="15"
        label="タイトル(必須)"
      ></v-text-field>
      </v-col>
      <v-col cols="12">
      <v-textarea
        v-model="newTodo.content"
        :counter="255"
        label="コンテンツ"
      ></v-textarea>
      </v-col>
      <v-col cols="12" height='10%' class="my-n5">
        <v-radio-group
          v-model="newTodo.state"
          row
          label="ステータス(必須)　　　　　　　　　　　　　　　　　　"
          class="my-0">
          <v-radio
            label="未対応"
            value=0
          ></v-radio>
          <v-radio
            label="対応中"
            value=1
          ></v-radio>
          <v-radio
            label="保留"
            value=2
          ></v-radio>
        </v-radio-group>
      </v-col>
      <v-col cols="12" height='10%' class="my-n3">
        <v-row>
          <v-col cols="12" >
            <v-checkbox
              v-model="newTodo.book_mark"
              label="上部へ固定"
              value=1
              class="my-0"
            ></v-checkbox>
          </v-col>
          <v-col cols="6" height='10%' class="mt-n5 pt-0">
            <v-dialog
              v-model="datePickerDialog"
              persistent
              width="290px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    class="pt-0 pb-3 label-font"
                    v-model="changeReminderDate"
                    label="リマインダー日付"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
              <date-picker
                @choice-date=setReminderDate>
              </date-picker>
            </v-dialog>
          </v-col>
          <v-col cols="6" height='10%' class="mt-n5 pt-0">
            <v-text-field
              class="pt-0 pb-3"
            label="リマインダー時間">
            </v-text-field>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    </v-form>
    <v-card-actions class="actions">
      <v-row justify="center" class="mb-auto">
        <v-col cols="6" align="right">
          <v-btn
            color="grey lighten-1"
            width="40%"
            @click="backTodos"
            rounded
            elevation="20">
            戻る
          </v-btn>
        </v-col>
        <v-col cols="6">
          <v-btn
            width="40%"
            color="orange lighten-2"
            @click="todoRegister"
            elevation="20"
            rounded>
            登録
          </v-btn>
        </v-col>
      </v-row>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import {Component, Mixins, Prop} from 'vue-property-decorator';
import DatePicker from '../utilComponent/DatePicker.vue';
import Const from '../../common/const';
import Axios from 'axios';
import { Todos } from '../../interfaces/Todos';

@Component({
  name: 'NewTodo',
  components: {
    DatePicker,
  }
})

export default class NewTodo extends Mixins(Const) {
  @Prop({type: Number, default: false})
  userId!: number;
  // 新規登録todo
  private newTodo: Todos = {
    title: '',
    content: '',
    state: null,
    book_mark: null,
    reminderDate: '',
    reminderTime: '',
  };
  // フォームバリデーションエラー
  private errors: string[] = [];
  // 日付カレンダーダイアログ表示フラグ
  private datePickerDialog: boolean = false;
  // 登録成功時MSG
  private succueseMsg: string =  '';
  // リマインダー年月日表示用ラッパー
  get changeReminderDate(): string {
    let reminderDate = new Date(this.newTodo.reminderDate!.replace(/-/g,'/'));
    // 実際に表示する年月日
    let displayDate: string = '';
    // 年月日に取得
    const year = reminderDate.getFullYear();
    const month = reminderDate.getMonth() + 1; // 月は0~11の値で管理されるため+1とする
    const date = reminderDate.getDate();
    // NaNは排除
    if(Number.isNaN(year) || Number.isNaN(month) || Number.isNaN(date)) {
      return displayDate = '';
    };
    // 表示用に加工
    displayDate = year + "年" + month + "月" + date + "日";
    return displayDate;
  };
  /**
   * 登録
   */
  private todoRegister(): void {
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    this.succueseMsg = '',
    Axios.post('/api/todos', {
      title: this.newTodo.title,
      content: this.newTodo.content,
      state: this.newTodo.state,
      book_mark: this.newTodo.book_mark,
      reminder: this.newTodo.reminder,
      userId: this.userId,
    }).then((res) => {
      if(res.data.validateState === false) {
        this.errors = this.changeErrors(res.data.message);
        return;
      }
      this.succueseMsg = 'todoを登録しました'
      this.initialize();
      this.$emit('todo-register', this.succueseMsg);
    }).catch((e) => {
      console.log(e);
      window.alert("データの更新に失敗しました")
    });
  };
  /**
   * エラーメッセージをオブジェクトから配列へ変換
   */
  private changeErrors(message: string): string[] {
    for (let [key, value] of Object.entries(message)) {
      this.errors.push(value[0]);
    }
    return this.errors;
  };
  /**
   * 戻る
   */
  private backTodos(): void {
    this.initialize();
    this.$emit('back-todos');
  };
  /**
   * データ初期化
   */
  private initialize(): void {
    Object.keys(this.newTodo).forEach(key => this.newTodo[key] = '');
    this.errors = [];
  };
  /**
   * リマインダー日付セット
   */
  private setReminderDate(date: string): void {
    this.newTodo.reminderDate = date;
    this.datePickerDialog = false;
  }
}
</script>

<style scoped>
  .card {
    padding: 10px;
  }
  .title {
    background-color: #a8ffff;
    font-weight: bold;
  }
  .actions {
    background-color: #a8ffff;
  }
</style>
