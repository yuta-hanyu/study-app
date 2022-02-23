<template>
<div>
<h1>TOP</h1>
</div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../common/const';

@Component ({
  name: 'TOP'
})

export default class Top extends Mixins(Const){

}
</script>
<style>

</style>
