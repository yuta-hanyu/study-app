<template>
  <v-date-picker
    locale="ja"
    v-model="date"
    color="green lighten-1"
    scrollable>
    <v-spacer></v-spacer>
    <v-btn
      text
      color="primary"
      @click="clear">
      クリア
    </v-btn>
    <v-btn
      text
      color="primary"
      @click="choiceDate">
      OK
    </v-btn>
  </v-date-picker>
</template>

<script lang="ts">
import {Component, Emit} from 'vue-property-decorator';
import Vue from 'vue';

@Component({
  name: 'DatePicker',
})

export default class DatePicker extends Vue {
  // OK押下
  @Emit('choice-date')
    emitChoiceDate(date: string): void {
  };
  // 年月日
  private date: string = '';
  /**
   * クリア押下
   */
  private clear(): void {
    this.date = '';
  }
  /**
   * OK押下
   */
  private choiceDate(): void {
    this.emitChoiceDate(this.date);
    this.date = '';
  }
}
</script>

<style>

</style>
