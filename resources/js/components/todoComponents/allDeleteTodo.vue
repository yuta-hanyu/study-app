<template>
<!-- <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Privacy Policy
        </v-card-title>

        <v-card-text>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="dialog = false"
          >
            I accept
          </v-btn>
        </v-card-actions>
      </v-card> -->


<v-card class="text-center">
    <v-sheet
      class="px-7 pt-7 pb-4 mx-auto text-center d-inline-block"
      color="black"
      dark
      height="300px"
      width="400px"

    >
      <div class="grey--text text--lighten-1 text-body-2 mb-4">
        全てのtodoを削除しますか？<br>
        ※ 元には戻りません
      </div>

      <v-btn
        :disabled="loading"
        class="ma-1"
        color="grey"
        plain
      >
        Cancel
      </v-btn>

      <v-btn
        :loading="loading"
        class="ma-1"
        color="error"
        plain
        @click="remove"
      >
        Delete
      </v-btn>
    </v-sheet>
</v-card>


</template>

<script>
export default {
  methods: {
    backTodos(){
      this.$emit('back-todos');
    }
  }
}
</script>

<style>

</style>
