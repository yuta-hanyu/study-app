<template>
  <div>
    <v-app>
      <header>
      <Header
        :isDark=isDark
        @change-isdark="changeIsDark"
        @logout-dialog="logoutDialogOpen">
      </Header>
      </header>
      <main class="body">
      <RouterView />
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
// import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';
import Logout from './components/Logout.vue';

@Component({
  components: {
    Header,
    // Todo,
    Top,
    Login,
    Logout
  },
})

export default class App extends Vue {
  // ダークモードフラグ
  isDark: boolean = this.$store.state.userInfo.isDark;
  // ログアウトダイアログ
  LogoutDialog: boolean = false;
  mounted() {
    this.changeDarkMode();
  }
  /**
  * ログアウトダイアログを表示
  */
  logoutDialogOpen(): void {
    this.LogoutDialog = true;
  };
  /**
  * ログアウトダイアログを非表示
  */
  backLogout(): void {
    this.LogoutDialog = false;
  };
  /**
  * ダークモード切替処理
  */
  changeDarkMode(): void {
    if(this.isDark) {
      document.getElementById('app')!.classList.remove('theme--light');
      document.getElementById('app')!.classList.add('theme--dark');
      // vuexに値をセット
      this.$store.dispatch('setIsDark',{
        isDark: this.isDark
      });
    } else {
      document.getElementById('app')!.classList.remove('theme--dark');
      document.getElementById('app')!.classList.add('theme--light');
      // vuexに値をセット
      this.$store.dispatch('setIsDark',{
        isDark: this.isDark
      });
    }
  }
  /**
  * ダークモードボタン押下
  */
  changeIsDark(): void {
    this.isDark = !this.isDark;
  }
  /**
   * ダークモードフラグの変化によって変換処理を実施
   */
  @Watch('isDark')
  onisDarkChanged() {this.changeDarkMode();}
}
</script>

<style scoped>
  .theme--dark {
    background-color: #666666;
    color: #fff;
  }
  body {
    background-color: #666666;
  }
</style>
