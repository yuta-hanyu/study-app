import Vue from 'vue'
import VueRouter from 'vue-router'
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';


// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
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
          meta: {
            isAuthenticated: true,
          },
        },
        {
          path: '/todo',
          name: 'Todo',
          component: Todo
        },
    ]
});

// router.beforeEach((to, from, next) => {
//   if (to.matched.some(record => record.meta.isAuthenticated)) {
//     if (!Store.state.auth.isAuth) {
//       next({ name: 'Login' });
//     } else {
//       next();
//     }
//   }
//   next();
// });


// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
