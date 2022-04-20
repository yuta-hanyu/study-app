<template>
  <div>
    <v-sheet dark width="400px" class="kokuban my-0">
      <v-row justify="center" class="px-2">
        <v-col md=10 class="text-center">
          <h5 class="dialog-title"><slot name="title"></slot></h5>
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
  </div>
</template>

<script lang="ts">
import {Component, Emit, Mixins} from 'vue-property-decorator';
import Const from '../../common/const';

@Component({
  name: 'Complete',
})

export default class Complete extends Mixins(Const) {
  // 「戻る]」押下
  @Emit('back')
    back(): void {};
}
</script>

<style>

</style>
