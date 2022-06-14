<template>
  <div>
    <v-container class="formacoes">
      <!-- Formation Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4">
              <h3 class="white--text">Formações</h3>
              <v-spacer></v-spacer>
              <!-- add formation -->
              <v-dialog
                v-model="dialogFormation"
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
                      <v-icon>mdi-plus</v-icon> nova formação
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogFormation', 'formFormation')"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                      <v-icon>mdi-shape-plus</v-icon> Cadastrar Nova Formação
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveFormation()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-form ref="formFormation">
                    <v-container>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="formationName"
                            :rules="formationNameRules"
                            label="Nome da formação"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-textarea
                            v-model="formationDescription"
                            :rules="formationDescriptionRules"
                            label="Descrição da formação"
                            required
                          >
                          </v-textarea>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="formationPrice"
                            :rules="formationPriceRules"
                            label="Preço da formação"
                            required
                          >
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-select
                            v-model="formationHasInstrument"
                            label="Instrumentos"
                            :items="instrument"
                            item-text="name"
                            item-value="idinstrument"
                            multiple
                            chips
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="formationVideo"
                            label="Vídeo da Formação"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <!-- formation table -->
            <v-card-text>
              <v-text-field
                v-model="searchFormation"
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
                :headers="headerFormation"
                :search="searchFormation"
                :items="formation"
                :items-per-page="5"
              >
                <template v-slot:item.price="{ item }">
                  {{
                    parseFloat(item.price).toLocaleString("pt-BR", {
                      style: "currency",
                      currency: "BRL",
                    })
                  }}
                </template>
                <template v-slot:item.instruments="{ item }">
                  <v-chip
                    v-for="(inst, i) in item.instruments"
                    :key="i"
                    class="ma-2"
                  >
                    <v-avatar color="white" class="mr-2">
                      <v-img :src="instrumentPath + inst.image"></v-img>
                    </v-avatar>
                    {{ inst.name }}
                  </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="blue" icon @click="editFormation(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    color="red"
                    icon
                    @click="deleteFormation(item.idformation)"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <!-- End Formation Card -->
    </v-container>
  </div>
</template>

<script>
export default {
  name: "ToquedivinoDashboardformations",

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
      instrumentIcon: this.instrumentPath + "picture.svg",
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
          icon: this.instrumentPath + "singer.svg",
        },
        {
          text: "Clarim",
          icon: this.instrumentPath + "trumpet.svg",
        },
        {
          text: "Coral de 4 vozes",
          icon: this.instrumentPath + "choir_4voices.svg",
        },
        {
          text: "Coral de 8 Vozes",
          icon: this.instrumentPath + "choir.svg",
        },
        {
          text: "Dueto Vocal",
          icon: this.instrumentPath + "duet.svg",
        },
        {
          text: "Flauta",
          icon: this.instrumentPath + "flute.svg",
        },
        {
          text: "Harpa",
          icon: this.instrumentPath + "harp.svg",
        },
        {
          text: "Sax",
          icon: this.instrumentPath + "sax.svg",
        },
        {
          text: "Percussão",
          icon: this.instrumentPath + "drums.svg",
        },
        {
          text: "Piano Digital",
          icon: this.instrumentPath + "piano.svg",
        },
        {
          text: "Piano 1/4 de cauda",
          icon: this.instrumentPath + "grand-piano.svg",
        },
        {
          text: "Tímpano",
          icon: this.instrumentPath + "drum.svg",
        },
        {
          text: "Viola",
          icon: this.instrumentPath + "viola.svg",
        },
        {
          text: "Violino",
          icon: this.instrumentPath + "violin.svg",
        },
        {
          text: "Violoncelo",
          icon: this.instrumentPath + "cello.svg",
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

    printMoeda: function (value) {
      let val = (value / 1).toFixed(2).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    moneyFormat: function () {
      return parseFloat(this.formationPrice).toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL",
      });
    },

    getFormations: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/formations/get");
      this.formation = response.data;
      this.stopContentLoading();
    },

    editFormation: function (item) {
      this.dialogFormation = true;
      this.crud = "u";
      this.formationId = item.idformation;
      this.formationName = item.name;
      this.formationDescription = item.description;
      this.formationPrice = item.price;
      this.formationHasInstrument = item.instruments.map(
        (item) => item.idinstrument
      );
      this.formationVideo = item.video;
    },

    deleteFormation: function (id) {
      this.$swal({
        title: "Deseja deletar formação?",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .get(this.apiURL + "/formations/delete/" + id)
            .then((response) => {
              this.$swal(response.data.msg, "", response.data.icon);
              this.getFormations();
            });
        }
      });
    },

    clearFormFormation: function () {
      this.$refs.formFormation.reset();
    },

    getInstrument: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/instruments/get");
      this.instrument = response.data;
      this.stopContentLoading();
    },

    saveFormation: function () {
      this.closeDialog("dialogFormation");
      this.loadingVisible = true;
      let data = new FormData();
      data.append("name", this.formationName);
      data.append("description", this.formationDescription);
      data.append("price", this.formationPrice);
      data.append("instruments", JSON.stringify(this.formationHasInstrument));
      data.append("video", this.formationVideo);
      if (this.crud == "c") {
        axios(this.apiURL + "/formations/create", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormFormation();
          this.formationId = "";
          this.formationName = "";
          this.formationDescription = "";
          this.formationPrice = "";
          this.formation = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getFormations();
        });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/formations/update/" + this.formationId, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormFormation();
          this.formationId = "";
          this.formationName = "";
          this.formationDescription = "";
          this.formationPrice = "";
          this.formation = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getFormations();
        });
      }
    },
  },

  created: async function () {
    await this.getFormations();
    await this.getInstrument();
  },
};
</script>

<style lang="scss" scoped>
</style>