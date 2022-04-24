<template>
  <div class="bg">
    <v-container class="container">
      <alert-msg
        v-if="alertMsgs.length"
        class="mt-4"
        :alertType=alertType
        :alertMsgs=alertMsgs>
      </alert-msg>

      <v-row class="mt-4" justify="end">
        <v-switch
          dark
          v-model="finishFlag"
          label="完了を表示する"
          @click="getTodos()"
          color="red"
        ></v-switch>
        <v-tooltip bottom color="#EEEEEE">
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              size="50"
              class="mx-2"
              color="primary"
              v-bind="attrs"
              v-on="on"
              id="plus-circle"
              @click="newDialog = !newDialog">
            mdi-plus-circle
            </v-icon>
          </template>
          <span style="color: black;">タスク登録</span>
        </v-tooltip>
        <v-tooltip bottom color="#EEEEEE">
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              size="50"
              class="mx-2"
              color="red"
              id="delete-alert"
              @click="allDeleteDialog = !allDeleteDialog"
              v-bind="attrs"
              v-on="on">
            mdi-delete-alert
            </v-icon>
          </template>
          <span style="color: black;">タスク一括削除</span>
        </v-tooltip>
      </v-row>
      <!-- ブックマークあり -->
      <v-row justify="center">
        <v-col cols="12">
          <div class="mt-5" align="left">固定済み</div>
          <hr class="hr-down mt-1 mb-1">
        </v-col>
        <v-col cols="3"
          v-for="(todo,index) in bookMarkTodos"
          :key=index
          v-show="todo.book_mark === TODO_BOOKMARK_FIXED">
          <v-tooltip bottom color="#EEEEEE">
            <template v-slot:activator="{ on, attrs }">
              <v-card
                dark
                @click="goDetail(todo)"
                elevation="10"
                class="mx-auto"
                outlined
                :style="{backgroundColor: color(todo)}"
                v-bind="attrs"
                v-on="on">
                <v-card-title>{{todo.title}}</v-card-title>
                <v-card-subtitle class="mb-n7">
                  <span v-if="todo.state === TOD0_STATUS.UNTREATED">
                  未対応
                  </span>
                  <span v-else-if="todo.state === TOD0_STATUS.ON_THE_WAY">
                  対応中
                  </span>
                  <span v-else-if="todo.state === TOD0_STATUS.ON_HPLD">
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
            <span style="color: black;">タスク編集・削除</span>
          </v-tooltip>
        </v-col>
      </v-row>
      <!-- ブックマークなし -->
      <v-row justify="center">
        <v-col cols="12">
          <div class="mt-5" align="left">その他</div>
          <hr class="hr-down mt-1 mb-1">
        </v-col>
        <v-col cols="3"
          v-for="(todo,index) in todos"
          :key=index
          v-show="todo.book_mark !== TODO_BOOKMARK_FIXED">
          <v-tooltip bottom color="#EEEEEE">
            <template v-slot:activator="{ on, attrs }">
              <v-card
                dark
                @click="goDetail(todo)"
                elevation="10"
                class="mx-auto"
                outlined
                :style="{backgroundColor: color(todo)}"
                v-bind="attrs"
                v-on="on">
                <v-card-title>{{todo.title}}</v-card-title>
                <v-card-subtitle class="mb-n7">
                  <span v-if="todo.state === TOD0_STATUS.UNTREATED">
                  未対応
                  </span>
                  <span v-else-if="todo.state === TOD0_STATUS.ON_THE_WAY">
                  対応中
                  </span>
                  <span v-else-if="todo.state === TOD0_STATUS.ON_HPLD">
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
            <span style="color: black;">タスク編集・削除</span>
          </v-tooltip>
        </v-col>
      </v-row>
    </v-container>
    <!-- 新規登録ボタン -->
    <!-- <v-tooltip bottom color="primary">
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
    </v-tooltip> -->
    <!-- 一括削除ボタン -->
    <!-- <v-tooltip bottom color="red">
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
    </v-tooltip> -->
    <!-- 新規作成ダイアログ -->
    <v-dialog
      persistent
      v-model="newDialog"
      width="600px">
      <new-todo
        @back="newDialog=!newDialog"
        @todo-register="registered">
      </new-todo>
    </v-dialog>
    <!-- 一括削除ダイアログ -->
    <v-dialog
      persistent
      v-model="allDeleteDialog"
      width="400px">
      <all-delete-todo
        @remove-all="registered"
        @back="allDeleteDialog=!allDeleteDialog">
      </all-delete-todo>
    </v-dialog>
    <!-- 詳細ダイアログ -->
    <v-dialog
      persistent
      v-model="detailDialog"
      width="700px">
      <detail-todo
        ref="child"
        @back="detailDialog=!detailDialog"
        @todo-update="registered"
        @delete-todo="registered"
        :detailTodo=detailTodo>
      </detail-todo>
    </v-dialog>
        <!-- @todo-update="updateTodo"
        @delete-todo="deleteTodo" -->
    <!-- リマインダーダイアログ -->
    <!-- <v-dialog
      persistent
      v-model="remaindDialog"
      width="400px">
      <remaind-todo
        @back="remaindDialog!=remaindDialog"
        :onRemindTodos=onRemindTodos>
      </remaind-todo>
    </v-dialog> -->
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import AllDeleteTodo from './todoComponents/AllDeleteTodo.vue'
import NewTodo from './todoComponents/NewTodo.vue';
import DetailTodo from './todoComponents/DetailTodo.vue';
import RemaindTodo from './todoComponents/RemaindTodo.vue';
import Const from '../common/const';
import Axios from 'axios';
import { User } from '../interfaces/User';
import { Todos } from '../interfaces/Todos';
import AlertMsg from '../components/utilComponent/AlertMsg.vue';
import Util from '../common/util';

@Component({
  name: 'Todo',
  components: {
    NewTodo,
    AllDeleteTodo,
    DetailTodo,
    RemaindTodo,
    AlertMsg,
  },
})

export default class Todo extends Mixins(Const, Util) {
  $refs: any = {}
  // ユーザーID
  private user_id: User = this.$store.state.userInfo.user_id;
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
  // リマンダーダイアログ表示フラグ
  // private remaindDialog: boolean = false;
// 処理完了Msg
  private alertMsgs: string[] = [];
  // 処理完了Msgタイプ
  private alertType: 'error'|'success'|'' = '';
  // リマインドデータを初期化
  // private onRemindTodos: Todos[]  = [];
  mounted() {
    this.getTodos();
    // リマインダー管理
    // if(this.remaindDialog === false) {
    //   setInterval(this.checkReminder, 60000);
    // }
  };
  /**
   * リマインダー管理
   */
  // private checkReminder(): void {
  //   // 要リマインドデータを初期化
  //   this.onRemindTodos = [];
  //   // 現在時刻を取得
  //   const now = new Date();
  //   // 現在時刻とリマインドを比較
  //   this.bookMarkTodos.map((todo) => {
  //     let reminder = new Date((todo.reminderDate!.replace(/-/g,'/')) + ' ' + todo.reminderTime);
  //     if(reminder.getTime() < now.getTime()) {
  //       this.remaindDialog = true;
  //       this.onRemindTodos.push(todo);
  //     };
  //   });
  // };
  /**
   * todo一覧取得
   */
  private getTodos(): void {
    this.setLoading();
    this.bookMarkTodos = [];
    this.todos = [];
    Axios.get(`/api/todo/`).then((res) => {
      // 固定表示とその他を分別（ステータス完了非表示）
      if (this.finishFlag === false) {
        for (let todo of res.data.todos) {
          if (todo.state !== this.TOD0_STATUS.FINISH && todo.book_mark === this.TODO_BOOKMARK_FIXED) {
            this.bookMarkTodos.push(todo);
          } else if(todo.state !== this.TOD0_STATUS.FINISH) {
            this.todos.push(todo);
          } else {
            continue;
          }
        }
        return;
      };
      // 固定表示とその他を分別（ステータス完了表示）
      if (this.finishFlag === true) {
        for (let todo of res.data.todos) {
          if (todo.book_mark === this.TODO_BOOKMARK_FIXED) {
            this.bookMarkTodos.push(todo);
          } else {
            this.todos.push(todo);
          }
        }
        return;
      };
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  };
  /**
   * ステータスに応じた配色適用
   */
  private color(todo: Todos){
    switch (todo.state){
    case this.TOD0_STATUS.UNTREATED:
      return "#990000"
    case this.TOD0_STATUS.ON_THE_WAY:
      return "#003366"
    case this.TOD0_STATUS.ON_HPLD:
      return "#006600"
    default:
      return "#020202"
    }
  };
  // /**
  //  * 新規登録ダイアログを表示
  //  */
  // private newTodoOpen(): void {
  //   this.newDialog = true;
  // };
  // /**
  //  * 新規登録ダイアログを非表示
  //  */
  // private newTodoClose(): void {
  //   this.newDialog = false;
  // };
  /**
   * todo新規登録・削除・編集完了
   */
  private registered(succueseMsg: string): void {
    this.newDialog = false;
    this.allDeleteDialog = false;
    this.detailDialog = false;
    // this.remaindDialog = false;
    this.alertType = '';
    this.alertMsgs = [];
    this.alertType = 'success';
    this.alertMsgs.push(succueseMsg);
    this.getTodos();
  };
  /**
   * 全削除ダイアログを表示
   */
  // private allDeleteOpen(): void {
  //   this.allDeleteDialog = true;
  // };
  // /**
  //  * 全削除ダイアログを非表示
  //  */
  // private allDeleteClose(): void {
  //   this.allDeleteDialog = false;
  // };
  /**
   * 全削除成功
   */
  // private removeAll(succueseMsg: string): void {
  //   this.succueseMsg = succueseMsg;
  //   setTimeout(() => {
  //     this.succueseMsg = '';
  //   }, 3000);
  //   this.getTodos();
  // };
  /**
   * todo詳細
   */
  async goDetail(todo: Todos): Promise<void> {
    // 子コンポーネント生成後、初期をセット
    await (
      this.detailTodo = todo,
      this.detailDialog = true
    );
    // コンポーネントに初期をセット
    this.$refs.child.setInitializeValue();
  };
  /**
   * todo編集完了
   */
  // private updateTodo(succueseMsg: string): void {
  //   this.detailDialogClose();
  //   this.succueseMsg = succueseMsg;
  //   setTimeout(() => {
  //     this.succueseMsg = '';
  //   }, 3000);
  //   this.getTodos();
  // }
  /**
   * 削除成功
   */
  // private deleteTodo(succueseMsg: string): void {
  //   this.detailDialogClose();
  //   this.succueseMsg = succueseMsg;
  //   setTimeout(() => {
  //     this.succueseMsg = '';
  //   }, 3000);
  //   this.getTodos();
  // }
  /**
   * 詳細ダイアログを非表示
   */
  // private detailDialogClose(): void {
  //   this.detailDialog = false;
  // }
  /**
   * 各ダイアログから戻る
   */
  // private back(): void {
  //   if(this.newDialog = true) {
  //     this.newTodoClose();
  //   };
  //   if(this.allDeleteDialog = true) {
  //     this.allDeleteClose();
  //   };
  //   if(this.detailDialog = true) {
  //     this.detailDialogClose();
  //   };
  // };
  /**
   * リマインダーダイアログから戻る
   */
  // private backRemaind(): void {
  //   if(this.remaindDialog = true) {
  //     this.remaindDialog = false;
  //   };
  //   // DB値を変更となるため、フロントデータも更新
  //   this.getTodos();
  // };
};
</script>

<style scoped>
.bg {
  padding-top: 3%;
  left: 0;
  min-height: 1200px;
  background-size: contain;
  background: url("../../../public/images/todo.jpeg") center center / cover no-repeat fixed;
  background-attachment: fixed;
}
.container {
  padding: 3% 7%;
}
/* #switch{
  position: fixed;
  right: 5%;
  top: 10%;
} */
/* #plus-circle{
  position: fixed;
  right: 10%;
  bottom: 5%;
  transform: scale(2, 2);
} */
/* #delete-alert {
  position: fixed;
  right: 5%;
  bottom: 5%;
  transform: scale(2, 2);
} */
/* .trash{
  position: absolute;
  right: 0;
  top: 2%;
} */
.edit{
  position: absolute;
  right: 20px;
  top: 2%;
}
.new_line {
  white-space: pre-wrap;
  text-align: left;
}
hr {
  border: 1px solid #aaa7a7;
  text-align: left;
}
</style>
