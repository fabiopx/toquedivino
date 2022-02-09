<template>
  <div>
    <v-dialog
      v-model="userNow.login"
      fullscreen
      persistent
      transition="dialog-transition"
    >
      
        <v-card width="400" height="300" color="blue darken-4">
          <v-toolbar elevation="0" color="blue darken-4">
            <v-toolbar-title>
              <v-img src="assets/img/logotipo_branco.png" width="150"></v-img>
            </v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form ref="formLogin">
              <v-text-field
                v-model="loginEmail"
                label="Login"
                dark
                :rules="loginEmailRules"
              >
              </v-text-field>
              <v-text-field
                v-model="loginPassword"
                label="Senha"
                dark
                :rules="loginPasswordRules"
                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showPassword ? 'text' : 'password'"
                @click:append="showPassword = !showPassword"
              >
              </v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn
              depressed
              large
              block
              color="orange white--text"
              @click="enterLogin()"
              >Entrar
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-alert
          id="alert"
          border="left"
          v-show="alertLogin.status"
          type="error"
          >{{ alertLogin.msg }}</v-alert
        >
    </v-dialog>
  </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'
export default {
    data: () => ({
        showPassword: false,
        alertLogin: { status: false, msg: "" },
        inputLoading: false,
		loginEmail: "",
		loginEmailRules: [(v) => !!v || "Digite o e-mail para realizar o login"],
		loginPassword: "",
		loginPasswordRules: [(v) => !!v || "Digite a senha para realizar o login"],
    }),

    computed: {
        ...mapGetters(['userNow'])
    },

    methods: {
        ...mapActions(['loginCustomer'])
    }
};
</script>