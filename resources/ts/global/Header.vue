<template>
  <div>
    <v-app-bar
      :color="color"
      dark>
    <v-app-bar-nav-icon @click="drawer=true"></v-app-bar-nav-icon>
    <v-toolbar-title>MyStudyApp</v-toolbar-title>
    <!-- ダークモード変換ボタン -->
    <v-tooltip bottom color="yellow lighten-3">
      <template v-slot:activator="{ on, attrs }">
        <v-icon
          color="yellow lighten-3"
          v-bind="attrs"
          v-on="on"
          id="mdi-lightbulb-on"
          @click="changeIsDark">
        mdi-lightbulb-on
        </v-icon>
      </template>
      <span style="color: black;">{{isDarkMode}}モードにする</span>
    </v-tooltip>
    </v-app-bar>
      <v-navigation-drawer
        v-model="drawer"
        fixed
        temporary>
        <v-list
          nav
          dense>
          <v-list-item-group >
            <v-list-item
              v-for="(menu, index) in this.MENU_ITEMS"
              :key=index>
              <router-link :to="menu.url">{{menu.name}}</router-link>
            </v-list-item>
            <v-list-item
              @click="LogoutDialog">
              <router-link to="#">ログアウト</router-link>
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
  @Prop({type: Boolean, default: false})
  isDark!: Boolean;
  /**
   * ダークモード切替処理
   */
  @Emit('change-isdark')
  changeIsDark(): void{
  };
  /**
   * ログアウト
   */
  @Emit('logout-dialog')
  LogoutDialog(): void{};
  // サイドメニュー閉じる開く
  private drawer: Boolean = false;
  // ツールチップtext変換
  get isDarkMode(): String {
    return this.isDark === true ? "ライト" : "ダーク";
  }
  // ヘッダー配色変換
  get color(): String {
    return this.isDark === true ? "gray" : "orange";
  }
}
</script>

<style scoped>
  #mdi-lightbulb-on {
    position: fixed;
    right: 1%;
    top: 15px;
    transform: scale(1.5, 1.5);
  }
</style>
