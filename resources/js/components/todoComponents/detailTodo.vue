<template>
  <v-card py="3">
    <v-card-title class="justify-center title text-h4">Todo編集・削除</v-card-title>
    <!-- <v-row
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
    </v-row> -->
    <v-form ref="form">
    <v-row class="px-6">
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
      <v-col cols="12" height='10%'>
      <v-radio-group
        value="editTodo.state"
        v-model="editTodo.state"
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
        v-model="editTodo.bookMark"
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
    detailTodo: Object
  },
  data() {
    return {
      editTodo: {
        userId:'',
        title: '',
        content: '',
        state: '',
        bookMark: '',
      }
    }
  },
  mounted() {
    this.editTodo.userId = this.detailTodo.user_id,
    this.editTodo.title = this.detailTodo.title,
    this.editTodo.content = this.detailTodo.content,
    this.editTodo.state = this.detailTodo.state,
    this.editTodo.bookMark = this.detailTodo.book_mark
  },
  methods: {
    /**
     * 戻る
     */
    backTodos(){
      // this.initialize();
      this.$emit('back-todos');
    },
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
