<template>
  <v-card py="3">
    <v-card-title class="justify-center title text-h4">Todo編集・削除</v-card-title>
    <v-row
      class="mt-3"
      v-if="this.errors.length"
      justify="center" >
      <v-alert
        v-for="(error, index) in this.errors" :key=index
        dense
        text
        border="left"
        type="error"
        class="px-6"
        width="70%"
      >
        {{error}}
      </v-alert>
    </v-row>
    <v-row class="px-6">
      <v-form ref="form">
        <v-col cols="12">
          <v-text-field
            required
            v-model="editTodo.title"
            :counter="15"
            label="タイトル(必須)"
          ></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-textarea
            v-model="editTodo.content"
            :counter="255"
            label="コンテンツ"
          ></v-textarea>
        </v-col>
        <v-col cols="12" height='10%' class="my-n5">
          <v-radio-group
            v-model="editTodo.state"
            row
            label="ステータス(必須)　　　　　　　　　　　　　"
            class="my-0">
            <v-radio
              label="未対応"
              :value=0
            ></v-radio>
            <v-radio
              label="対応中"
              :value=1
            ></v-radio>
            <v-radio
              label="保留"
              :value=2
            ></v-radio>
            <v-radio
              label="完了"
              :value=3
            ></v-radio>
          </v-radio-group>
        </v-col>
        <v-col cols="12" height='10%' class="mt-n3">
          <v-checkbox
            v-model="editTodo.book_mark"
            label="上部へ固定"
            :value=1
            class="my-0"
          ></v-checkbox>
        </v-col>
      </v-form>
    </v-row>
    <v-card-actions class="actions">
      <v-row justify="center" class="mb-auto">
        <v-col cols="3" align="right">
          <v-btn
            color="grey lighten-1"
            width="25%"
            @click="backTodos"
            rounded
            elevation="20">
            戻る
          </v-btn>
        </v-col>
        <v-col cols="3" align="center">
          <v-btn
            @click="updateTodo"
            width="25%"
            color="orange lighten-2"
            elevation="20"
            rounded>
            編集
          </v-btn>
        </v-col>
        <v-col cols="3">
          <v-btn
            color="red lighten-1"
            width="25%"
            @click="removeTodo"
            rounded
            elevation="20">
            削除
          </v-btn>
        </v-col>
      </v-row>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Emit} from 'vue-property-decorator';
import Const from '../../common/const';
import { Todos } from '../../interfaces/Todos';
import Axios from 'axios';
// Todo型にuserIdを付与
type typeEditTodo = Todos & {
  userId: number;
};

@Component({
  name: 'DetailTodo',
})

export default class DetailTodo extends Mixins(Const) {
  @Prop({type: Object, default: false})
    detailTodo!: typeEditTodo;
  // 戻るボタン押下
  @Emit('back-todos')
    emitBackTodos(): void {
  };
  // 削除ボタン押下
  @Emit('delete-todo')
    emitDeleteTodos(succueseMsg: string): void {
  };
  // 編集ボタン押下
  @Emit('todo-update')
    emitUpdateTodos(succueseMsg: string): void {
  };
  // 編集todo
  private editTodo: typeEditTodo = {
    userId: this.detailTodo.userId,
    title: this.detailTodo.title,
    content: this.detailTodo.content,
    state: this.detailTodo.state,
    book_mark: this.detailTodo.book_mark
  };
  // 処理成功時MSG
  private succueseMsg: string =  '';
  // フォームバリデーションエラー
  private errors: string[] = [];
  /**
   * 編集ボタン押下
   */
  private updateTodo(): void {
    // エラーMSGリセット
    this.errors = [];
    // 成功MSGリセット
    this.succueseMsg = '',
    Axios.put(`/api/todo/update`, {
      id: this.detailTodo.id,
      title: this.editTodo.title,
      content: this.editTodo.content,
      state: this.editTodo.state,
      book_mark: this.editTodo.book_mark,
    }).then((res) => {
      if(res.data.validateState === false) {
        this.errors = this.changeErrors(res.data.message);
        return;
      }
      this.succueseMsg = `"${this.editTodo.title}"を編集しました`
      this.initialize();
      this.emitUpdateTodos(this.succueseMsg);
    }).catch((e) => {
      console.log(e);
      window.alert("データの更新に失敗しました")
    });
  };
  /**
   * エラーメッセージをオブジェクトから配列へ変換
   */
  private changeErrors(message: string[]): string[] {
    for (let [key, value] of Object.entries(message)) {
      this.errors.push(value[0]);
    }
    return this.errors;
  }
  /**
   * 戻る
   */
  private backTodos(): void {
    this.initialize();
    this.emitBackTodos();
  }
  /**
   * データ初期化
   */
  private initialize(): void {
    Object.keys(this.editTodo).forEach(key => this.editTodo[key] = '');
    this.errors = [];
  }
  /**
   * 初期データセット
   */
  private setVal(): void {
    this.editTodo.userId = this.detailTodo.userId,
    this.editTodo.title = this.detailTodo.title,
    this.editTodo.content = this.detailTodo.content,
    this.editTodo.state = this.detailTodo.state,
    this.editTodo.book_mark = this.detailTodo.book_mark
  }
  /**
   * 削除ボタン押下
   */
  private removeTodo(): void {
    Axios.delete(`/api/todo/${this.detailTodo.id}`).then((res) => {
      this.succueseMsg = `"${this.editTodo.title}"を削除しました`
      this.initialize();
      this.emitDeleteTodos(this.succueseMsg);
    }).catch((e) => {
      console.log(e);
      window.alert("データの更新に失敗しました");
    });
  }
}
</script>

<style scoped>
  .card {
    padding: 10px;
  }
  .title {
    background-color: #a8ffd3;
    font-weight: bold;
  }
  .actions {
    background-color: #a8ffd3;
  }
</style>
