<template>
  <div>
    <v-container fluid>
      <v-row v-if="this.succueseMsg" justify="center">
        <v-col cols="12">
        <v-alert
          align="center"
          dense
          dark
          color="green">
          {{this.succueseMsg}}
        </v-alert>
        </v-col>
      </v-row>
      <v-row id="switch">
        <v-switch
          v-model="finishFlag"
          label="完了を表示する"
          @click="getTodos()"
          color="info"
        ></v-switch>
      </v-row>
      <!-- ブックマークあり -->
      <v-row justify="center">
        <v-col cols="10">
          <div class="mt-5" align="left">固定済み</div>
          <v-banner single-line></v-banner>
        </v-col>
        <v-col cols="3"
          v-for="(todo,index) in bookMarkTodos"
          :key=index
          v-show="todo.book_mark === 1">
          <v-tooltip bottom color="deep-orange lighten-2">
            <template v-slot:activator="{ on, attrs }">
              <v-card
                dark
                @click="todoDetail(todo)"
                elevation="10"
                class="mx-auto"
                outlined
                :style="{backgroundColor: color(todo)}"
                v-bind="attrs"
                v-on="on">
                <v-card-title>{{todo.title}}</v-card-title>
                <v-card-subtitle class="mb-n7">
                  <span v-if="todo.state === 0">
                  未対応
                  </span>
                  <span v-else-if="todo.state === 1">
                  対応中
                  </span>
                  <span v-else-if="todo.state === 2">
                  保留
                  </span>
                  <span v-else>
                  完了
                  </span>
                </v-card-subtitle>
                <v-banner single-line></v-banner>
                <v-card-text class="new_line" v-text="todo.content">
                </v-card-text>
              </v-card>
            </template>
            <span>todo編集・削除</span>
          </v-tooltip>
        </v-col>
      </v-row>
      <!-- ブックマークなし -->
      <v-row justify="center">
        <v-col cols="10">
          <div class="mt-5" align="left">その他</div>
          <v-banner single-line></v-banner>
        </v-col>
        <v-col cols="3"
          v-for="(todo,index) in todos"
          :key=index
          v-show="todo.book_mark !== 1">
          <v-tooltip bottom color="deep-orange lighten-2">
            <template v-slot:activator="{ on, attrs }">
              <v-card
                dark
                @click="todoDetail(todo)"
                elevation="10"
                class="mx-auto"
                outlined
                :style="{backgroundColor: color(todo)}"
                v-bind="attrs"
                v-on="on">
                <v-card-title>{{todo.title}}</v-card-title>
                <v-card-subtitle class="mb-n7">
                  <span v-if="todo.state === 0">
                  未対応
                  </span>
                  <span v-else-if="todo.state === 1">
                  対応中
                  </span>
                  <span v-else-if="todo.state === 2">
                  保留
                  </span>
                  <span v-else>
                  完了
                  </span>
                </v-card-subtitle>
                <v-banner single-line></v-banner>
                <v-card-text class="new_line" v-text="todo.content">
                </v-card-text>
              </v-card>
            </template>
            <span>todo編集・削除</span>
          </v-tooltip>
        </v-col>
      </v-row>
    </v-container>
    <!-- 新規登録ボタン -->
    <v-tooltip bottom color="primary">
      <template v-slot:activator="{ on, attrs }">
        <v-icon
          color="primary"
          v-bind="attrs"
          v-on="on"
          id="plus-circle"
          @click="newTodoOpen">
        mdi-plus-circle
        </v-icon>
      </template>
      <span>todo追加</span>
    </v-tooltip>
    <!-- 一括削除ボタン -->
    <v-tooltip bottom color="red">
      <template v-slot:activator="{ on, attrs }">
        <v-icon
          color="red"
          id="delete-alert"
          @click="allDeleteOpen"
          v-bind="attrs"
          v-on="on">
        mdi-delete-alert
        </v-icon>
      </template>
      <span>todo一括削除</span>
    </v-tooltip>
    <!-- 新規作成ダイアログ -->
    <v-dialog
      persistent
      v-model="newDialog"
      height="200px"
      width="500px">
      <new-todo
        @back-todos="back"
        @todo-register="todoRegister"
        :userId=userId>
      </new-todo>
    </v-dialog>
    <!-- 一括削除ダイアログ -->
    <v-dialog
      persistent
      v-model="allDeleteDialog"
      height="200px"
      width="400px">
      <all-delete-todo
        @back-todos="back"
        @remove-all="removeAll"
        :userId=userId>
      </all-delete-todo>
    </v-dialog>
    <!-- 詳細ダイアログ -->
    <v-dialog
      persistent
      v-model="detailDialog"
      height="200px"
      width="500px">
      <detail-todo
        ref="child"
        @todo-update="updateTodo"
        @delete-todo="deleteTodo"
        @back-todos="back"
        :detailTodo=detailTodo>
      </detail-todo>
    </v-dialog>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import AllDeleteTodo from './todoComponents/AllDeleteTodo.vue'
import NewTodo from './todoComponents/NewTodo.vue';
import DetailTodo from './todoComponents/DetailTodo.vue';
import Const from '../common/const';
import Axios from 'axios';
import { User } from '../interfaces/User';
import { Todos } from '../interfaces/Todos';

@Component({
  name: 'Todo',
  components: {
    NewTodo,
    AllDeleteTodo,
    DetailTodo,
  },
})

export default class Todo extends Mixins(Const) {
  $refs: any = {}
  // ユーザーID
  private userId: User = this.$store.state.userInfo.userId;
  // 固定表示一覧
  private bookMarkTodos: Todos[] = [];
  // その他一覧
  private todos: Todos[] = [];
  // 新規登録ダイアログ表示フラグ
  private newDialog: boolean = false;
  // 新規登録ダイアログ表示フラグ
  private allDeleteDialog: boolean = false;
  // 詳細ダイアログ表示フラグ
  private detailDialog: boolean = false;
  // 詳細todo
  private detailTodo: Todos | null = null;
  // ステータス完了表示フラグ
  private finishFlag: boolean = false;
  // 処理成功MSG
  private succueseMsg: string = '';
  mounted() {
    this.getTodos();
  };
  /**
   * todo一覧表示
   */
  private getTodos(): void {
    this.bookMarkTodos = [];
    this.todos = [];
    Axios.get(`/api/todos/${this.userId}`).then((res) => {
      let todos: Todos[] = [];
      todos = res.data.result;
      // 固定表示とその他を分別（ステータス完了非表示）
      if (this.finishFlag === false) {
        for (let todo of todos) {
          if (todo.state !== 3 && todo.book_mark === 1) {
            this.bookMarkTodos.push(todo);
          } else if(todo.state !== 3) {
            this.todos.push(todo);
          } else {
            continue;
          }
        }
        return;
      }
      // 固定表示とその他を分別（ステータス完了表示）
      if (this.finishFlag === true) {
        for (let todo of todos) {
          if (todo.book_mark === 1) {
            this.bookMarkTodos.push(todo);
          } else {
            this.todos.push(todo);
          }
        }
      }
    }).catch((e) => {
      //認証エラー
      if(e.response.status === 401) {
        alert(this.ERROR_MSG.EXPAIRED_SESSION);
        this.$store.dispatch('resetUserInfo');
        this.$router.push("/login");
      };
      console.log(e);
    });
  }
  /**
   * ステータスに応じた配色適用
   */
  private color(todo: Todos){
    switch (todo.state){
    // 未対応
    case 0:
      return "#990000"
    // 対応中
    case 1:
      return "#003366"
    // 保留
    case 2:
      return "#006600"
    // 対応済み
    default:
      return "#020202"
    }
  }
  /**
   * 新規登録ダイアログを表示
   */
  private newTodoOpen(): void {
    this.newDialog = true;
  }
  /**
   * 新規登録ダイアログを非表示
   */
  private newTodoClose(): void {
    this.newDialog = false;
  }
  /**
   * todo新規登録
   */
  private todoRegister(succueseMsg: string): void {
    this.newTodoClose();
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 3000);
    this.getTodos();
  }
  /**
   * 全削除ダイアログを表示
   */
  private allDeleteOpen(): void {
    this.allDeleteDialog = true;
  }
  /**
   * 全削除ダイアログを非表示
   */
  private allDeleteClose(): void {
    this.allDeleteDialog = false;
  }
  /**
   * 全削除成功
   */
  private removeAll(succueseMsg: string): void {
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 3000);
    this.getTodos();
  }
  /**
   * todo詳細
   */
  private async todoDetail(todo: Todos): Promise<void> {
    // 子コンポーネント生成後、初期をセット
      await (
        this.detailDialog = true,
        this.detailTodo = todo
      );
      // コンポーネントに初期をセット
      this.$refs.child.setVal();
  }
  /**
   * todo編集完了
   */
  private updateTodo(succueseMsg: string): void {
    this.detailDialogClose();
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 3000);
    this.getTodos();
  }
  /**
   * 削除成功
   */
  private deleteTodo(succueseMsg: string): void {
    this.detailDialogClose();
    this.succueseMsg = succueseMsg;
    setTimeout(() => {
      this.succueseMsg = '';
    }, 3000);
    this.getTodos();
  }
  /**
   * 詳細ダイアログを非表示
   */
  private detailDialogClose(): void {
    this.detailDialog = false;
  }
  /**
   * 各ダイアログから戻る
   */
  private back(): void {
    if(this.newDialog = true) {
      this.newTodoClose();
    }
    if(this.allDeleteDialog = true) {
      this.allDeleteClose();
    }
    if(this.detailDialog = true) {
      this.detailDialogClose();
    }
  }
}
</script>

<style scoped>
  #switch{
    position: fixed;
    right: 5%;
    top: 10%;
  }
  #plus-circle{
    position: fixed;
    right: 10%;
    bottom: 5%;
    transform: scale(2, 2);
  }
  #delete-alert {
    position: fixed;
    right: 5%;
    bottom: 5%;
    transform: scale(2, 2);
  }
  .trash{
    position: absolute;
    right: 0;
    top: 2%;
  }
  .edit{
    position: absolute;
    right: 20px;
    top: 2%;
  }
  .new_line {
    white-space: pre-wrap;
    text-align: left;
  }
</style>
