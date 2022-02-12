<template>
  <div>
    <v-container>
      <v-row justify="center" align-content="center" style="height: 600px;">
        <v-col cols="8" align="center">
        <v-sheet
        color="amber lighten-5"
        elevation="1">
        <div class="text-h4 py-5">ログイン</div>
          <v-row
            class="mt-3"
            v-if="this.validatMessage.length"
            justify="center">
            <v-alert
              v-for="(message, index) in this.validatMessage" :key=index
              dense
              text
              border="left"
              type="error"
              class="px-6"
              width="70%">
              {{message}}
            </v-alert>
          </v-row>
          <v-row
            class="mt-3"
            v-if="this.message"
            justify="center">
            <v-alert
              dense
              text
              border="left"
              type="error"
              class="px-6"
              width="70%">
              {{message}}
            </v-alert>
          </v-row>
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
import storeAuth from '../store/auth.js';
export default {
  data() {
    return{
      // 認証メールアドレス
      email: '',
      // 認証パスワード
      password: '',
      // ログインボタン無効化フラグ
      invalid: false,
      // バリデーションMSG
      validatMessage: [],
      // 結果MSG
      message: ''
    }
  },
  methods: {
    /**
     * エラーメッセージをオブジェクトから配列へ変換
     */
    changeErrors(message) {
      let errors = [];
      for (let [key, value] of Object.entries(message)) {
        errors.push(value[0]);
      }
      return errors;
    },
    /**
     * ログインボタン押下
     */
    login() {
      // エラーメッセージ初期化;
      this.validatMessage = [];
      this.message = '';
      // クッキー認証
      axios.get('/api/csrf-cookie', {withCredentials:true}).then((res) => {
        // ログイン認証開始
        axios.post('/api/login', {
          email: this.email,
          password: this.password,
        },{withCredentials:true}).then((res) => {
          if( res.data.retultFlag == true ) {
            this.$router.push("/");
          };
          this.validatMessage = this.changeErrors(res.data.validatMessage);
        }).catch((e) => {
          console.log(e);
          this.message = 'ログインに失敗しました'
        });
      }).catch((e) => {
        window.alert("認証エラーです");
        console.log(e);
      });
    }
  }
}
    // ...mapActions({
    //   login: 'auth/login',
    // }),
    // async submit() {
    //   await this.login(this.form);
    //   this.$router.replace({ name: 'Top' });
    // },
    // login(){
    //   axios.get('/api/loginPost',{
    //     email: this.email,
    //     password: this.password
    //   }).then((res) => {
    //     this.message = res.data.message;
    //     console.log(res);
    //     console.log("成功");
    //     // this.$router.replace({ name: 'Top' });
    //   }).catch((e) => {
    //     console.log(e);
    //     window.alert("データの送信に失敗しました")
    //   });
    // },

</script>

<style>

</style>
