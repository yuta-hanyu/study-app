<template>
  <div>
    <v-alert
      v-for="(alertMsg, index) in this.getAlertMsgs" :key=index
      dense
      border="left"
      :type="alertType"
      class="px-6 mx-auto"
      width="70%">
      {{alertMsg}}
    </v-alert>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Mixins, Watch} from 'vue-property-decorator';
import Const from '../../common/const';

@Component({
  name: 'AlertMsg',
})

export default class AlertMsg extends Mixins(Const) {
  @Prop({type: String, default: false})
    alertType!: 'error'|'success'|'';
  @Prop({type: Array, default: false})
    alertMsgs!: string[];

  private getAlertMsgs: string[] = this.alertMsgs;

  @Watch('alertMsgs', {immediate: true})
    private onChangeRoute(routeInstance: object, oldRouteInstance: object) {
      this.getAlertMsgs = this.alertMsgs;
      if(this.alertType === 'success') {
        setTimeout(() => {
          this.getAlertMsgs = [];
        }, 10000);
      }
  }
}

</script>

<style>

</style>
