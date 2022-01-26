<template>
  <v-container>
    <v-row>
      <v-col cols="10">
        <v-data-table
          :headers="headers"
          :items="items"
        ></v-data-table>
      </v-col>
      </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'Todo',
}
</script>

<style>

</style>
