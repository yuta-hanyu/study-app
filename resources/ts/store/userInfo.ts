import { User } from '../interfaces/User'

type Payload = User;
interface Context {
  commit: (name: any, Payload?: Payload) => void;
}
type UserState = User & {
  omitEmail: string;
  isLoading: boolean;
};
// ログイン情報(param）
interface userInfoParam {
  loginUserName: string
  userId: string
  isAuth: boolean
  omitEmail: string
  isLoading: boolean
};

const userInfo = {
  state: {
    loginUserName: '',
    userId: '',
    isAuth: false,
    omitEmail: '',
    isLoading: false,
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
      Object.assign(state,
        {
          loginUserName: '',
          userId: '',
          isAuth: false,
        });
    },
    /**
     * メールアドレスを保存
     * @param any state
     * @param any omitEmail
     */
    setOmitEmail(state: UserState, omitEmail: userInfoParam) {
      state.omitEmail = omitEmail.omitEmail;
    },
    /**
     * ローディングの設定値を保存
     * @param any state
     * @param any isLoading
     */
     setIsLoading(state: UserState, isLoading: userInfoParam) {
      state.isLoading = isLoading.isLoading;
    },
  },
  actions: {
    setLoginUser(context: Context, userInfo: userInfoParam) {
      context.commit('setLoginUser', userInfo);
    },
    resetUserInfo(context: Context) {
      context.commit('resetUserInfo');
    },
    setOmitEmail(context: Context, omitEmail: userInfoParam) {
      context.commit('setOmitEmail', omitEmail);
    },
    setIsLoading(context: Context, isLoading: userInfoParam) {
      context.commit('setIsLoading', isLoading);
    },
  },
};

export default userInfo
