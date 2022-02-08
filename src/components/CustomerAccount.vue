<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <p class="text-h4 white--text">
            <v-icon color="white">mdi-account</v-icon> Dados de acesso
          </p>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="blue" dark>
              <v-toolbar-title>Gerenciar dados</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn text @click="saveAccount()">
                  <v-icon>mdi-content-save</v-icon> Salvar
                </v-btn>
              </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="loadingAccountFields"
                type="paragraph"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingAccountFields" ref="formAccount">
                <v-row>
                  <v-col>
                    <v-text-field
                      v-model="accountName"
                      label="Nome"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-text-field
                      v-model="accountEmail"
                      label="E-mail"
                    ></v-text-field>
                  </v-col>
                  <v-col>
                    <v-text-field
                      v-model="accountPassword"
                      label="Senha"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-file-input
                      v-model="currentFile"
                      label="Foto"
                      chips
                      @change="readAccountPhoto"
                    ></v-file-input>
                    <v-progress-linear
                      v-show="progressShow"
                      v-model="progressUpload"
                      class="grey--text"
                      height="20"
                    >
                      {{ progressUpload }} %
                    </v-progress-linear>
                    <v-alert
                      v-show="uploadSuccess"
                      type="success"
                      transition="fade-transition"
                    >
                      {{ uploadMsg }}
                    </v-alert>
                    <v-alert
                      v-show="uploadError"
                      type="error"
                      transition="fade-transition"
                    >
                      {{ uploadMsg }}
                    </v-alert>
                  </v-col>
                  <v-col>
                    <v-avatar>
                      <v-img :src="accountPhoto"></v-img>
                    </v-avatar>
                    <span>{{ accountName }}</span>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    urlApi: process.env.VUE_APP_URL,
    accountName: "",
    accountEmail: "",
    accountPassword: "",
    accountPhone: "",
    accountPhoto: "assets/img/profile.svg",
    currentFile: undefined,
    progressUpload: 0,
    progressShow: false,
    uploadSuccess: false,
    uploadError: false,
    uploadMsg: "",
    photo: "",
  }),

  methods: {
    getAccount: function () {
      this.loadingAccountFields = true;
      axios
        .get(this.urlApi + "getUsers/" + this.userNow.id)
        .then((response) => {
          this.accountName = response.data.name;
          this.accountEmail = response.data.email;
          this.accountPassword = response.data.password;
          this.accountPhoto = response.data.photo
            ? response.data.photo
            : "assets/img/profile.svg";
          this.loadingAccountFields = false;
        });
    },

    loadEnv: function(){
        console.log(process.env.VUE_APP_URL)
    }
  },
};
</script>