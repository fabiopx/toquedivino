<template>
  <div>
    <v-container class="servicos">
      <!-- Services Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="grey darken-4">
              <h3>Serviços</h3>
              <v-spacer></v-spacer>
              <!-- add service -->
              <v-dialog
                v-model="dialogService"
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
                      <v-icon class="mr-2">mdi-plus</v-icon>novo serviço
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogService', 'formService')"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Cadastrar serviço </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveService()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-form ref="formService">
                    <v-container>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="serviceName"
                            :rules="serviceNameRules"
                            label="Nome do serviço"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-textarea
                            v-model="serviceDescription"
                            :rules="serviceDescriptionRules"
                            label="Descrição do serviço"
                            required
                          >
                          </v-textarea>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-select
                            v-model="serviceHasTax"
                            label="Taxas"
                            :items="serviceTax"
                            item-text="name"
                            item-value="idtax"
                            multiple
                            chips
                          ></v-select>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
              <!-- end add service -->
            </v-toolbar>
            <!-- services table -->
            <v-card-text>
              <v-text-field
                label="Busca"
                v-model="searchService"
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
                :headers="headerServices"
                :search="searchService"
                :items="services"
                :items-per-page="5"
              >
                <template v-slot:item.taxas="{ item }">
                  <v-chip v-for="(tax, i) in item.taxas" :key="i" class="ma-2">
                    <b>{{ tax.name }}:</b> R$ {{ printMoeda(tax.value) }}
                    <span v-show="tax.type == 2">{{ tax.multiplied }}</span>
                  </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn
                    color="blue"
                    title="editar"
                    icon
                    @click="editServices(item)"
                  >
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    color="red"
                    title="deletar"
                    icon
                    @click="deleteService(item.idservice)"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
            <!-- end services table -->
          </v-card>
        </v-col>
      </v-row>
      <!-- End Services Card -->
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardServices",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      headingLoader: false,
      loadingVisible: false,
      dialogService: false,
      dialogTaxService: false,
      dialogServiceHasTax: false,
      dialogVariantTax: false,
      serviceId: "",
      serviceName: "",
      serviceNameRules: [(v) => !!v || "O campo NOME DO SERVIÇO é requerido"],
      serviceDescription: "",
      serviceDescriptionRules: [
        (v) => !!v || "O campo DESCRIÇÃO DO SERVIÇO é requerido",
      ],
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
      searchService: "",
      headerServices: [
        {
          text: "Serviço",
          value: "name",
        },
        {
          text: "Taxas",
          value: "taxas",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      services: [],
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
      serviceTax: [],
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
    clearFormService: function () {
      this.$refs.formService.reset();
    },

    getServices: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/services/get");
      this.services = response.data;
      this.stopContentLoading();
    },
    getService: async function (id) {
      this.headingLoader = true;
      const response = await axios.get(this.apiURL + "/services/get/" + id);
      this.contractService = response.data;
      this.headingLoader = false;
    },
    getTax: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/taxes/get");
      this.serviceTax = response.data;
      this.stopContentLoading();
    },
    editServices: function (item) {
      this.dialogService = true;
      this.crud = "u";
      this.serviceId = item.idservice;
      this.serviceName = item.name;
      this.serviceDescription = item.description;
      this.serviceHasTax = item.taxas
        ? item.taxas.map((item) => item.idtax)
        : [];
    },
    saveService: function () {
      this.dialogService = false;
      this.loadingVisible = true;
      let data = new FormData();
      data.append("name", this.serviceName);
      data.append("description", this.serviceDescription);
      data.append("taxas", JSON.stringify(this.serviceHasTax));
      if (this.crud == "c") {
        axios(this.apiURL + "/services/create", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormService();
          this.serviceId = "";
          this.serviceName = "";
          this.serviceDescription = "";
          this.service = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getServices();
        });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/services/update/" + this.serviceId, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormService();
          this.serviceId = "";
          this.serviceName = "";
          this.serviceDescription = "";
          this.service = [];
          this.$swal(response.data.msg, "", response.data.icon);
          this.getServices();
        });
      }
    },
    deleteService: function (id) {
      this.$swal({
        title: "Deseja deletar serviço?",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/services/delete/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getServices();
          });
        }
      });
    },
  },

  created: async function () {
    await this.getServices();
    await this.getTax();
  },
};
</script>

<style lang="scss" scoped>
</style>