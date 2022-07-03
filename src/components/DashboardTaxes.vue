<template>
  <div>
    <!-- TAXAS -->
    <v-container class="taxas">
      <!-- Tax Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <h3>Taxas</h3>
              <v-spacer></v-spacer>
              <!-- add tax -->
              <v-dialog
                v-model="dialogTaxService"
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
                      <v-icon class="mr-2">mdi-plus</v-icon>nova taxa
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogTaxService', 'formServiceTax')"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Taxa de Serviço </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveServiceTax()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-form ref="formServiceTax">
                    <v-container>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="serviceTaxName"
                            :rules="serviceTaxNameRules"
                            label="Nome da Taxa"
                            required
                          >
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-textarea
                            v-model="serviceTaxDescription"
                            :rules="serviceTaxDescriptionRules"
                            label="Descrição da taxa"
                            required
                          >
                          </v-textarea>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-select
                            v-model="serviceTaxType"
                            label="Tipo de Taxa"
                            :items="serviceTaxTypeSelect"
                            item-text="name"
                            item-value="value"
                            :rules="serviceTaxTypeRules"
                            required
                          >
                          </v-select>
                        </v-col>
                        <v-col v-show="serviceTaxType == 2">
                          <v-select
                            v-model="variantTaxName"
                            label="Multiplicar"
                            :items="variantTax"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-currency-field
                            v-model="serviceTaxValue"
                            :rules="serviceTaxValueRules"
                            label="Valor"
                            append-icon="mdi-currency-usd"
                          >
                          </v-currency-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <!-- tax table -->
            <v-card-text>
              <v-text-field
                v-model="searchServiceTax"
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
                :headers="headerServiceTax"
                :search="searchServiceTax"
                :items="serviceTax"
                :items-per-page="5"
              >
                <template v-slot:item.value="{ item }">
                  <span>R$ {{ printMoeda(item.value) }}</span>
                </template>
                <template v-slot:item.type="{ item }">
                  <span v-show="item.type == 1">Fixa</span>
                  <span v-show="item.type == 2">Multiplicada</span>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="blue" icon @click="editServiceTax(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    v-if="!item.servicehastax"
                    color="red"
                    icon
                    @click="deleteTax(item.idtax)"
                    title="deletar service"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                  <v-btn
                    v-else
                    color="grey"
                    icon
                    title="taxa associada a um serviço"
                  >
                    <v-icon>mdi-delete-off</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
            <!-- end add tax -->
          </v-card>
        </v-col>
      </v-row>
      <!-- End Tax Card -->
    </v-container>
    <!-- FIM TAXAS -->
  </div>
</template>

<script>
export default {
  name: "DashboardTaxes",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      dialogTaxService: false,
      dialogServiceHasTax: false,
      dialogVariantTax: false,
      taxId: "",
      serviceTaxName: "",
      serviceTaxNameRules: [(v) => !!v || "O campo TAXA é requerido"],
      serviceTaxDescription: "",
      serviceTaxDescriptionRules: [
        (v) => !!v || "O campo DESCRIÇÃO DA TAXA é requerido",
      ],
      serviceTaxType: "",
      serviceTaxTypeSelect: [
        {
          name: "Fixa",
          value: "1",
        },
        {
          name: "Multiplicada",
          value: "2",
        },
      ],
      serviceTaxTypeRules: [(v) => !!v || "O campo TIPO DE TAXA é requerido"],
      serviceTaxValue: "",
      serviceTaxValueRules: [(v) => !!v || "O campo VALOR é requerido"],
      searchServiceTax: "",
      headerServiceTax: [
        {
          text: "Taxa",
          value: "name",
        },
        {
          text: "Descrição",
          value: "description",
        },
        {
          text: "Valor",
          value: "value",
        },
        {
          text: "Tipo",
          value: "type",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      serviceTax: [],
      searchServiceHasTax: "",
      headerServiceHasTax: [
        {
          text: "Serviço",
          value: "nameService",
        },
        {
          text: "Taxa",
          value: "nameTax",
        },
        {
          text: "Ações",
          value: "actions",
        },
      ],
      serviceHasTax: [],
      variantTax: ["por km", "por pessoa", "por instrumento", "por música"],
      variantTaxName: "",
      variantTaxValue: "",
      variantTaxTag: "",
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

    clearFormServiceTax: function () {
      this.$refs.formServiceTax.reset();
    },

    getTax: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/taxes/get");
      this.serviceTax = response.data;
      this.stopContentLoading();
    },

    editServiceTax: function (item) {
      this.dialogTaxService = true;
      this.serviceTaxName = item.name;
      this.crud = "u";
      this.taxId = item.idtax;
      this.serviceTaxDescription = item.description;
      this.serviceTaxType = item.type;
      this.variantTaxName = item.multiplied;
      this.serviceTaxValue = item.value;
    },

    saveServiceTax: function () {
      this.closeDialog("dialogTaxService");
      this.loadingVisible = true;
      let data = new FormData();
      let taxId = this.serviceTaxId;
      data.append("name", this.serviceTaxName);
      data.append("description", this.serviceTaxDescription);
      data.append("value", this.serviceTaxValue);
      data.append("type", this.serviceTaxType);
      data.append("multiplied", this.variantTaxName);
      if (this.crud == "c") {
        axios(this.apiURL + "/taxes/create", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormServiceTax();
          this.taxId = "";
          this.serviceTaxName = "";
          this.serviceTaxDescription = "";
          this.serviceTaxType = "";
          this.serviceTaxValue = "";
          this.serviceTax = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getTax();
        });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/taxes/update/" + this.taxId, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormServiceTax();
          this.taxId = "";
          this.serviceTaxName = "";
          this.serviceTaxDescription = "";
          this.serviceTaxType = "";
          this.serviceTaxValue = "";
          this.serviceTax = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getTax();
        });
      }
    },

    deleteTax: function (id) {
      this.$swal({
        title: "Deseja deletar taxa?",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/taxes/delete/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getTax();
          });
        }
      });
    },
  },

  created: async function () {
    await this.getTax();
  },
};
</script>

<style lang="scss" scoped>
</style>