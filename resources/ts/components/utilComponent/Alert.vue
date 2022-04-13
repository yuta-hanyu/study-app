<template>
  <v-sheet
    class="px-7 pt-7 pb-4 mx-auto text-center d-inline-block"
    color="black"
    dark
    height="350px"
    width="600px">
    <v-row justify="center" class="px-2">
    <v-col md=10 class="text-center">
      <h3 class="dialog-title"><slot name="title"></slot></h3>
    </v-col>
    <v-col md=12>
      <p><slot name="content"></slot></p>
    </v-col>
    <v-col
      class="text-center"
      md="12">
      <v-btn
        class="go"
        @click="back()">
        OK
      </v-btn>
      </v-col>
    </v-row>
  </v-sheet>
</template>

<script lang="ts">
import {Component, Emit, Mixins} from 'vue-property-decorator';
import Const from '../../common/const';

@Component({
  name: 'Alert',
})

export default class Alert extends Mixins(Const) {
  // 「戻る]」押下
  @Emit('back')
    back(): void {};
}
</script>

<style>

</style>
