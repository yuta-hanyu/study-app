<template>
  <div>
    <v-app-bar
      app
      dark>
    <v-app-bar-nav-icon @click="drawer=true"></v-app-bar-nav-icon>
    <p class="my-auto">StudyApp</p>
    <v-spacer></v-spacer>
    <v-toolbar-title>{{$route.meta.title}}</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-chip
      class="mx-2"
      color="success"
      dark>
        <v-icon
          dark
          left>
          mdi-human-greeting-variant
        </v-icon>{{$store.state.userInfo.loginUserName}}
    </v-chip>
    </v-app-bar>
      <v-navigation-drawer
        v-model="drawer"
        fixed
        width="200px"
        dark
        temporary>
        <v-list
          nav
          dense>
          <v-list-item-group>
            <v-list-item
              v-for="(menu, index) in this.MENU_ITEMS"
              :key=index>
              <router-link
                :to="menu.url"
                class="font-weight-black list">
                {{menu.name}}
              </router-link>
            </v-list-item>
            <v-list-item
              @click="LogoutDialog">
              <router-link
                to="#"
                class="font-weight-black list">
                ログアウト
              </router-link>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Emit} from 'vue-property-decorator';
import Const from '../common/const';

@Component({
  name: "Header",
})

export default class Header extends Mixins(Const){
  /**
   * ログアウト
   */
  @Emit('logout-dialog')
  LogoutDialog(): void{};
  // サイドメニュー閉じる開く
  private drawer: Boolean = false;
}
</script>

<style scoped>
  #mdi-lightbulb-on {
    transform: scale(1.4, 1.4);
  }
  .list {
    text-decoration: none;
    color: #FFFFFF;
  }
</style>
