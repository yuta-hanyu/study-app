import Vue from 'vue'
import Vuex from 'vuex'
import userInfo from './userInfo'
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
        'userInfo.userId',
        'userInfo.loginUserName',
        'userInfo.isAuth'
      ],
      storage: window.localStorage
    }),
    createPersistedState({
      key: 'study_app_isDark',
      paths: [
        'userInfo.isDark',
      ],
      storage: window.localStorage
    }),
    createPersistedState({
      key: 'study_app_omitEmail',
      paths: [
        'userInfo.omitEmail',
      ],
      storage: window.localStorage
    })
  ]
})
