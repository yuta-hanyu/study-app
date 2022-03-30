<template>
  <div>
    <v-app>
      <header>
        <Header
          v-show="headerFlag"
          @logout-dialog="logoutDialogOpen">
        </Header>
      </header>
      <main class="body pt-15">
      <RouterView />
      <loading :loadingDialog="IsLoadingShow" />
      <v-dialog
        persistent
        v-model="LogoutDialog"
        height="200px"
        width="300px">
        <logout
          @back-logout="backLogout">
        </logout>
      </v-dialog>
      </main>
    </v-app>
  </div>
</template>

<script lang="ts">
import { Component, Ref, Watch} from 'vue-property-decorator';
import Vue from 'vue';
import Header from './global/Header.vue';
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';
import Logout from './components/Logout.vue';
import BookMark from './components/BookMark.vue';
import Loading from './global/Loading.vue'

@Component({
  components: {
    Header,
    Todo,
    Top,
    Login,
    Logout,
    BookMark,
    Loading,
  },
})

export default class App extends Vue {
  // ログアウトダイアログ
  private LogoutDialog: boolean = false;
  // ヘッダー表示判定
  get headerFlag(): boolean {
    return this.$store.state.userInfo.isAuth ? true : false;
  };
  // ローディング表示判定
  get IsLoadingShow() {
    return this.$store.state.userInfo.isLoading;
  }
  /**
  * ログアウトダイアログを表示
  */
  private logoutDialogOpen(): void {
    this.LogoutDialog = true;
  };
  /**
  * ログアウトダイアログを非表示
  */
  backLogout(): void {
    this.LogoutDialog = false;
  };
}
</script>

<style lang="scss">
  .theme--light.v-application {
    background-color: #666666;
    color: #fff;
  }
  .v-application{
    font-family: "M Plus 1c" !important;
    font-weight: bolder;
}
</style>
