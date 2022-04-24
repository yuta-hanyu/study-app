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

        <draggable @end="sortChange(true)" v-model="bookMarkTodos" flat draggable=".bookMarkTodos" class="d-flex flex-wrap justify-center">
        <div class="bookMarkTodos mx-2 my-2"
          v-for="(todo,index) in bookMarkTodos"
          :key=index
          style="min-width:200px">
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
        </div>
        </draggable>

      </v-row>
      <!-- ブックマークなし -->
      <v-row justify="center">
        <v-col cols="12">
          <div class="mt-5" align="left">その他</div>
          <hr class="hr-down mt-1 mb-1">
        </v-col>
        <draggable @end="sortChange" v-model="todos" flat draggable=".todos" class="d-flex flex-wrap justify-center">
          <div class="todos mx-2 my-2"
            v-for="(todo,index) in todos"
            :key=index
            style="min-width:200px">
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
          </div>
        </draggable>
      </v-row>
    </v-container>
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
import draggable from 'vuedraggable'

@Component({
  name: 'Todo',
  components: {
    NewTodo,
    AllDeleteTodo,
    DetailTodo,
    RemaindTodo,
    AlertMsg,
    draggable
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
   * todo一覧取得
   */
  private getTodos(): void {
    this.setLoading();
    this.bookMarkTodos = [];
    this.todos = [];
    Axios.post(`/api/todo/`,{
      finishFlag: this.finishFlag
    }).then((res) => {
      this.bookMarkTodos = res.data.bookMarkTodos;
      this.todos = res.data.todos;
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
  /**
   * ソート順変更
   */
  private sortChange(flag: boolean): void {
    this.setLoading();
    this.alertType = '';
    this.alertMsgs = [];
    let targetTodos: Todos[] = [];
    flag === true ? targetTodos = this.bookMarkTodos : targetTodos = this.todos;
    targetTodos.map((todo, index) => {
      todo.sort_order = index;
    })
    Axios.post(`/api/todo/update_sort`,{
      targetTodos :targetTodos
    }).then((res) => {
      this.alertType = 'success';
      this.alertMsgs.push(`タスクの並び順を${this.SUCCESS_MSG.EDIT_SUCCESS}`);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
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
