<template>
  <div>
    <v-footer padless app color="teal darken-4" dark>
    <v-col
      class="text-center"
      cols="12">
      {{ new Date().getFullYear() }}__<strong>&copy; StudyApp</strong>
    </v-col>
  </v-footer>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Const from '../common/const';

@Component({
  name: "Footer",
})

export default class Footer extends Mixins(Const){

}
</script>

<style scoped>

</style>
