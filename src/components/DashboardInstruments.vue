<template>
  <div>
    <v-container class="instrumentos">
      <!-- Instrument Card -->
      <v-row>
        <v-col>
          <v-card>
            <!-- add instrument -->
            <v-toolbar color="grey darken-4" dark>
              <h3>Instrumentos</h3>
              <v-spacer></v-spacer>

              <v-dialog
                v-model="dialogInstrument"
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
                      <v-icon>mdi-plus</v-icon> novo instrumento
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="
                        closeDialog('dialogInstrument'), clearFormInstrument()
                      "
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                      <v-icon>mdi-piano</v-icon> Instrumento musical
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveInstrument()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-form ref="formInstrument">
                    <v-container>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="instrumentName"
                            :rules="instrumentNameRules"
                            label="Nome do instrumento"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-select
                            v-model="instrumentIcon"
                            :items="instruments"
                            item-text="text"
                            item-value="icon"
                            label="Imagem do Instrumento"
                          >
                          </v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-img
                            max-height="150"
                            max-width="150"
                            :src="instrumentIcon"
                          >
                          </v-img>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <!-- end add instrument -->
            <!-- table instrument -->
            <v-card-text>
              <v-text-field
                v-model="searchInstrument"
                label="Busca"
                append-icon="mdi-magnify"
              >
              </v-text-field>
              <v-skeleton-loader
                v-if="firstLoad"
                :tableLoading="true"
                type="table"
              >
              </v-skeleton-loader>
              <v-data-table
                v-show="!firstLoad"
                :headers="headerInstrument"
                :search="searchInstrument"
                :items="instrument"
                :items-per-page="5"
              >
                
                <template v-slot:item.image="{ item }">
                  <v-avatar>
                    <v-img :src="instrumentPath + item.image"></v-img>
                  </v-avatar>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="blue" icon @click="editInstrument(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    v-if="!item.formationhasinstrument"
                    color="red"
                    icon
                    @click="deleteInstrument(item.idinstrument)"
                    title="deletar instrumento"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                  <v-btn
                    v-else
                    color="grey"
                    icon
                    title="instrumento associado a uma formação"
                  >
                    <v-icon>mdi-delete-off</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
            <!-- end table instrument -->
          </v-card>
        </v-col>
      </v-row>
      <!-- Fim Instrumento Card -->
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardInstruments",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      dialogFormation: false,
      dialogInstrument: false,
      dialogFormationHasInstrument: false,
      instrumentPath: process.env.VUE_APP_INSTRUMENTS,
      formationId: "",
      formationName: "",
      formationNameRules: [
        (v) => !!v || "O campo NOME DA FORMAÇÃO é requerido",
      ],
      formationDescription: "",
      formationDescriptionRules: [
        (v) => !!v || "O campo DESCRIÇÃO é requerido",
      ],
      formationPrice: "",
      formationPriceRules: [(v) => !!v || "O campo PREÇO é requerido"],
      formationVideo: "",
      instrumentId: "",
      instrumentName: "",
      instrumentNameRules: [
        (v) => !!v || "O campo NOME DO INSTRUMENTO é requerido",
      ],
      instrumentIcon: process.env.VUE_APP_INSTRUMENTS + "picture.svg",
      instrumentIconRules: [
        (v) => !!v || "O campo IMAGEM DO INSTRUMENTO é requerido",
      ],
      instrumentSound: null,
      instrumentSoundRules: [
        (v) => v > 5000000 || "Arquivo deve ser menor que 5MB",
      ],
      instruments: [
        {
          text: "Cantor solo",
          icon: process.env.VUE_APP_INSTRUMENTS + "singer.svg",
        },
        {
          text: "Clarim",
          icon: process.env.VUE_APP_INSTRUMENTS + "trumpet.svg",
        },
        {
          text: "Coral de 4 vozes",
          icon: process.env.VUE_APP_INSTRUMENTS + "choir_4voices.svg",
        },
        {
          text: "Coral de 8 Vozes",
          icon: process.env.VUE_APP_INSTRUMENTS + "choir.svg",
        },
        {
          text: "Dueto Vocal",
          icon: process.env.VUE_APP_INSTRUMENTS + "duet.svg",
        },
        {
          text: "Flauta",
          icon: process.env.VUE_APP_INSTRUMENTS + "flute.svg",
        },
        {
          text: "Harpa",
          icon: process.env.VUE_APP_INSTRUMENTS + "harp.svg",
        },
        {
          text: "Sax",
          icon: process.env.VUE_APP_INSTRUMENTS + "sax.svg",
        },
        {
          text: "Percussão",
          icon: process.env.VUE_APP_INSTRUMENTS + "drums.svg",
        },
        {
          text: "Piano Digital",
          icon: process.env.VUE_APP_INSTRUMENTS + "piano.svg",
        },
        {
          text: "Piano 1/4 de cauda",
          icon: process.env.VUE_APP_INSTRUMENTS + "grand-piano.svg",
        },
        {
          text: "Tímpano",
          icon: process.env.VUE_APP_INSTRUMENTS + "drum.svg",
        },
        {
          text: "Viola",
          icon: process.env.VUE_APP_INSTRUMENTS + "viola.svg",
        },
        {
          text: "Violino",
          icon: process.env.VUE_APP_INSTRUMENTS + "violin.svg",
        },
        {
          text: "Violoncelo",
          icon: process.env.VUE_APP_INSTRUMENTS + "cello.svg",
        },
      ],
      searchFormation: "",
      headerFormation: [
        {
          text: "Formação",
          align: "start",
          value: "name",
        },
        {
          text: "Preço",
          value: "price",
        },
        {
          text: "Instrumentos",
          value: "instruments",
          sortable: false,
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      formation: [],
      searchInstrument: "",
      headerInstrument: [
        {
          text: "Instrumento",
          align: "start",
          value: "name",
        },
        {
          text: "Imagem",
          sortable: false,
          value: "image",
        },
        // {
        //     text: 'Audio',
        //     sortable: false,
        //     value: 'sound'
        // },
        {
          text: "Ações",
          sortable: false,
          value: "actions",
        },
      ],
      instrument: [],
      searchFormationHasInstrument: "",
      headerFormationHasInstrument: [
        {
          text: "Formação",
          value: "nameFormation",
        },
        {
          text: "Instrumento",
          value: "nameInstrument",
        },
        {
          text: "Ações",
          value: "actions",
        },
      ],
      formationHasInstrument: [],
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

    clearFormInstrument: function () {
      this.$refs.formInstrument.reset();
      this.instrumentIcon = this.instrumentPath + "picture.svg";
    },

    getInstrument: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/instruments/get");
      this.instrument = response.data;
      this.stopContentLoading();
    },

    deleteInstrument: function (id) {
      this.$swal({
        title: "Deseja deletar instrumento?",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .get(this.apiURL + "/instruments/delete/" + id)
            .then((response) => {
              this.$swal(response.data.msg, "", response.data.icon);
              this.getInstrument();
            });
        }
      });
    },

    editInstrument: function (item) {
      this.dialogInstrument = true;
      this.crud = "u";
      this.nInstrument = false;
      this.instrumentId = item.idinstrument;
      this.instrumentName = item.name;
      this.instrumentIcon = item.image;
      this.instrumentSound = item.sound;
    },

    saveInstrument: function () {
      this.closeDialog("dialogInstrument");
      this.loadingVisible = true;
      let data = new FormData();
      data.append("name", this.instrumentName);
      data.append("image", this.instrumentIcon);
      if (this.crud == "c") {
        axios(this.apiURL + "/instruments/create", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormInstrument();
          this.instrumentId = "";
          this.instrumentName = "";
          this.instrumentIcon = this.instrumentPath + "picture.svg";
          this.instrument = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getInstrument();
        });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/instruments/update/" + this.instrumentId, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormInstrument();
          this.instrumentId = "";
          this.instrumentName = "";
          this.instrumentIcon = this.instrumentPath + "picture.svg";
          this.instrument = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getInstrument();
        });
      }
    },
  },

  created: async function(){
    await this.getInstrument();
  }
};
</script>

<style lang="scss" scoped>
</style>