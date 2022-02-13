<template>
  <div>
    <v-card
     color="amber lighten-5">
      <v-card-title class="justify-center title text-h6">ログアウト</v-card-title>
      <v-card-actions>
        <v-row justify="center" class="mb-auto">
          <v-col cols="6" align="right">
            <v-btn
              color="grey lighten-1"
              width="100px"
              rounded
              @click="back"
              elevation="20">
              戻る
            </v-btn>
          </v-col>
          <v-col cols="6">
            <v-btn
              width="100px"
              color="orange lighten-2"
              elevation="20"
              rounded>
              ログアウト
            </v-btn>
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  name: 'Logout',
  data() {
    return {}
  },
  methods: {
    // 戻るボタン押下
    back() {
      this.$emit('back-logout');
    }
  }
}
</script>

<style>

</style>
