<template>
  <div>
    <v-container class="assinaturas">
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="blue-grey">
              <h3>Assinaturas</h3>
              <v-spacer></v-spacer>
              <v-dialog
                v-model="dialogSignature"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-toolbar-items>
                    <v-btn
                      dark
                      text
                      v-on="on"
                      v-bind="attrs"
                      @click="setCrud('c'), getUsers()"
                    >
                      <v-icon class="mr-2">mdi-plus</v-icon>nova assinatura
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="blue-grey">
                    <v-btn icon dark @click="closeDialogSignature()">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Cadastrar Assinatura </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn
                        depressed
                        color="blue-grey"
                        @click="saveSignature()"
                      >
                        <v-icon>mdi-content-save</v-icon> salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-card-text>
                    <v-form ref="formSignature">
                      <v-row>
                        <v-col v-show="crud == 'c'">
                          <v-select
                            v-model="signatureUserName"
                            :items="users"
                            label="Nome do signatário"
                            item-text="name"
                            return-object
                          ></v-select>
                        </v-col>
                        <v-col v-show="crud == 'u'">
                          <v-text-field
                            v-model="signatureUserName.name"
                            label="Nome"
                            readonly
                          ></v-text-field>
                        </v-col>
                        <v-col>
                          <v-select
                            v-model="signatureType"
                            :items="signatureTypes"
                            label="Tipo de assinatura"
                            item-text="text"
                            item-value="type"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-select
                            v-model="signatureFont"
                            :items="signaturesFonts"
                            label="Fonte da assinatura"
                            item-text="name"
                            item-value="signClass"
                          ></v-select>
                        </v-col>
                        <v-col>
                          <v-switch label="Status" v-model="signatureStatus">
                          </v-switch>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-sheet elevation="1" rounded outlined class="pa-5">
                            <h3 :class="signatureFont">
                              {{ signatureUserName.name }}
                            </h3>
                          </v-sheet>
                        </v-col>
                        <v-col></v-col>
                      </v-row>
                    </v-form>
                  </v-card-text>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="firstLoad"
                :tableLoading="true"
                type="table"
              >
              </v-skeleton-loader>
              <v-data-table
                v-show="!firstLoad"
                :headers="headerSignatures"
                :items="signatures"
                :items-per-page="5"
                :search="searchSignatures"
              >
                <template v-slot:item.type="{ item }">
                  <v-chip v-show="item.type == 1">Contratante</v-chip>
                  <v-chip v-show="item.type == 2">Contratado</v-chip>
                  <v-chip v-show="item.type == 3">Testemunha</v-chip>
                </template>
                <template v-slot:item.status="{ item }">
                  <v-chip v-if="item.status == 1" color="primary">Ativo</v-chip>
                  <v-chip v-else>Inativo</v-chip>
                </template>
                <template v-slot:item.inuse="{ item }">
                  <v-chip v-show="item.inuse == 0" color="red" dark>Não</v-chip>
                  <v-chip v-show="item.inuse == 1" color="green" dark
                    >Sim</v-chip
                  >
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="primary" icon @click="editSignature(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    v-show="item.inuse == 0"
                    color="red"
                    icon
                    @click="deleteSignature(item.idsignature)"
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
  name: "DashboardSignatures",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      inputLoading: false,
      btnLoading: false,
      create: true,
      dialogSignature: false,
      dialogSignWithPassword: false,
      signatureId: "",
      signatureUserName: { id: "", name: "" },
      signatureType: "",
      signatureTypes: [
        {
          text: "Contratante",
          type: "1",
        },
        {
          text: "Contratado",
          type: "2",
        },
        {
          text: "Testemunha",
          type: "3",
        },
      ],
      signatureFont: "",
      signaturesFonts: [
        {
          name: "Fuggles",
          signClass: "gf-fuggles",
        },
        {
          name: "Mrs Saint Delafield",
          signClass: "gf-mrs-saint-delafield",
        },
        {
          name: "Sacramento",
          signClass: "gf-sacramento",
        },
        {
          name: "Shadows Into Light",
          signClass: "gf-shadows-into-light",
        },
        {
          name: "Caveat",
          signClass: "gf-caveat",
        },
        {
          name: "Parisienne",
          signClass: "gf-parisienne",
        },
        {
          name: "Allura",
          signClass: "gf-allura",
        },
        {
          name: "Alex Brush",
          signClass: "gf-alex-brush",
        },
        {
          name: "Dawning of a New Day",
          signClass: "gf-dawnning-of-a-new-day",
        },
        {
          name: "Kristi",
          signClass: "gf-kristi",
        },
        {
          name: "League Script",
          signClass: "gf-league-script",
        },
        {
          name: "Pinyon Script",
          signClass: "gf-pinyon-script",
        },
        {
          name: "Reenie Beanie",
          signClass: "gf-reenie-beanie",
        },
        {
          name: "Zeyada",
          signClass: "gf-zeyada",
        },
      ],
      signatureStatus: false,
      signaturePassword: "",
      signatureWithPassword: {},
      formSignWithPassword: true,
      loadingSignWithPassword: false,
      alertSignWithPassword: false,
      searchSignatures: "",
      headerSignatures: [
        {
          text: "Nome do signatário",
          value: "name",
        },
        {
          text: "Tipo de Assinatura",
          value: "type",
        },
        {
          text: "Status",
          value: "status",
        },
        {
          text: "Em Uso?",
          value: "inuse",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      signatures: [],
    };
  },

  mounted() {},

  methods: {
    saveSignature: function () {
      if (this.$refs.formSignature.validate()) {
        this.dialogSignature = false;
        this.loadingVisible = true;
        if (this.signatureStatus) {
          this.signatureStatus = 1;
        } else {
          this.signatureStatus = 0;
        }
        let data = new FormData();
        data.append("name", this.signatureUserName.name);
        data.append("account_idaccount", this.signatureUserName.idaccount);
        data.append("type", this.signatureType);
        data.append("font", this.signatureFont);
        data.append("status", this.signatureStatus);
        if (this.create) {
          axios(this.apiURL + "/createSignature", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            this.signatureUserName = { id: "", name: "" };
            this.signatureType = "";
            this.signatureFont = "";
            this.signatureStatus = false;
            this.signatures = [];
            Swal.fire(response.data.msg, "", response.data.icon);
            this.getSignature();
          });
        } else {
          axios(this.urlApi + "updateSignature/" + this.signatureId, {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            Swal.fire(response.data.msg, "", response.data.icon);
            this.getSignature();
          });
        }
      }
    },
    getSignature: function () {
      this.firstLoad = true;
      axios.get(this.urlApi + "getSignature").then((response) => {
        this.signatures = response.data;
        this.stopContentLoading();
      });
    },
    editSignature: function (item) {
      this.dialogSignature = true;
      this.crud = "u";
      this.signatureUserName.name = item.name;
      this.signatureUserName.idaccount = item.account_idaccount;
      this.signatureType = item.type;
      this.signatureFont = item.font;
      this.signatureStatus = item.status;
      this.signatureId = item.idsignature;
    },
    deleteSignature: function (id) {
      this.$swal({
        title: "Deseja deletar assinatura?",
        icon: "question",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.urlApi + "deleteSignature/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getSignature();
          });
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>