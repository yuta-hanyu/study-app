<template>
  <div>
    <v-card
     color="amber lighten-5">
      <v-card-title class="justify-center title text-h6">ログアウト</v-card-title>
      <v-card-actions>
        <v-row justify="center" class="mb-auto">
          <v-col cols="6" align="right">
            <v-btn
              color="grey lighten-1"
              width="100px"
              rounded
              @click="back"
              elevation="20">
              戻る
            </v-btn>
          </v-col>
          <v-col cols="6">
            <v-btn
              @click="logout"
              width="100px"
              color="orange lighten-2"
              elevation="20"
              rounded>
              ログアウト
            </v-btn>
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../common/const'
import Axios from 'axios';

@Component({
  name: "Logout",
})

export default class Logout extends Mixins(Const){
  // 戻るボタン押下
  @Emit('back-logout')
  back(): void {};
  /**
   * ログアウト
  */
  private logout(): void {
    Axios.post('/api/logout').then((res) => {
      // ログアウト処理ができていなかった場合
      if(res.data.retultFlag == false) {
        window.alert(this.ERROR_MSG.LOGOUT_FAILD);
        return;
      };
      // storeのuserInfoを初期値へリセット
      this.$store.dispatch('resetUserInfo');
      // ログアウトダイアログを閉じる
      this.back();
      this.$router.push("/login");
    }).catch((e) => {
      window.alert(this.ERROR_MSG.SERVER_ERROR);
      console.log(e);
    });
  }
}
</script>

<style>

</style>
