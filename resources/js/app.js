import Vue from 'vue'
import router from './router'
import App from './App.vue'
import Vuetify from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import storeAuth from './store/auth'

Vue.use(Vuetify);

new Vue({
  el: '#app',
  storeAuth,
  vuetify: new Vuetify(),
  router, // ルーティングの定義を読み込む
  components: { App }, // ルートコンポーネントの使用を宣言する
  template: '<App />' // ルートコンポーネントを描画する
})

