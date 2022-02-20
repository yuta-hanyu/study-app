import { User } from '../interfaces/User'

type Payload = User;
interface Context {
  commit: (name: any, Payload?: Payload) => void;
}
/**
 * ログインユーザー情報の初期値
 * @param any state
 * @param any userInfo
*/
const getDefaultState = (): User => {
  return {
    loginUserName: '',
    userId: '',
    isAuth: false,
  }
};
// ログイン情報(State)
type UserState = User & {
  isDark: boolean;
  omitEmail: string;
  // isAuth: boolean;
};
// ログイン情報(param）
interface userInfoParam {
  loginUserName: string
  userId: string
  isAuth: boolean
  isDark: boolean
  omitEmail: string
};

const userInfo = {
  // ユーザー情報は初期値をセット
  state: {
    getDefaultState,
    isDark: false,
    omitEmail: '',
  },
  mutations: {
    /**
     * ログインユーザー情報を保存
     * @param any state
     * @param any userInfo
     */
    setLoginUser(state: UserState, userInfo: userInfoParam) {
      state.loginUserName = userInfo.loginUserName
      state.userId = userInfo.userId,
      state.isAuth = true
    },
    /**
     * ユーザー情報を初期値へリセット
     * @param any state
     * @param any userInfo
     */
    resetUserInfo(state: UserState) {
      Object.assign(state, getDefaultState());
    },
    /**
     * ダークモードの設定値を保存
     * @param any state
     * @param any isDark
     */
    setIsDark(state: UserState, isDark: userInfoParam) {
      state.isDark = isDark.isDark;
    },
    /**
     * メールアドレスを保存
     * @param any state
     * @param any omitEmail
     */
    setOmitEmail(state: UserState, omitEmail: userInfoParam) {
      state.omitEmail = omitEmail.omitEmail;
    },
  },
  actions: {
    setLoginUser(context: Context, userInfo: userInfoParam) {
      context.commit('setLoginUser', userInfo);
    },
    setIsDark(context: Context, isDark: userInfoParam) {
      context.commit('setIsDark', isDark);
    },
    resetUserInfo(context: Context) {
      context.commit('resetUserInfo');
    },
    setOmitEmail(context: Context, omitEmail: userInfoParam) {
      context.commit('setOmitEmail', omitEmail);
    },
  },
};

export default userInfo
