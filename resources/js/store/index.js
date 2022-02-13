import Vue from 'vue'
import Vuex from 'vuex'
import userInfo from './userInfo'
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {

  },
  mutations: {},
  actions: {},
  getters: {},
  modules: {
    userInfo
  },
  // localStorageにユーザー情報を保存
  plugins: [createPersistedState({
    key: 'study_app',
    paths: [
      'userInfo.userId',
      'userInfo.loginUserName',
      'userInfo.isAuth'
    ],
    storage: window.localStorage
  })]
})
