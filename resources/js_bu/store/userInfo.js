/**
 * ログインユーザー情報の初期値
 * @param any state
 * @param any userInfo
 */
const getDefaultState = () => {
  return {
    loginUserName: '',
    userId: '',
    isAuth: false,
  }
};

// ユーザー情報は初期値をセット
const state = {
  getDefaultState,
  isDark: false,
  omitEmail: '',
}


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
   * ユーザー情報を初期値へリセット
   * @param any state
   * @param any userInfo
   */
  resetUserInfo(state) {
    Object.assign(state, getDefaultState());
  },
  /**
   * ダークモードの設定値を保存
   * @param any state
   * @param any isDark
   */
  setIsDark(state, isDark) {
    state.isDark = isDark.isDark;
  },
  /**
   * メールアドレスを保存
   * @param any state
   * @param any omitEmail
   */
   setOmitEmail(state, omitEmail) {
    state.omitEmail = omitEmail.omitEmail;
   },
};

const actions = {
  setLoginUser(context, userInfo) {
    context.commit('setLoginUser', userInfo);
  },
  setIsDark(context, isDark) {
    context.commit('setIsDark', isDark);
  },
  resetUserInfo(context) {
    context.commit('resetUserInfo');
  },
  setOmitEmail(context, omitEmail) {
    context.commit('setOmitEmail', omitEmail);
  },
};

const getters = {}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions
};
