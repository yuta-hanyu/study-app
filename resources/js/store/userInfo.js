const state = {
  loginUserName: '',
  userId: '',
  isAuth: false,
  isDark: false,
};
const mutations = {
  /**
   * ログインユーザー情報を保存
   * @param any state
   * @param any userInfo
   */
  setLoginUser(state, userInfo) {
    state.loginUserName = userInfo.name
    state.userId = userInfo.userId,
    state.isAuth = true
  },
  /**
   * ダークモードの設定値を保存
   * @param any state
   * @param any isDark
   */
  setIsDark(state, isDark) {
    state.isDark = isDark.isDark;
  }
};
const actions = {
  setLoginUser(context, userInfo) {
    context.commit('setLoginUser', userInfo);
  },
  setIsDark(context, isDark) {
    context.commit('setIsDark', isDark);
  }
};
const getters = {}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions
};
