<template>
  <div>
    <v-container v-show="isAgreement" fluid>
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
            <v-toolbar color="grey darken-4" dark>
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
                    <v-avatar class="mr-2">
                      <v-img :src="accountBlob ? accountBlob : accountPhoto"></v-img>
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
    <v-container v-show="!isAgreement">
      <p class="white--text">Esta função estará liberarda após a assinatura do contrato.</p>
      <p>
        <v-btn depressed color="grey darken-4" dark class="mr-2">Faça seu orçamento</v-btn>
        <v-btn depressed color="grey darken-4" dark>Revise seu cadastro</v-btn>
      </p>
    </v-container>
    <v-overlay v-show="loading">
      <v-progress-circular indeterminate></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
var imgPath = "assets/";
export default {
  data: () => ({
    userData: {},
    accountName: "",
    accountEmail: "",
    accountPassword: "",
    accountPhone: "",
    accountPhoto: process.env.VUE_APP_IMGPATH + "profile.svg",
    accountBlob: null,
    currentFile: undefined,
    progressUpload: 0,
    progressShow: false,
    uploadSuccess: false,
    uploadError: false,
    uploadMsg: "",
    photo: "",
    loadingAccountFields: false,
    loading: false
  }),

  methods: {
    ...mapActions(['setUserNow']),
    getAccount: function () {
      this.loadingAccountFields = true;
      axios
        .get(process.env.VUE_APP_URL + "getUsers/" + this.userNow.id)
        .then((response) => {
          this.accountName = response.data.name;
          this.accountEmail = response.data.email;
          this.accountPassword = response.data.password;
          this.accountPhoto = response.data.photo
            ? response.data.photo
            : process.env.VUE_APP_IMGPATH + "profile.svg";
          this.loadingAccountFields = false;
          this.userData = this.$session.get('userData')
          this.userData.photo = this.accountPhoto
          this.userData.name = this.accountName
          this.$session.remove('userData')
          this.$session.set('userData', this.userData)
          this.setUserNow(this.$session.get('userData'))
        });
    },

    readAccountPhoto: function (file) {
      if (file) {
        this.accountBlob = URL.createObjectURL(file);
        this.progressShow = true;

        let data = new FormData();
        data.append("file", file);

        axios
          .post(process.env.VUE_APP_URL + "uploadPhoto", data, {
            onUploadProgress: (event) => {
              const totalLength = event.lengthComputable
                ? event.total
                : event.target.getResponseHeader("content-length") ||
                  event.target.getResponseHeader(
                    "x-decompressed-content-length"
                  );
              console.log("onUploadProgress", totalLength);
              if (totalLength !== null) {
                this.progressUpload = Math.round(
                  (event.loaded * 100) / totalLength
                );
              }
            },
          })
          .then((response) => {
            response.data.type == "success"
              ? (this.uploadSuccess = true)
              : (this.uploadError = true);
            this.uploadMsg = response.data.msg;
            this.progressShow = false;
          })
          .catch((error) => {
            this.uploadError = true;
            this.uploadMsg = error;
          });
      } else {
        this.accountPhoto = process.env.VUE_APP_IMGPATH + "profile.svg";
        this.progressShow = false;
        this.uploadMsg = "";
        this.uploadSuccess = false;
        this.uploadError = false;
      }
    },

    saveAccount: function () {
      let data = new FormData();
      data.append("name", this.accountName);
      data.append("email", this.accountEmail);
      data.append("password", this.accountPassword);
      data.append(
        "photo",
        this.currentFile
          ? process.env.VUE_APP_UPLOAD + this.currentFile.name
          : this.accountPhoto
      );
      this.loading = true
      axios(
        process.env.VUE_APP_URL +
          "updateUserCustomers/" +
          this.userNow.id,
        {
          method: "POST",
          data: data,
        }
      ).then((response) => {
        this.loading = false
        this.getAccount();
      });
    },
  },

  created() {
    if(this.$session.exists()){
      this.setUserNow(this.$session.get('userData'))
    }
    this.getAccount();
  },

  computed: {
    ...mapGetters(["userNow", "isAgreement"]),
  },
};
</script>