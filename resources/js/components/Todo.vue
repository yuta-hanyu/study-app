<template>
  <div>
    <v-container>
      <v-row>
        <v-col cols="4"
          v-for="(todo,index) in todos"
          :key=index
        >
          <v-card
            class="mx-auto"
            outlined
            :style="{backgroundColor: color(todo)}"
          >
          <v-icon
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
          </v-icon>
          <v-card-title>{{todo.title}}</v-card-title>
          <v-card-subtitle>
            <span v-if="todo.state === 1">
            未対応
            </span>
            <span v-else-if="todo.state === 2">
            対応中
            </span>
            <span v-else-if="todo.state === 3">
            保留
            </span>
            <span v-else>
            完了
            </span>
          </v-card-subtitle>
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
        @todo-register="todoRegister">
      </new-todo>
    </v-dialog>
    <!-- 一括削除 -->
    <v-dialog
      v-model="allDeleteDialog"
      height="300px"
      width="400px"
      transition="dialog-top-transition"
    >
      <all-delete-todo
        @back-todos="back">
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
    allDeleteTodo },
  name: 'Todo',
  data(){
    return{
      // 新規登録ダイアログ表示フラグ
      newDialog: false,
      // 新規登録ダイアログ表示フラグ
      allDeleteDialog: false,
      // 編集ダイアログ表示フラグ
      editDialog: false,
      // todo一覧
      todos: '',
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
      axios.get('/api/todos').then((res) => {
        this.todos = res.data;
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
      case 0:
        return "#FFCDD2"
      case 1:
        return "#BBDEFB"
      case 2:
        return "#80CBC4"
      default:
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
     * 全削除ダイアログを表示
     */
    allDeleteOpen(){
      this.allDeleteDialog = true;
    },
    /**
     * 各ダイアログから戻る
     */
    back(){
      if(this.newDialog = true){
        // Object.keys(this.newTodo).forEach(key => delete this.newTodo[key])
        this.newTodoClose();
      }
    },
    /**
     * todo新規登録
     */
    todoRegister() {
      this.newTodoClose();
      this.getTodos();
    },
    /**
     * todo削除
     */
    todoDelete(todo) {
      if(window.confirm("削除して良いですか？")){
        axios.delete(`/api/todos/${todo.id}`).then((res) => {
          this.getTodos();
        }).catch((e) => {
          console.log(e);
        });
      } else {
        return;
      }
    },
    /**
     * todo編集
     */
    todoEdit(todo){
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
