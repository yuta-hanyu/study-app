<template>
  <div>
    <v-app>
      <Header/>
      <main>
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

    }
  },
  methods:{
  }
};
</script>

<style scoped>
</style>
