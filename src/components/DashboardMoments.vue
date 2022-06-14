<template>
  <div>
    <v-container class="repertorio">
      <!-- Moments Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="grey darken-4">
              <h3>Momentos</h3>
              <v-spacer></v-spacer>
              <!-- add moments -->
              <v-dialog
                v-model="dialogMoments"
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
                      <v-icon>mdi-plus</v-icon> novo momento
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogMoments', 'formMoments')"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Cadastrar Momento </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn depressed @click="saveMoments()">
                        <v-icon>mdi-content-save</v-icon> salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-card-text>
                    <v-form ref="formMoments">
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="momentsName"
                            label="Nome do Momento"
                            :rules="momentsNameRules"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="momentsDescription"
                            label="Descrição do momento"
                            :rules="momentsDescriptionRules"
                            required
                          >
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-switch
                            label="Status"
                            v-model="momentsStatus"
                          ></v-switch>
                        </v-col>
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
                :headers="headerMoments"
                :items="moments"
                :items-per-page="5"
                :search="searchMoments"
              >
                <template v-slot:item.status="{ item }">
                  <v-chip color="primary" v-if="item.status">Ativo</v-chip>
                  <v-chip v-else>Inativo</v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="primary" icon @click="editMoments(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    v-if="!item.musichasmoments"
                    color="red"
                    icon
                    @click="deleteMoments(item.idmoments)"
                    title="deletar momento"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                  <v-btn
                    v-else
                    color="grey"
                    icon
                    title="momento associado à uma musica"
                  >
                    <v-icon>mdi-delete-off</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <!-- Fim Moments Card -->
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardMoments",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      dialogMoments: false,
      momentsId: "",
      momentsName: "",
      momentsNameRules: [(v) => !!v || "O campo NOME DO MOMENTO é requerido"],
      momentsDescription: "",
      momentsDescriptionRules: [(v) => !!v || "O campo DESCRIÇÃO é requerido"],
      momentsStatus: true,
      searchMoments: "",
      headerMoments: [
        {
          text: "Momentos",
          value: "name",
        },
        {
          text: "Status",
          value: "status",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      moments: [],
    };
  },

  mounted() {},

  methods: {
    setCrud: function (op) {
      this.crud = op;
    },

    closeDialog: function (d, form = null) {
      this[d] = false;
      if (form != null) {
        this.$refs[form].reset();
      }
    },

    stopContentLoading: function () {
      this.contentLoading = false;
      this.firstLoad = false;
    },

    clearFormMoments: function () {
      this.$refs.formMoments.reset();
    },

    getMoments: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/momentos/get");
      this.moments = response.data;
      this.stopContentLoading();
    },

    deleteMoments: function (id) {
      this.$swal({
        title: "Deseja deletar o momento?",
        icon: "question",
        confirmButtonText: "Deletar",
        cancelButtonText: "Cancelar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/momentos/delete/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getMoments();
          });
        }
      });
    },

    editMoments: function (item) {
      this.dialogMoments = true;
      this.crud = "u";
      this.momentsId = item.idmoments;
      this.momentsName = item.name;
      this.momentsDescription = item.description;
      this.momentsStatus = item.status;
    },

    saveMoments: function () {
      if (this.$refs.formMoments.validate()) {
        this.closeDialog("dialogMoments");
        this.loadingVisible = true;
        if (this.momentsStatus) {
          this.momentsStatus = 1;
        } else {
          this.momentsStatus = 0;
        }
        let data = new FormData();
        data.append("name", this.momentsName);
        data.append("description", this.momentsDescription);
        data.append("status", this.momentsStatus);

        if (this.crud == "c") {
          axios(this.apiURL + "/momentos/create", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            this.clearFormMoments();
            this.momentsName = "";
            this.momentsDescription = "";
            this.momentsStatus = true;
            this.moments = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getMoments();
          });
        } else if (this.crud == "u") {
          axios(this.apiURL + "/momentos/update/" + this.momentsId, {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            this.clearFormMoments();
            this.momentsId = "";
            this.momentsName = "";
            this.momentsDescription = "";
            this.momentsStatus = true;
            this.moments = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getMoments();
          });
        }
      }
    },
  },

  created: async function () {
    await this.getMoments();
  },
};
</script>

<style lang="scss" scoped>
</style>