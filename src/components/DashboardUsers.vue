<template>
  <div>
    <v-container class="usuarios">
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="blue-grey">
              <h3>Usuários</h3>
              <v-spacer></v-spacer>
              <!-- add user -->
              <v-dialog
                v-model="dialogUsers"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-toolbar-items>
                    <v-btn
                      dark
                      text
                      v-bind="attrs"
                      v-on="on"
                      @click="setCrud('c')"
                    >
                      <v-icon class="mr-2">mdi-account-plus</v-icon> novo
                      usuário
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="blue-grey">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogUsers'), clearFormUsers()"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>novo usuário</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveUser()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-form ref="formAccount" id="formAccount">
                    <v-container>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="accountName"
                            :rules="accountNameRules"
                            label="Nome do usuário"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col>
                          <v-text-field
                            v-model="accountEmail"
                            :rules="accountEmailRules"
                            label="E-mail do usuário"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="accountPhone"
                            v-mask="maskTel(accountPhone)"
                            :rules="accountPhoneRules"
                            label="Telefone do Usuário"
                            required
                          >
                          </v-text-field>
                        </v-col>
                        <v-col>
                          <v-select
                            v-model="accountAccessType"
                            :rule="accountAccessTypeRules"
                            :items="accessType"
                            item-text="name"
                            item-value="value"
                            label="Tipo de Acesso"
                            required
                          >
                          </v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="accountPassword"
                            label="Senha"
                            :rules="accountPasswordRules"
                            :append-icon="
                              showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            @click:append="showPassword = !showPassword"
                          >
                          </v-text-field>
                        </v-col>
                        <v-col>
                          <v-switch
                            label="Status"
                            v-model="accountStatus"
                          ></v-switch>
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
                            {{ progressUpload }} %</v-progress-linear
                          >
                          <v-alert
                            v-show="uploadSuccess"
                            type="success"
                            transition="fade-transition"
                            >{{ uploadMsg }}</v-alert
                          >
                          <v-alert
                            v-show="uploadError"
                            type="error"
                            transition="fade-transition"
                            >{{ uploadMsg }}</v-alert
                          >
                        </v-col>
                        <v-col>
                          <v-avatar>
                            <v-img :src="accountPhoto"></v-img>
                          </v-avatar>
                          <span class="mr-3">{{ accountName }}</span>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <v-card-text>
              <!-- users table -->
              <v-text-field
                v-model="searchUsers"
                label="Busca"
                append-icon="mdi-magnify"
              >
              </v-text-field>
              <v-skeleton-loader
                v-if="firstLoad"
                :contentLoading="true"
                type="table"
              >
              </v-skeleton-loader>
              <v-data-table
                v-show="!firstLoad"
                :headers="headerUsers"
                :search="searchUsers"
                :items="users"
                :items-per-page="5"
              >
                <template v-slot:item.access="{ item }">
                  <span v-show="item.access == 0">Admin</span>
                  <span v-show="item.access == 1">Usuário</span>
                  <span v-show="item.access == 2">Cliente</span>
                  <span v-show="item.access == 3">Músico</span>
                </template>
                <template v-slot:item.status="{ item }">
                  <v-chip v-show="item.status == 1" color="primary">
                    Ativo</v-chip
                  >
                  <v-chip v-show="item.status == 0"> Inativo</v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="blue" icon @click="editUsers(item)">
                    <v-icon>mdi-account-edit</v-icon>
                  </v-btn>
                  <v-btn
                    v-show="item.access != 0"
                    color="red"
                    icon
                    @click="deleteUser(item.idaccount)"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardUsers",

  data() {
    return {
      dialogUsers: false,
      showPassword: false,
      firstLoad: false,
      currentFile: undefined,
      progressUpload: 0,
      progressShow: false,
      uploadSuccess: false,
      uploadError: false,
      uploadMsg: "",
      maskPhone: "(##) ####-####",
      maskMobile: "(##) #####-####",
      maskCep: "#####-###",
      maskCpf: "###.###.###-##",
      maskCnpj: "##.###.###/####-##",
      maskMoney: "######.##",
      maskBirthdate: "##/##/####",
      accountId: "",
      accountName: "",
      accountNameRules: [(v) => !!v || "O campo NOME DO USUÁRIO é requerido"],
      accountPhoto: process.env.VUE_APP_IMGPATH + "profile.svg",
      accountEmail: "",
      accountEmailRules: [
        (v) => !!v || "O campo EMAIL é requerido",
        (v) => /.+@.+/.test(v) || "Insira um E-mail válido",
      ],
      accountPhone: "",
      accountPhoneRules: [(v) => !!v || "O campo CELULAR é requerido"],
      accountAccessType: "",
      accountAccessTypeRules: [
        (v) => !!v || "Por favor selecione um TIPO DE ACESSO",
      ],
      accessType: [
        {
          name: "User",
          value: "1",
        },
        {
          name: "Cliente",
          value: "2",
        },
        {
          name: "Músicos",
          value: "3",
        },
      ],
      accountPassword: "",
      accountPasswordRules: [(v) => !!v || "O campo SENHA é requerido"],
      accountStatus: false,
      searchUsers: "",
      headerUsers: [
        {
          text: "Nome",
          align: "start",
          sortable: false,
          value: "name",
        },
        {
          text: "E-mail",
          value: "email",
        },
        {
          text: "Telefone",
          value: "phone",
        },
        {
          text: "Acesso",
          value: "access",
        },
        {
          text: "Status",
          value: "status",
        },
        {
          text: "Ações",
          value: "actions",
        },
      ],
      users: [],
    };
  },

  mounted() {},

  methods: {
    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },

    clearFormUsers: function () {
      this.$refs.formAccount.reset();
      this.progressShow = false;
      this.uploadSuccess = false;
      this.uploadError = false;
    },

    closeDialog: function (d, form = null) {
      this[d] = false;
      if (form != null) {
        this.$refs[form].reset();
      }
    },

    setCrud: function (op) {
      this.crud = op;
    },

    readAccountPhoto: function (file) {
      if (file) {
        this.accountPhoto = URL.createObjectURL(file);
        this.progressShow = true;

        let data = new FormData();
        data.append("file", file);

        axios
          .post(this.urlApi + "uploadPhoto", data, {
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
            this.uploadShow = false;
          })
          .catch((error) => {
            this.uploadError = true;
            this.uploadMsg = error;
          });
      } else {
        this.accountPhoto = process.env.VUE_APP_IMGPATH + "profile.svg";
        this.uploadShow = false;
        this.uploadMsg = "";
        this.uploadSuccess = false;
        this.uploadError = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>