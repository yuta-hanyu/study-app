<template>
  <v-card class="text-center">
    <v-sheet
      class="px-7 pt-7 pb-4 mx-auto text-center d-inline-block"
      color="black"
      dark
      width="400px">
      <div class="grey--text text--lighten-1 text-h6 mb-4" align="center">
        全てのタスクを削除しますか？<br>
        ※ 元には戻りません
      </div>
      <v-row justify="center">
        <v-col cols="4">
          <v-btn
            class="ma-1"
            color="grey"
            plain
            @click="back">
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
</template>

<script lang="ts">
import {Component, Emit, Mixins} from 'vue-property-decorator';
import Axios from 'axios';
import Const from '../../common/const';
import Util from '../../common/util';

@Component({
  name: 'AllDeleteTodo',
})

export default class AllDeleteTodo extends Mixins(Util, Const) {
  // 戻るボタン押下
  @Emit('back')
    back(): void {};
  // 削除ボタン押下
  @Emit('remove-all')
    emitRemoveAll(succueseMsg: string): void {};
  /**
   * 削除ボタン押下
   */
  private removeAll(): void {
    this.setLoading();
    Axios.post(`/api/todo/remove_all`).then((res) => {
      let succueseMsg = `タスクを${this.SUCCESS_MSG.ALL_ELETE_SUCCESS}`
      this.emitRemoveAll(succueseMsg);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
}
</script>
