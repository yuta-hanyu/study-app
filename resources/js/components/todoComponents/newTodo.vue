<template>
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
          <v-col cols="1">
            <v-btn
              @click="backTodos"
              color="blue lighten-4">
              戻る
            </v-btn>
          </v-col>
          <v-col cols="auto"></v-col>
          <v-col cols="1">
            <v-btn
              color="orange lighten-4"
              @click="todoRegister">
              登録
            </v-btn>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    </v-form>
  </v-card>
</template>

<script>
export default {
  data(){
    return {
      // 新規登録todo
      newTodo: {
        user_id: 1,
        title: '',
        content: '',
        state:'',
        bookMark: false
      },
    }
  },
  methods: {
    /**
     * 登録
     */
    todoRegister(){
      this.$emit('todo-register',this.newTodo);
      this.initialize();
    },
    /**
     * 戻る
     */
    backTodos(){
      this.initialize();
      this.$emit('back-todos');
    },
    /**
     * データ初期化
     */
    initialize(){
      Object.keys(this.newTodo).forEach(key => this.newTodo[key] = '');
    }
  },
}
</script>

<style>

</style>
