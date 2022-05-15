<template>
  <div id="main">
    <div class="fof">
      <h1>SNS認証情報を確認中・・・</h1>
      <h3>from StudyApp</h3>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Axios from 'axios';
import Util from '../common/util';
import Const from '../common/const';

@Component ({
  name: 'SnSConfirming'
})

export default class SnSConfirming extends Mixins(Const, Util){
  mounted() {
    const token = window.location.search;
    this.signinWithProvider(token);
  }
  /**
   * SNS認証情報登録・ログイン処理
   */
  private signinWithProvider(token: string):void {
    Axios.post(`/api/login/google/callback${token}`, {}).then((res) => {
      // vuexにユーザー情報をセット
      this.$store.dispatch('setLoginUser', {
        name: res.data.userInfo.name,
        user_id: res.data.userInfo.user_id,
      });
      this.$router.push("/top");
    }).catch((e) => {
      this.serverError(e);
    });
  }
}
</script>
<style>
* {
  transition: all 0.6s;
}
html {
  height: 100%;
}
body{
  font-family: 'Lato', sans-serif;
  color: #888;
  margin: 0;
}
#main{
  display: table;
  width: 100%;
  height: 100vh;
  text-align: center;
}
.fof{
  display: table-cell;
  vertical-align: middle;
}
.fof h1{
  font-size: 50px;
  display: inline-block;
  padding-right: 12px;
  animation: type .5s alternate infinite;
}
@keyframes type{
  from{box-shadow: inset -3px 0px 0px #888;}
  to{box-shadow: inset -3px 0px 0px transparent;}
}
</style>
