import Vue from 'vue'
import VueRouter from 'vue-router'
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';
import Store from './store/index.js';


Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
          path: '/login',
          name: 'Login',
          component: Login
        },
        {
          path: '/',
          name: 'Top',
          component: Top,
          meta: {isAuthenticated: true},
        },
        {
          path: '/todo',
          name: 'Todo',
          component: Todo,
          meta: {isAuthenticated: true},
        },
    ]
});
//ログイン認証していない場合はリダイレクト
router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.isAuthenticated)) {
    if (Store.state.userInfo.isAuth === false) {
      next({ name: 'Login' });
    } else {
      next();
    }
  }
  next();
});

export default router
