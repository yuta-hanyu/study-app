<template>
  <div>
    <v-app-bar
      color="orange"
      dark
    >
    <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
    <v-toolbar-title>MyPortfolioSite</v-toolbar-title>
    </v-app-bar>
      <v-navigation-drawer
        v-model="drawer"
        fixed
        temporary
      >
        <v-list
          nav
          dense
        >
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
