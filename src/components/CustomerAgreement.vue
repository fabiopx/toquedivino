<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark> Orçamento</v-toolbar>
            <v-card-text>
              <v-sheet class="pa-4" outlined elevation="1">
                <v-simple-table>
                  <tbody>
                    <tr>
                      <td>
                        <v-skeleton-loader
                          v-show="loadingDatas"
                          type="article"
                        ></v-skeleton-loader>
                        <div v-show="!loadingDatas">
                          <h3>Formação</h3>
                          <div class="font-italic">{{ formation.name }}</div>
                          <div class="pa-3 mb-3 rounded grey lighten-4">
                            {{ formation.description }}
                          </div>
                          <v-chip class="mb-2" color="grey darken-4" dark
                            >(+)
                            {{
                              parseFloat(formation.price).toLocaleString("pt-BR", {
                                style: "currency",
                                currency: "BRL",
                              })
                            }}</v-chip
                          >
                        </div>
                      </td>
                      <td>
                        <v-switch
                          v-model="formationChecked"
                          v-show="!isBudget"
                          inset
                          color="grey darken-4"
                          @change="addFormation()"
                        ></v-switch>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <v-skeleton-loader
                          v-show="loadingDatas"
                          type="article"
                        ></v-skeleton-loader>
                        <div v-show="!loadingDatas">
                          <h3>Serviço</h3>
                          <div class="font-italic">{{ service.name }}</div>
                          <div class="mb-3 pa-3 rounded grey lighten-4">
                            {{ service.description }}
                          </div>
                          <div
                            class="pa-3 grey lighten-4"
                            v-show="tax"
                            v-for="t in tax"
                            :key="t.idtax"
                          >
                            <h4>Taxa: {{ t.name }}</h4>
                            <p>{{ t.description }}</p>
                            <p>
                              Origem: Americana-SP<br />
                              Destino:
                              {{ events.address.city + "-" + events.address.state }}<br />
                              Distância total (ida e volta):
                              {{ (distance / 1000).toFixed("2") }} km
                            </p>
                            <v-chip color="grey darken-4" dark
                              >{{
                                parseFloat(t.value).toLocaleString("pt-BR", {
                                  style: "currency",
                                  currency: "BRL",
                                })
                              }}
                              x
                              <span v-if="t.type == '2'" class="ml-2"
                                >{{ (distance / 1000).toFixed("2") }} km</span
                              >
                            </v-chip>
                          </div>
                          <v-skeleton-loader
                            v-show="loadingTotalTax"
                            type="chip"
                            class="mt-2"
                          ></v-skeleton-loader>
                          <v-chip
                            v-show="!loadingTotalTax"
                            color="grey darken-4"
                            dark
                            class="mt-2 mb-2"
                            >(+)
                            {{
                              parseFloat(multipliedTaxValue.toFixed("2")).toLocaleString(
                                "pt-BR",
                                {
                                  style: "currency",
                                  currency: "BRL",
                                }
                              )
                            }}</v-chip
                          >
                        </div>
                      </td>
                      <td>
                        <v-switch
                          v-model="serviceChecked"
                          v-show="!isBudget"
                          inset
                          color="grey darken-4"
                          @change="addTax()"
                        >
                        </v-switch>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <v-skeleton-loader
                          v-show="loadingDatas"
                          type="article"
                        ></v-skeleton-loader>
                        <div v-show="!loadingDatas">
                          <h3>Evento</h3>
                          <div>Data: {{ $moment(events.date).format("DD/MM/YYYY") }}</div>
                          <div>Horário: {{ events.time }}</div>
                          <div>
                            Endereço: {{ events.address.street }},
                            {{ events.address.number }}
                            {{
                              events.address.complement
                                ? ", " + events.address.complement
                                : ""
                            }}
                            {{ events.address.neigborhood }}, {{ events.address.city }} -
                            {{ events.address.state }},
                            {{ events.address.zipcode }}
                          </div>
                        </div>
                      </td>
                      <td>
                        <v-switch
                          v-model="eventChecked"
                          v-show="!isBudget"
                          inset
                          color="grey darken-4"
                        >
                        </v-switch>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <v-skeleton-loader
                          v-show="loadingSomaBudget"
                          type="text"
                        ></v-skeleton-loader>
                        <v-chip v-show="!loadingSomaBudget" class="grey darken-4" dark>
                          <b class="text-uppercase">
                            (=)
                            {{
                              parseFloat(budgetSoma).toLocaleString("pt-BR", {
                                style: "currency",
                                currency: "BRL",
                              })
                            }}</b
                          >
                        </v-chip>
                      </td>
                    </tr>
                  </tbody>
                </v-simple-table>
                <div
                  class="mt-3 pa-3 red darken-4 white--text rounded"
                  v-show="!loadingSomaBudget"
                  v-if="isBudget"
                >
                  Orçamento válido até
                  {{ $moment(budget.expires_in).format("DD/MM/YYYY") }}
                  <span class="ml-4"
                    >Status do orçamento:
                    <span v-if="budget.status == 0"
                      >Iniciado: aguardando validação da Equipe Toque Divino</span
                    >
                    <span v-if="budget.status == 1">Validado</span>
                    <span v-if="budget.status == 2">Finalizado</span>
                    <span v-if="budget.status == 3">Expirado</span>
                    <span v-if="budget.status == 4">Cancelado</span>
                  </span>
                </div>
              </v-sheet>
            </v-card-text>
            <v-card-actions>
              <div class="ml-3" v-show="!loadingDatas">
                <v-btn
                  v-show="!isBudget"
                  color="red darken-4"
                  class="mt-3 pa-4"
                  dark
                  @click="verifyAllChecked()"
                >
                  <v-icon class="mr-2">mdi-content-save</v-icon>Salvar orçamento
                </v-btn>
                <v-btn
                  v-show="!isBudget"
                  color="red darken-4"
                  class="mt-3 ml-2 pa-4"
                  dark
                  @click="$router.push('/customer/inscribe')"
                >
                  <v-icon class="mr-2">mdi-pencil</v-icon>Alterar o cadastro
                </v-btn>
              </div>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    formation: {},
    service: {},
    events: { address: {} },
    formationChecked: false,
    serviceChecked: false,
    eventChecked: false,
    budget: "",
    budgetItems: [],
    budgetSoma: 0,
    isBudget: false,
    distance: "",
    tax: [],
    multipliedTaxValue: 0,
    loadingDatas: false,
    loadingTotalTax: true,
    loadingSomaBudget: false,
  }),
  methods: {
    ...mapActions(["setInscribeID", "setUserNow"]),

    somaBudget: function (item) {
      this.budgetSoma = parseFloat(this.budgetSoma) + parseFloat(item);
    },
    subtractBudget: function (item) {
      this.budgetSoma = parseFloat(this.budgetSoma) - parseFloat(item);
    },
    addFormation: function () {
      this.formationChecked
        ? this.somaBudget(this.formation.price)
        : this.subtractBudget(this.formation.price);
    },
    addTax: function () {
      this.serviceChecked
        ? this.somaBudget(this.multipliedTaxValue)
        : this.subtractBudget(this.multipliedTaxValue);
    },
    verifyAllChecked: function () {
      if (this.formationChecked && this.serviceChecked && this.eventChecked) {
        this.createBudget();
      } else {
        this.$swal("É preciso confirmar todas as informações", "", "error");
      }
    },
    getInscribe: async function () {
      this.loadingDatas = true;
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      this.formation = response.data.formation;
      this.service = response.data.service;
      await this.getServiceTax();
      this.loadingDatas = false;
    },
    getEvent: async function () {
      this.loadingDatas = true;
      const response = await axios.get(
        this.apiURL + "/events/getCustomers/" + this.inscribeID
      );
      this.events = response.data;
      this.distance = await this.getDistance();
      this.loadingDatas = false;
    },
    getServiceTax: async function () {
      const response = await axios.get(
        this.apiURL + "/services/get/" + this.service.idservice
      );
      // console.log(response.data.taxas);
      this.tax = response.data.taxas;
    },
    getDistance() {
      return new Promise((resolve, reject) => {
        var service = new google.maps.DistanceMatrixService();
        service
          .getDistanceMatrix({
            origins: ["Americana-SP"],
            destinations: [this.events.address.city],
            travelMode: "DRIVING",
          })
          .then((response) => {
            // console.log(response.rows[0].elements[0].distance.value * 2);
            resolve(response.rows[0].elements[0].distance.value * 2);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    calculateTaxValue: function () {
      setInterval(() => {
        var tax = this.tax;
        tax.forEach((t) => {
          if (t.multiplied == "por km") {
            this.multipliedTaxValue =
              parseFloat(t.value) * (parseFloat(this.distance) / 1000);
          }
        });
        this.loadingTotalTax = false;
      }, 1000);
    },
    verifyBudget: async function () {
      const response = await axios.get(
        this.apiURL + "/budgets/isBudget/" + this.inscribeID
      );
      this.isBudget = response.data;
    },
    createBudget: async function () {
      var data = new FormData();
      data.append("date", this.$moment().format("YYYY-MM-DD"));
      data.append("value", this.budgetSoma);
      data.append("discount", null);
      data.append("addition", null);
      data.append("expires_in", this.$moment().add(15, "days").format("YYYY/MM/DD"));
      data.append("status", 0);
      data.append("inscribe_idinscribe", this.inscribeID);
      const response = await axios({
        method: "post",
        url: this.apiURL + "/budgets/create",
        data: data,
      });
      this.$swal(response.data.msg, "", response.data.icon);
      this.verifyBudget();
      this.getBudget();
    },
    getBudget: function () {
      this.loadingSomaBudget = true;
      axios.get(this.apiURL + "/budgets/get/" + this.inscribeID).then((response) => {
        this.budgetSoma = response.data ? response.data.value : 0;
        this.budget = response.data;
        this.loadingSomaBudget = false;
      });
    },
  },
  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
      this.getEvent();
      this.getInscribe();
      this.calculateTaxValue();
      this.verifyBudget();
      this.getBudget();
    }
  },
  computed: {
    ...mapGetters(["inscribeID", "userNow", "isAgreement"]),
  },
  formationChecked: function () {
    this.formationChecked
      ? this.somaBudget(this.formation.price)
      : this.subtractBudget(this.formation.price);
  },
};
</script>
