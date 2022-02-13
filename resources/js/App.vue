<template>
  <div>
    <v-app>
      <header>
      <Header
        :isDark=isDark
        @change-Isdark="changeIsDark"
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

<script>
import Header from './global/Header.vue';
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';
import Logout from './components/Logout.vue';

export default {
  components: {
    Header,
    Todo,
    Top,
    Login,
    Logout
  },
  name: 'App',
  data() {
    return {
      // ダークモードフラグ
      isDark: this.$store.state.userInfo.isDark,
      // ログアウトダイアログ
      LogoutDialog: false,
    }
  },
  mounted() {
    this.changeDarkMode();
  },
  methods:{
    /**
     * ログアウトダイアログを表示
     */
    logoutDialogOpen() {
      this.LogoutDialog = true;
    },
    /**
     * ログアウトダイアログを非表示
     */
    backLogout() {
      this.LogoutDialog = false;
    },
    /**
     * ダークモード切替処理
     */
    changeDarkMode() {
      if (this.isDark) {
        document.getElementById('app').classList.remove('theme--light');
        document.getElementById('app').classList.add('theme--dark');
        // vuexに値をセット
        this.$store.dispatch('userInfo/setIsDark',{
          isDark: this.isDark
        });
      } else {
        document.getElementById('app').classList.remove('theme--dark');
        document.getElementById('app').classList.add('theme--light');
        // vuexに値をセット
        this.$store.dispatch('userInfo/setIsDark',{
          isDark: this.isDark
        });
      }
    },
    /**
     * ダークモードボタン押下
     */
    changeIsDark() {
      this.isDark = !this.isDark;
    }
  },
  computed: {
  },
  /**
   * ダークモードフラグの変化によって変換処理を実施
   */
  watch: {
    isDark: function() {
      this.changeDarkMode();
    }
  }
};
</script>

<style scoped>
  .theme--dark {
    background-color: #666666;
    color: #fff;
  }
</style>
