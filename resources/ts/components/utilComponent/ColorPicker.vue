<template>
  <div>
    <v-color-picker
      dark
      mode="hexa"
      hide-canvas
      hide-sliders
      v-model="color"
      class="ma-2"
      show-swatches>
    </v-color-picker>
    <v-container>
    <v-row justify="center"
      :style="{backgroundColor: '#666666'}">
      <v-btn
        class="go mx-2 my-2"
        @click="choiceColor">
        OK
      </v-btn>
      </v-row>
  </v-container>
    </div>
</template>

<script lang="ts">
import {Component, Emit} from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: 'ColorPicker',
})

export default class ColorPicker extends Vue {
  // OK押下
  @Emit('choice-color')
    emitChoiceColor(date: string): void {
  };
  // カラー
  private color: string = '';
  /**
   * OK押下
   */
  private choiceColor(): void {
    this.emitChoiceColor(this.color);
    this.color = '';
  }
}
</script>

<style>

</style>
