<template>
  <v-time-picker
    v-model="time"
    format="ampm"
    landscape
    scrollable
    full-width
    color="green lighten-1">
    <v-btn
      text
      color="primary"
      @click="clear">
      クリア
    </v-btn>
    <v-btn
      text
      color="primary"
      @click="choiceTime">
      OK
    </v-btn>
  </v-time-picker>
</template>

<script lang="ts">
import {Component, Emit} from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: 'TimePicker',
})

export default class TimePicker extends Vue {
  // OK押下
  @Emit('choice-time')
    emitChoiceTime(time: string): void {
  };
  // 年月日
  private time: string = '';
  /**
   * クリア押下
   */
  private clear(): void {
    this.time = '';
  }
  /**
   * OK押下
   */
  private choiceTime(): void {
    this.emitChoiceTime(this.time);
    this.time = '';
  }
}
</script>

<style>

</style>
