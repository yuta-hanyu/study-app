<template>
  <v-sheet
    class="px-7 pt-7 pb-4 mx-auto text-center d-inline-block"
    color="black"
    dark>
    <div class="grey--text text--lighten-1 text-h5 mb-4" align="center">
      リマインダー
    </div>
    <div class="grey--text text--lighten-1 mb-4" align="center">
      以下のTodoをお忘れですか？
    </div>
    <v-list
      flat
      subheader>
      <v-list-item-group
        multiple>
        <v-list-item
          v-for="(todo,index) in onRemindTodos"
          :key=index
          class="list_item">
            <v-list-item-content class="text-left">
              <v-list-item-title>・ {{todo.title}}</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
    </v-list>
    <v-row justify="center">
      <v-col cols="12">
        <v-btn
          class="ma-1"
          color="grey"
          plain
          @click="resolveRemaind">
          <div class="text-h6">OK</div>
        </v-btn>
      </v-col>
    </v-row>
  </v-sheet>
</template>

<script lang="ts">
import {Component, Prop, Emit, Mixins} from 'vue-property-decorator';
import Const from '../../common/const';
import Axios from 'axios';
import { Todos } from '../../interfaces/Todos';

@Component({
  name: 'RemaindTodo',
})

export default class RemaindTodo extends Mixins(Const) {
  @Prop({type: Array, default: false})
    onRemindTodos!: Todos[];
  // OKボタン押下
  @Emit('back-todos')
    backTodos(): void {
  };
  /**
   * OKボタン押下
   */
  private resolveRemaind(): void {
    // リマインダーONとなるtodoのidを取得
    let ids = this.onRemindTodos.map((todo) => {
      return todo.id;
    });
    Axios.put(`/api/todo/updateRemaind`, {
      userId: this.$store.state.userInfo.userId,
      id: ids,
    }).then((res) => {
      this.backTodos();
    }).catch((e) => {
      window.alert(this.ERROR_MSG.UPDATE_ERROR)
      console.log(e);
    });
  }
}
</script>

<style scoped>
  .list_item {
    height: 40px;
  }
</style>
