export default {
    menuItems: [
      {
        name: 'トップ',
        url: '/'
      },
      {
        name: 'TODO',
        url: '/todo'
      },
      {
        name: 'WORK',
        url: '#'
      },
      {
        name: 'SERVICE',
        url: '#'
      },
      {
        name: 'BLOG',
        url: '#'
      },
      // {
        // name: 'ログアウト',
        // url: '/logout'
        // url: '#'
      // }
    ],
    ERROR_MSG:
      {
        EXPAIRED_SESSION: 'セッションの有効期間が切れています。再度、ログインしてください',
        SERVER_ERROR: 'サーバーエラーが発生しました',
        LOGOUT_FAILD: 'ログアウトに失敗しました',
        LOGIN_FAILD: 'ログインに失敗しました',
        AUTH_FAILD: '認証エラーです'
      },
  }
