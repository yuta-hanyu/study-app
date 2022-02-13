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
          <v-list-item-group>
            <v-list-item
            v-for="(menu, index) in munes"
            :key=index>
            <router-link :to="menu.url">{{menu.name}}</router-link>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>
  </div>
</template>

<script>
import consts from '../common/const.js'

export default {
  name: 'Header',
  props: {
    isDark: Boolean
  },
  data () {
    return {
      drawer: false,
      munes: consts.menuItems,
    }
  },
  methods: {
    changeIsDark() {
      this.$emit('change-Isdark');
    }
  },
  computed: {
    // ツールチップtext変換
    isDarkMode() {
      return this.isDark === true ? "ライト" : "ダーク";
    },
    // ヘッダー配色変換
    color() {
      return this.isDark === true ? "gray" : "orange";
    }
  }
}
</script>

<style scoped>
  #mdi-lightbulb-on{
    position: fixed;
    right: 1%;
    top: 15px;
    transform: scale(1.5, 1.5);
  }
</style>
