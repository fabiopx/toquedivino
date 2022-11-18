<template>
  <div>
    <v-dialog
      v-model="loginNow.login"
      fullscreen
      persistent
      transition="dialog-transition"
    >
      <v-card width="400" height="400" color="blue darken-4">
        <v-toolbar elevation="0" color="blue darken-4">
          <v-toolbar-title>
            <v-img
              :src="require('../assets/logotipo_branco.png')"
              width="200"
            ></v-img>
          </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form class="mt-3" ref="formLogin">
            <v-text-field
              v-model="loginData.email"
              label="Login"
              dark
              :rules="loginEmailRules"
            >
            </v-text-field>
            <v-text-field
              v-model="loginData.password"
              label="Senha"
              dark
              :rules="loginPasswordRules"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showPassword ? 'text' : 'password'"
              @click:append="showPassword = !showPassword"
            >
            </v-text-field>
          </v-form>
          <v-alert
            id="alert"
            border="left"
            v-show="alertLogin.status"
            type="error"
            >{{ alertLogin.msg }}</v-alert
          >
        </v-card-text>
        <v-card-actions>
          <v-btn
            depressed
            x-large
            color="orange white--text"
            @click="enterLogin()"
            >Entrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  name: "DashboardLogin",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      showPassword: false,
      alertLogin: { status: false, msg: "" },
      inputLoading: false,
      loginEmailRules: [(v) => !!v || "Digite o e-mail para realizar o login"],
      loginPasswordRules: [
        (v) => !!v || "Digite a senha para realizar o login",
      ],
      loginData: { email: "", password: "" },
    };
  },

  mounted() {},

  computed: {
    ...mapGetters(["loginNow"])
  },

  methods: {
    ...mapActions(["setLoginNow"]),

    enterLogin: async function () {
      let data = new FormData();
      data.append("email", this.loginData.email);
      data.append("password", this.loginData.password);
      const response = await axios(this.apiURL + "/users/loginDashboard", {
        method: "POST",
        data: data,
      });

      if (response.data.loginNow.logged) {
        this.$session.start();
        this.$session.set("userData", response.data.loginNow);
        this.setLoginNow(this.$session.get('userData'));
        this.$router.push("/dashboard/home");
      } else {
        this.alertLogin = response.data.alert;
        setInterval(() => {
          this.alertLogin = false;
        }, 5000);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>