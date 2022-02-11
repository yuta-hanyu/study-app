<template>
  <div>
    <v-container>
      <v-row justify="center" align-content="center" style="height: 600px;">
        <v-col cols="8" align="center">
        <v-sheet
        color="amber lighten-5"
        elevation="1">
        <div class="text-h4 py-5">ログイン</div>
          <v-form>
              <v-col cols="10" align="center">
                <v-text-field
                  class="px-10"
                  v-model="email"
                  label="メールアドレス"
                  required
                  :counter="255"
                ></v-text-field>
              </v-col>
              <v-col cols="10" align="center">
                <v-text-field
                  class="px-10"
                  v-model="password"
                  :counter="8"
                  label="パスワード"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="10" align="center">
                <v-btn
                  class="mr-4"
                  @click="login"
                  color="orange lighten-2"
                  :disabled="invalid">
                  ログイン
                </v-btn>
              </v-col>
          </v-form>
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
  },
  methods:{
    login(){
      axios.get('/api/login',{
        email: this.email,
        password: this.password
      }).then((res) => {
        console.log(res);
        console.log("成功");
      }).catch((e) => {
        console.log(e);
        window.alert("データの送信に失敗しました")
      });
    },
  }

}
</script>

<style>

</style>
