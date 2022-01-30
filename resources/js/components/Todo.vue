<template>
  <div>
    <v-container>
      <v-row>
        <v-col cols="6"
        v-for="(todo,index) in todos"
        :key=index
        >
          <v-card
            class="mx-auto"
            outlined
            @click="edit(todo)"
            :style="{backgroundColor: color(todo)}"
          >
          <v-card-title>{{todo.title}}</v-card-title>
          <v-card-subtitle>
            <span v-if="todo.state === 1">
            未対応
            </span>
            <span v-else-if="todo.state === 2">
            対応中
            </span>
            <span v-else>
            保留
            </span>
          </v-card-subtitle>
          <v-card-text>
            {{todo.content}}
          </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    <v-icon
    id="plus-circle"
    @click="newTodoOpen"
    >mdi-plus-circle</v-icon>
    </v-container>

      <!-- 新規作成ダイアログ -->
      <v-dialog
      v-model="newDialog"
      width=70%
      height="80%">
      <v-card py="3">
        <v-card-title class="justify-center">Todo追加</v-card-title>
        <v-form ref="form">
        <v-row class="px-6">
          <v-col cols="12">
          <v-text-field
            v-model="newTodo.title"
            :counter="15"
            label="タイトル"
            required
          ></v-text-field>
          </v-col>
          <v-col cols="12">
          <v-textarea
            v-model="newTodo.content"
            :counter="255"
            label="コンテンツ"
          ></v-textarea>
          </v-col>
          <v-col cols="12" height='10%'>
          <v-radio-group
            v-model="newTodo.state"
            row
            label="ステータス"
            class="my-0"
          >
            <v-radio
              label="未対応"
              value=1
            ></v-radio>
            <v-radio
              label="対応中"
              value=2
            ></v-radio>
            <v-radio
              label="保留"
              value=3
            ></v-radio>
            <v-radio
              label="完了"
              value=4
            ></v-radio>
          </v-radio-group>
          </v-col>
          <v-col cols="12" height='10%'>
          <v-checkbox
            v-model="newTodo.bookMark"
            label="ブックマーク登録"
            class="my-0"
          ></v-checkbox>
          </v-col>
          <v-col cols="12">
            <v-row justify="center">
              <v-col cols="2" justify="center">
                <v-btn
                  @click="back"
                  color="blue lighten-4">
                  戻る
                </v-btn>
              </v-col>
              <v-col cols="2" justify="center">
                <v-btn color="orange lighten-4">
                  登録
                </v-btn>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
        </v-form>
      </v-card>
      </v-dialog>

      <!-- 編集ダイアログ -->
      <v-dialog
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
      </v-dialog>
  </div>

</template>

<script>
export default {
  name: 'Todo',
  data(){
    return{
      newDialog: false,
      editDialog: false,
      todos:[
        {
          id: 1,
          title: 'aaa',
          content: 'あああああああああああああ',
          state: 1,
          bookMark: false
        },
        {
          id: 2,
          title: 'bbb',
          content: 'あああああああああああああ',
          state: 2,
          bookMark: false
        },
        {
          id: 3,
          title: 'ccc',
          content: 'あああああああああああああ',
          state: 3,
          bookMark: false
        },
      ],
      newTodo: {
        id: '',
        title: '',
        content: '',
        state:'',
        bookMark: false
      },
      editTodo: {
          id: '',
          title: '',
          content: '',
          state:'',
          bookMark: false
      }
    }
  },
  methods:{
    edit(todo){
      this.editDialog = true
      this.editTodo.id = todo.id
      this.editTodo.title = todo.title
      this.editTodo.content = todo.content
      this.editTodo.state = todo.state
      this.editTodo.bookMark =  false
    },
    newTodoOpen(){
      this.newDialog = true;
    },
    color(todo){
      switch (todo.state){
      case 1:
        return "#FFCDD2"
      case 2:
        return "#BBDEFB"
      case 3:
        return "#80CBC4"
      default:
      }
    },
    // 戻る押下時に入力内容リセット
    back(){
      if(this.newDialog = true){
        Object.keys(this.newTodo).forEach(key => delete this.newTodo[key])
        this.newDialog = false;
      }
    },
  }
}
</script>

<style scoped>
#plus-circle{
    position: fixed;
    right: 5%;
    bottom: 5%;
    transform: scale(2, 2);
}
</style>
