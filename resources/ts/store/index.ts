import Vue from 'vue';
import Vuex from 'vuex';
import userInfo from './userInfo';
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    userInfo: userInfo
  },
  // localStorageにユーザー情報を保存
  plugins: [
    createPersistedState({
      key: 'study_app_userinfo',
      paths: [
        'userInfo.user_id',
        'userInfo.name',
        'userInfo.isAuth',
        'userInfo.omitEmail',
      ],
      storage: window.localStorage
    }),
  ]
})
