<template>
  <div>
    <v-app>
      <header>
      <Header
        :isDark=isDark
        @change-Isdark="changeIsDark">
      </Header>
      </header>
      <main class="body">
      <RouterView />
      </main>
    </v-app>
  </div>
</template>

<script>
import Header from './global/Header.vue';
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';

export default {
  components: {
    Header,
    Todo,
    Top,
    Login
  },
  name: 'App',
  data() {
    return {
      // ダークモードフラグ
      isDark: this.$store.state.userInfo.isDark,
    }
  },
  mounted() {
    this.changeDarkMode();
  },
  methods:{
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
