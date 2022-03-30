<template>
  <v-dialog
    fullscreen
    v-model="loadingDialog"
    persistent>
    <v-container fluid fill-height>
      <v-progress-circular
        :size="70"
        :width="10"
        color="light-blue lighten-1"
        indeterminate
        class="mx-auto align-self-center s">
      </v-progress-circular>
    </v-container>
  </v-dialog>
</template>

<script lang="ts">
import {Component, Prop} from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: 'Loading',
})

export default class Loading extends Vue {
  @Prop({type: Boolean, default: false})
    loadingDialog!: boolean;
}
</script>

<style scoped>
</style>
