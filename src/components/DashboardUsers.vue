<template>
  <div>
    <v-container class="usuarios">
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="grey darken-4">
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
                  <v-toolbar dark color="grey darken-4">
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
                            label="Telefone do Usuário"
                          >
                          </v-text-field>
                        </v-col>
                        <v-col>
                          <v-text-field
                          v-model="accountMobile"
                          v-mask="maskTel(accountMobile)"
                          label="Celular do Usuário"
                          ></v-text-field>
                        </v-col>
                        
                      </v-row>
                      <v-row>
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
                <template v-slot:item.account.access="{ item }">
                  <span v-show="item.account.access == 0">Admin</span>
                  <span v-show="item.account.access == 1">Usuário</span>
                </template>
                <template v-slot:item.account.status="{ item }">
                  <v-chip v-show="item.account.status == 1" color="primary">
                    Ativo</v-chip
                  >
                  <v-chip v-show="item.account.status == 0"> Inativo</v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="blue" icon @click="editUsers(item)">
                    <v-icon>mdi-account-edit</v-icon>
                  </v-btn>
                  <v-btn
                    v-if="item.access != 0 && item.access != 4"
                    color="red"
                    icon
                    @click="deleteUser(item.account.idaccount)"
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
      apiURL: process.env.VUE_APP_URL,
      dialogUsers: false,
      showPassword: false,
      firstLoad: false,
      loadingVisible: false,
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
      accountPhoneRules: [(v) => !!v || "O campo TELEFONE é requerido"],
      accountMobile: "",
      accountMobileRules: [(v) => !!v || "O campo CELULAR é requerido"],
      accountAccessType: "",
      accountAccessTypeRules: [
        (v) => !!v || "Por favor selecione um TIPO DE ACESSO",
      ],
      accessType: [
        {
          name: "Administrador de sistema",
          value: "0",
        },
        {
          name: "Usuário de sistema",
          value: "1",
        }
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
          value: "account.email",
        },
        {
          text: "Telefone",
          value: "phone",
        },
        {
          text: "Acesso",
          value: "account.access",
        },
        {
          text: "Status",
          value: "account.status",
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

    stopContentLoading: function () {
      this.contentLoading = false;
      this.firstLoad = false;
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

    getUsers: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/users/get");
      this.users = response.data;
      this.stopContentLoading();
    },

    editUsers: function (item) {
      this.dialogUsers = true;
      this.crud = "u";
      this.accountId = item.account.idaccount;
      this.accountName = item.name;
      this.accountEmail = item.account.email;
      this.accountPhone = item.phone;
      this.accountMobile = item.mobile;
      this.accountAccessType = item.account.access;
      this.accountPassword = item.account.password;
      this.accountStatus = item.account.status;
    },

    saveUser: function () {
      if (this.$refs.formAccount.validate()) {
        this.dialogUsers = false;
        this.loadingVisible = true;
        if (this.accountStatus) {
          this.accountStatus = 1;
        } else {
          this.accountStatus = 0;
        }
        let data = new FormData();
        data.append("name", this.accountName);
        data.append("email", this.accountEmail);
        data.append("password", this.accountPassword);
        data.append("status", this.accountStatus);
        data.append("phone", this.accountPhone);
        data.append("mobile", this.accountMobile);
        data.append("access", this.accountAccessType);
        if (this.crud == "u") {
          axios(this.apiURL + "/users/update/" + this.accountId, {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            this.progressShow = false;
            this.uploadError = false;
            this.uploadSuccess = false;
            this.accountId = "";
            this.accountName = "";
            this.accountEmail = "";
            this.accountPhone = "";
            this.accountMobile = "";
            this.accountAccessType = "";
            this.accountPassword = "";
            this.accountPhoto = process.env.VUE_APP_IMGPATH + "profile.svg";
            this.users = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getUsers();
          });
        } else if (this.crud == "c") {
          axios(this.apiURL + "/users/create", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.accountId = "";
            this.accountName = "";
            this.accountEmail = "";
            this.accountPhone = "";
            this.accountMobile = "";
            this.accountAccessType = "";
            this.accountPassword = "";
            this.users = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getUsers();
          });
        }
      }
    },

    deleteUser: function (id) {
      this.$swal({
        title: "Deseja deletar usuário?",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/users/delete/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getUsers();
          });
        }
      });
    },
  },

  created: async function () {
    await this.getUsers();
  },
};
</script>

<style lang="scss" scoped>
</style>