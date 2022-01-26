<template>
<div>
  <v-app>
  <div>
    <Header/>
    <router-link to="/todo">todo</router-link>
    <main>
    <RouterView />
    </main>
  </div>
  </v-app>
</div>
</template>

<script>
import Header from './global/Header.vue';
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';



export default {
  components: {
    Header,
    Todo,
    Top
  },
  name: 'App',

  data() {
    return {
    }
  },
};
</script>
