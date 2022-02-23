import Vue from 'vue';
import router from './router';
import App from './App.vue';
import Vuetify from 'vuetify';
import '@mdi/font/css/materialdesignicons.css';
import store from './store';

Vue.use(Vuetify);

new Vue({
  // el: '#app',
  vuetify: new Vuetify(),
  router,
  store,
  // components: { App }, // ルートコンポーネントの使用を宣言する
  // template: '<App />' // ルートコンポーネントを描画する
  render: h => h(App)
}).$mount('#app');

