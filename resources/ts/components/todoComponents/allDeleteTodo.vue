<template>
<v-row align-content="center">
  <v-card class="text-center">
    <v-sheet
      class="px-7 pt-7 pb-4 mx-auto text-center d-inline-block"
      color="black"
      dark
      height="200px"
      width="400px"
    >
      <div class="grey--text text--lighten-1 text-h6 mb-4" align="center">
        全てのtodoを削除しますか？<br>
        ※ 元には戻りません
      </div>
      <v-row justify="center">
        <v-col cols="4">
          <v-btn
            class="ma-1"
            color="grey"
            plain
            @click="backTodos">
            <div class="text-h6">戻る</div>
          </v-btn>
        </v-col>
        <v-col cols="4">
          <v-btn
            class="ma-1"
            color="error"
            plain
            @click="removeAll">
            <div class="text-h6">削除</div>
          </v-btn>
        </v-col>
      </v-row>
    </v-sheet>
  </v-card>
</v-row>
</template>

<script>
export default {
  props: {
    userId: Number
  },
  data() {
    return {
      // 削除成功時MSG
      succueseMsg: ''
    }
  },
  methods: {
    /**
     * 戻るボタン押下
     */
    backTodos(){
      this.$emit('back-todos');
    },
    /**
     * 削除ボタン押下
     */
    removeAll(){
      axios.delete(`/api/todos/${this.userId}`).then((res) => {
        console.log('成功');
        this.succueseMsg = 'todoを全て削除しました'
        this.$emit('remove-all', this.succueseMsg);
        this.backTodos();
      }).catch((e) => {
        window.alert('データの削除に失敗しました')
        console.log(e);
      });
    }
  }
}
</script>

<style>

</style>
