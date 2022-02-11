<template>
  <div>
    <v-container>
      <v-row justify="center" align-content="center" style="height: 600px;">
        <v-col cols="7" align="center">
        <v-sheet
        color="amber lighten-5"
        elevation="1">
        <div
        class="text-h4 py-5">
        ログイン
        </div>
          <v-row justify="center">
            <v-form>
                <v-col cols="12" align="center">
                  <v-text-field
                    class="px-10"
                    v-model="email"
                    label="メールアドレス"
                    required
                    :counter="255"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" align="center">
                  <v-text-field
                    class="px-10"
                    v-model="password"
                    :counter="8"
                    label="パスワード"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" align="center">
                  <v-btn
                    class="mr-4"
                    type="submit"
                    color="orange lighten-2"
                    :disabled="invalid">
                    ログイン
                  </v-btn>
                </v-col>
            </v-form>
          </v-row>
        </v-sheet>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data(){
    return{
      email:"",
      password:"",
      invalid:false
    }
  }

}
</script>

<style>

</style>
