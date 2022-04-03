<template>
  <div>
    <v-app>
      <header>
        <Header
          v-show="headerFlag"
          @inquiry-dialog="inquiryDialog = !inquiryDialog"
          @logout-dialog="LogoutDialog = !LogoutDialog">
        </Header>
      </header>
      <main class="body">
      <RouterView />
      <!-- ローディングダイアログ -->
      <loading :loadingDialog="IsLoadingShow" />
      <!-- お問い合わせダイアログ -->
      <v-dialog
        persistent
        v-model="inquiryDialog"
        height="600px"
        width="500px">
        <inquiry
          @back-inquiry="inquiryDialog = !inquiryDialog">
        </inquiry>
      </v-dialog>
      <!-- ログアウトダイアログ -->
      <v-dialog
        persistent
        v-model="LogoutDialog"
        height="200px"
        width="300px">
        <logout
          @back-logout="LogoutDialog = !LogoutDialog">
        </logout>
      </v-dialog>
      </main>
    </v-app>
  </div>
</template>

<script lang="ts">
import { Component, Ref, Watch} from 'vue-property-decorator';
import Vue from 'vue';
import Header from './global/Header.vue';
import Todo from './components/Todo.vue';
import Top from './components/Top.vue';
import Login from './components/Login.vue';
import Logout from './components/Logout.vue';
import BookMark from './components/BookMark.vue';
import Loading from './global/Loading.vue';
import Inquiry from './components/Inquiry.vue';

@Component({
  components: {
    Header,
    Todo,
    Top,
    Login,
    Logout,
    BookMark,
    Loading,
    Inquiry,
  },
})

export default class App extends Vue {
  // ログアウトダイアログ
  private LogoutDialog: boolean = false;
  // お問い合わせダイアログ
  private inquiryDialog: boolean = false;
  // ヘッダー表示判定
  get headerFlag(): boolean {
    return this.$store.state.userInfo.isAuth ? true : false;
  };
  // ローディング表示判定
  get IsLoadingShow() {
    return this.$store.state.userInfo.isLoading;
  }

  mounted() {
    let routeInstance = this.$route;
    this.createTitle(routeInstance);
  }
  /**
  * ページタイトル取得
  */
  private createTitle(routeInstance: any): void{
    if(routeInstance.meta.title){
      const setTitle = routeInstance.meta.title + ' | StudyApp';
      document.title = setTitle;
    } else {
      document.title = 'StudyApp'
    }
  }
  /**
  * ルータを監視し、動的にページタイトル変更
  */
  @Watch('$route', {immediate: true})
    private onChangeRoute(routeInstance: object, oldRouteInstance: object) {
    this.createTitle(routeInstance);
  }
}
</script>

<style lang="scss">
.theme--light.v-application {
  background-color: #666666;
  color: #fff;
}
.v-application{
  font-family: "M Plus 1c" !important;
  font-weight: bolder;
}

</style>
