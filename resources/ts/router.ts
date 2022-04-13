import Vue from 'vue'
import VueRouter from 'vue-router'
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';
import Logout from './components/Logout.vue';
import Inquiry from './components/inquiry.vue';
import bookMark from './components/BookMark.vue';
import Verify from './components/Verify.vue';
import Account from './components/Account.vue'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {title: 'ログイン'}
    },
    {
      path: '/logout',
      name: 'Logout',
      component: Logout,
      meta: {isAuthenticated: true},
    },
    // {
    //   path: '/',
    //   name: 'Top',
    //   component: Top,
    //   meta: {isAuthenticated: true},
    // },
    {
      path: '/todo',
      name: 'Todo',
      component: Todo,
      meta: {isAuthenticated: true, title: 'タスク管理'},
    },
    {
      path: '/bookMark',
      name: 'bookMark',
      component: bookMark,
      meta: {isAuthenticated: true, title: 'ブックマーク管理'},
    },
    {
      path: '/verify/:token',
      name: 'verify',
      component: Verify,
      meta: {title: '会員本登録'},
    },
    {
      path: '/account',
      name: 'Account',
      component: Account,
      meta: {isAuthenticated: true, title: '会員情報'},
    },

  ]
});
//ログイン認証していない場合はリダイレクト
router.beforeEach((to, from, next) => {
  // ※vuexのisAuthに直接アクセスできないため、localStorageのisAuthにてガード判定を行う
  const userInfo: any = localStorage.getItem('study_app_userinfo');
  const userInfoObj = JSON.parse(userInfo);
  if (to.matched.some(record => record.meta.isAuthenticated)) {
    if (userInfoObj.userInfo.isAuth === false) {
      next({ name: 'Login' });
    } else {
      next();
    }
  }
  next();
});

export default router
