<template>
  <v-card py="3">
    <v-card-title class="justify-center title text-h4">Todo追加</v-card-title>
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
      <v-col cols="12" height='10%'>
      <v-radio-group
        v-model="newTodo.state"
        row
        label="ステータス(必須)"
        class="my-0"
      >
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
      <v-col cols="12" height='10%'>
      <v-checkbox
        v-model="newTodo.bookMark"
        label="上部へ固定"
        value=1
        class="my-0"
      ></v-checkbox>
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

<script>
export default {
  props: {
    userId: Number
  },
  data(){
    return {
      // ログインユーザーID
      // userId: '1',
      // 新規登録todo
      newTodo: {
        title: '',
        content: '',
        state: '',
        bookMark: ''
      },
      // フォームバリデーションエラー
      errors:[],
      // 登録成功時MSG
      succueseMsg: ''
    }
  },
  methods: {
    /**
     * 登録
     */
    todoRegister() {
      // エラーMSGリセット
      this.errors = [];
      // 成功MSGリセット
      this.succueseMsg = '',
      axios.post('/api/todos', {
        title: this.newTodo.title,
        content: this.newTodo.content,
        state: this.newTodo.state,
        bookMark: this.newTodo.bookMark,
        userId : this.userId
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
    },
    /**
     * エラーメッセージをオブジェクトから配列へ変換
     */
    changeErrors(message) {
      for (let [key, value] of Object.entries(message)) {
        this.errors.push(value[0]);
      }
      return this.errors;
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
      this.errors = [];
    }
  },
  // computed: {
    /**
     * 登録ボタン活性判定
     */
    // isDisabled(){
    //   if(!this.newTodo.title || this.newTodo.title.length > 15){
    //     return true;
    //   }
    //   if(!this.newTodo.state){
    //     return true;
    //   }
    //     return false;
    // }
  // }
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
