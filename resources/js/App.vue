<template>
  <div>
    <v-app>
      <header>
      <Header/>
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
      isDark: true,
    }
  },
  mounted() {
    this.darkModeMediaQuery();
  },
  methods:{
    darkModeMediaQuery() {
      // console.log(window.matchMedia('(prefers-color-scheme: light)'));
      // console.log(window.matchMedia('(prefers-color-scheme: light)').matches)
      // let isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      if (this.isDark) { // Dark
        console.log('ダークモード');
        console.log(document.getElementById('app'))
          document.getElementById('app').classList.remove('theme--light');
          document.getElementById('app').classList.add('theme--dark')
      } else { // Light
        console.log('ライトモード');
          document.getElementById('app').classList.remove('theme--dark');
          document.getElementById('app').classList.add('theme--light')
      }
    }
  },
  computed: {
  },
  watch: {
    isDark: function() {
      this.darkModeMediaQuery();
    }
  }
};
</script>

<style scoped>
/* @media (prefers-color-scheme: dark) {
  body {
    background-color: #666666;
    color: #fff;
  }
} */
.theme--dark {
    background-color: #666666;
    color: #fff;
}
</style>
