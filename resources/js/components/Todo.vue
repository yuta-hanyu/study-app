<template>
  <div>
    <v-container fluid>
      <v-row v-if="this.succueseMsg" justify="center">
        <v-alert
          width="70%"
          align="center"
          dense
          text
          type="success">
          {{this.succueseMsg}}
        </v-alert>
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
        <div class="mt-5" align="left">固定済み</div>
        <v-banner single-line></v-banner>
        <v-col cols="3"
          v-for="(todo,index) in bookMarkTodos"
          :key=index
          v-show="todo.book_mark === 1">
          <v-card
            @click="todoDetail(todo)"
            elevation="10"
            class="mx-auto"
            outlined
            :style="{backgroundColor: color(todo)}">
          <!-- <v-icon
            color="green"
            class="edit"
            @click="todoEdit(todo)">
            mdi-clipboard-edit
          </v-icon>
          <v-icon
            color="red"
            class="trash"
            @click="todoDelete(todo)">
            mdi-delete
          </v-icon> -->
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
          <v-card-text>
            {{todo.content}}
          </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <!-- ブックマークなし -->
      <v-row justify="center">
        <div class="mt-5" align="left">その他</div>
        <v-banner single-line></v-banner>
        <v-col cols="3"
          v-for="(todo,index) in todos"
          :key=index
          v-show="todo.book_mark !== 1">
          <v-card
            elevation="10"
            class="mx-auto"
            outlined
            :style="{backgroundColor: color(todo)}">
          <!-- <v-icon
            color="green"
            class="edit"
            @click="todoEdit(todo)">
            mdi-clipboard-edit
          </v-icon>
          <v-icon
            color="red"
            class="trash"
            @click="todoDelete(todo)">
            mdi-delete
          </v-icon> -->
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
          <v-card-text>
            {{todo.content}}
          </v-card-text>
          </v-card>
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
          @click="newTodoOpen"
        >
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
          v-on="on"
        >
        mdi-delete-alert
        </v-icon>
      </template>
      <span>todo一括削除</span>
    </v-tooltip>
    <!-- 新規作成ダイアログ -->
    <v-dialog
      v-model="newDialog"
      width=70%>
      <new-todo
        @back-todos="back"
        @todo-register="todoRegister"
        :userId=userId>
      </new-todo>
    </v-dialog>
    <!-- 一括削除 -->
    <v-dialog
      v-model="allDeleteDialog"
      height="200px"
      width="400px"
      transition="dialog-top-transition"
    >
      <all-delete-todo
        @back-todos="back"
        @remove-all="removeAll"
        :userId=userId>
      </all-delete-todo>
    </v-dialog>
      <!-- 編集ダイアログ -->
      <!-- <v-dialog
      v-model="editDialog"
      max-width=70%
      >
        <v-card
          class="mx-auto"
          outlined
        >
        <v-card-title>{{editTodo.title}}</v-card-title>
        <v-card-subtitle>
        </v-card-subtitle>å
              </v-card>
      </v-dialog> -->
  </div>
</template>

<script>
import allDeleteTodo from './todoComponents/allDeleteTodo.vue'
import newTodo from './todoComponents/newTodo.vue'

export default {
  components: {
    newTodo,
    allDeleteTodo
  },
  name: 'Todo',
  data(){
    return{
      // ユーザーID
      userId: 1,
      // 固定表示一覧
      bookMarkTodos: [],
      // その他一覧
      todos: [],
      // 新規登録ダイアログ表示フラグ
      newDialog: false,
      // 新規登録ダイアログ表示フラグ
      allDeleteDialog: false,
      // 詳細ダイアログ表示フラグ
      detailDialog: false,
      // ステータス完了表示フラグ
      finishFlag: false,
      // 処理成功MSG
      succueseMsg: '',
      editTodo: {
        id: '',
        title: '',
        content: '',
        state:'',
        bookMark: false
      }
    }
  },
  mounted() {
    this.getTodos();
  },
  methods:{
    /**
     * todo一覧表示
     */
    getTodos() {
      this.bookMarkTodos = [];
      this.todos = [];
      axios.get(`/api/todos/${this.userId}`).then((res) => {
        console.log(res.data.result);
        let todos = [];
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
        console.log(e);
        window.alert("データの取得に失敗しました")
      });
    },
    /**
     * ステータスに応じた配色適用
     */
    color(todo){
      switch (todo.state){
      // 未対応
      case 0:
        return "#FFCCBC"
      // 対応中
      case 1:
        return "#E1F5FE"
      // 保留
      case 2:
        return "#EDE7F6"
      // 対応済み
      default:
        return "#E0E0E0"
      }
    },
    /**
     * 新規登録ダイアログを表示
     */
    newTodoOpen(){
      this.newDialog = true;
    },
    /**
     * 新規登録ダイアログを非表示
     */
    newTodoClose(){
      this.newDialog = false;
    },
    /**
     * todo新規登録
     */
    todoRegister(succueseMsg) {
      console.log(succueseMsg);
      this.newTodoClose();
      this.succueseMsg = succueseMsg;
      setTimeout(() => {
        this.succueseMsg = '';
      }, 3000);
      this.getTodos();
    },
    /**
     * 全削除ダイアログを表示
     */
    allDeleteOpen(){
      this.allDeleteDialog = true;
    },
    /**
     * 全削除ダイアログを非表示
     */
    allDeleteClose(){
      this.allDeleteDialog = false;
    },
    /**
     * 全削除成功
     */
    removeAll(succueseMsg){
      this.succueseMsg = succueseMsg;
      setTimeout(() => {
        this.succueseMsg = '';
      }, 3000);
      this.getTodos();
    },
    /**
     * 各ダイアログから戻る
     */
    back(){
      if(this.newDialog = true){
        this.newTodoClose();
      }
      if(this.allDeleteDialog = true){
        this.allDeleteClose();
      }
    },
    /**
     * todo削除
     */
    // todoDelete(todo) {
    //   if(window.confirm("削除して良いですか？")){
    //     axios.delete(`/api/todo/${todo.id}`).then((res) => {
    //       this.getTodos();
    //     }).catch((e) => {
    //       console.log(e);
    //     });
    //   } else {
    //     return;
    //   }
    // },
    /**
     * todo詳細
     */
    todoDetail(todo){
      this.editDialog = true
      this.editTodo.id = todo.id
      this.editTodo.title = todo.title
      this.editTodo.content = todo.content
      this.editTodo.state = todo.state
      this.editTodo.bookMark =  false
    },
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
</style>
