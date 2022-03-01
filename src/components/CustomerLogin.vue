<template>
  <div>
    <v-dialog
      v-model="userNow.login"
      fullscreen
      persistent
      transition="dialog-transition"
    >
      <v-card width="400" height="400" color="blue darken-4">
        <v-toolbar elevation="0" color="blue darken-4">
          <v-toolbar-title>
            <v-img :src="require('../assets/logotipo_branco.png')" width="200"></v-img>
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
  data: () => ({
    showPassword: false,
    alertLogin: { status: false, msg: "" },
    inputLoading: false,
    loginEmailRules: [(v) => !!v || "Digite o e-mail para realizar o login"],
    loginPasswordRules: [(v) => !!v || "Digite a senha para realizar o login"],
    loginData: { email: "", password: "" },
  }),

  computed: {
    ...mapGetters(["userNow"]),
  },

  methods: {
    ...mapActions(["setUserNow", "setInscribeID"]),

    enterLogin: async function () {
      let data = new FormData();
      data.append("email", this.loginData.email);
      data.append("password", this.loginData.password);
      const response = await axios(process.env.VUE_APP_URL + "loginCustomer", {
        method: "POST",
        data: data,
      });

      if (response.data.userNow.logged) {
        this.$session.start();
        this.$session.set("userData", response.data.userNow);
        this.setUserNow(this.$session.get('userData'));
        this.getInscribeID()
        this.$router.push("/customer");
      } else {
        this.alertLogin = response.data.alert;
        setInterval(() => {
          this.alertLogin = false;
        }, 5000);
      }
    },
    getInscribeID: function () {
			axios
				.get(process.env.VUE_APP_URL + "getInscribeCustomers/" + this.userNow.id)
				.then((response) => {
					const resp = response.data;
          this.setInscribeID(resp.idinscribe)
				});
		},
  },

  created() {
    //
  },
};
</script>