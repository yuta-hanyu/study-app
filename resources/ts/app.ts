import Vue from 'vue';
import router from './router';
import App from './App.vue';
import Vuetify from 'vuetify';
import '@mdi/font/css/materialdesignicons.css';
import store from './store';
import VueGtm from 'vue-gtm';
// Vue.use(Vuetify);

Vue.use(
  Vuetify,
  VueGtm, {
    id: 'GTM-MJJZBCG',
  }
);

new Vue({
  vuetify: new Vuetify(),
  router,
  store,
  render: h => h(App)
}).$mount('#app');

