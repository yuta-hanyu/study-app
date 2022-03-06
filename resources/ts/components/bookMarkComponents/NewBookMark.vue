<template>
  <div>
    <v-sheet width="400px">
      <v-form>
        <v-container>
          <v-row justify="center">
            <p class="text-h6 py-3">ブックマーク登録</p>
            <v-col
              cols="10">
              <v-text-field
                label="リンク">
              </v-text-field>
            </v-col>
            <v-col
              cols="10">
              <v-text-field
                label="タイトル">
              </v-text-field>
            </v-col>
            <v-col cols="10">
              <v-combobox
                v-model="select"
                :items="comboboxItems"
                label="フォルダ"
                dense>
              </v-combobox>
            </v-col>
            <v-col
              cols="10">
              <v-text-field
                label="メモ">
              </v-text-field>
            </v-col>
            <v-container>
              <v-row justify="center">
                <v-col
                 class="text-center"
                  cols="4">
                  <v-btn>
                    戻る
                  </v-btn>
                </v-col>
                <v-col
                  cols="4"
                  class="text-center">
                  <v-btn>
                    登録
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-row>
        </v-container>
      </v-form>
    </v-sheet>
  </div>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator';
import Const from '../../common/const';

@Component({
  name: 'NewBookMark',
  components: {
  },
})

export default class NewBookMark extends Mixins(Const) {
  private select: string = 'Vuetify';
  private comboboxItems: string[] = [
      'Programming',
          'Design',
          'Vue',
          'Vuetify',
  ];

}
</script>

<style>

</style>
