<template>
  <div>
    <v-app-bar
      color="orange"
      dark
    >
    <v-app-bar-nav-icon @click="drawer=true"></v-app-bar-nav-icon>
    <v-toolbar-title>MyStudyApp</v-toolbar-title>

    <!-- ダークモード変換ボタン -->
    <v-tooltip bottom color="primary">
      <template v-slot:activator="{ on, attrs }">
        <v-icon
          color="primary"
          v-bind="attrs"
          v-on="on"
          id="plus-circle"
          @click="newTodoOpen"
        >
        mdi-plus-circle
        </v-icon>
      </template>
      <span>todo追加</span>
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
  data () {
    return {
      drawer: false,
      munes: consts.menuItems
    }
  }
}
</script>

<style>

</style>
