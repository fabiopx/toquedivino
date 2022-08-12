<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <v-card v-if="access.isEvent">
            <v-toolbar color="grey darken-4" dark>
              <h2>
                <v-icon class="mr-3">mdi-file-document-edit</v-icon>Orçamento
              </h2>
            </v-toolbar>
            <v-card-text v-if="budgetOpen">
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
                          <h3>
                            Formação
                            <v-btn
                              icon
                              @click="$router.push('/customer/inscribe')"
                              ><v-icon>mdi-pencil</v-icon></v-btn
                            >
                          </h3>
                          <div class="font-italic">{{ formation.name }}</div>
                          <div class="pa-3 mb-3 rounded grey lighten-4">
                            {{ formation.description }}
                          </div>
                          <v-chip class="mb-2" color="grey darken-4" dark
                            >(+)
                            {{
                              parseFloat(formation.price).toLocaleString(
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
                          v-model="formationChecked"
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
                          <h3>
                            Serviço
                            <v-btn
                              icon
                              @click="$router.push('/customer/inscribe')"
                              ><v-icon>mdi-pencil</v-icon></v-btn
                            >
                          </h3>
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
                            <p v-if="t.mutiplied == 'por km'">
                              Origem: Americana-SP<br />
                              Destino:
                              {{
                                events.address.city +
                                "-" +
                                events.address.state
                              }}<br />
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

                              <span v-if="t.multiplied == 'por km'" class="ml-2"
                                >x {{ (distance / 1000).toFixed("2") }} km</span
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
                              parseFloat(
                                multipliedTaxValue.toFixed("2")
                              ).toLocaleString("pt-BR", {
                                style: "currency",
                                currency: "BRL",
                              })
                            }}</v-chip
                          >
                        </div>
                      </td>
                      <td>
                        <v-switch
                          v-model="serviceChecked"
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
                          <h3>
                            Evento
                            <v-btn icon @click="$router.push('/customer/event')"
                              ><v-icon>mdi-pencil</v-icon></v-btn
                            >
                          </h3>
                          <div>
                            Data:
                            {{ $moment(events.date).format("DD/MM/YYYY") }}
                          </div>
                          <div>Horário: {{ events.time }}</div>
                          <div>
                            Endereço: {{ events.address.street }},
                            {{ events.address.number }}
                            {{
                              events.address.complement
                                ? ", " + events.address.complement
                                : ""
                            }}
                            {{ events.address.neigborhood }},
                            {{ events.address.city }} -
                            {{ events.address.state }},
                            {{ events.address.zipcode }}
                          </div>
                        </div>
                      </td>
                      <td>
                        <v-switch
                          v-model="eventChecked"
                          inset
                          color="grey darken-4"
                        >
                        </v-switch>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <v-skeleton-loader
                          v-show="loadingDatas"
                          type="chip"
                        ></v-skeleton-loader>
                        <v-chip
                          v-show="!loadingDatas"
                          class="grey darken-4"
                          dark
                        >
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
                <div class="ml-3" v-show="!loadingDatas">
                  <v-btn
                    v-show="budgetOpen"
                    color="red darken-4"
                    class="mt-3 pa-4"
                    dark
                    @click="verifyAllChecked()"
                  >
                    <v-icon class="mr-2">mdi-content-save</v-icon>Salvar
                    orçamento
                  </v-btn>
                </div>
              </v-sheet>
            </v-card-text>
            <v-card-text v-if="access.isBudget">
              <v-sheet>
                <v-skeleton-loader
                  type="table-thead, table-row"
                  v-show="loadingBudget"
                ></v-skeleton-loader>
                <v-simple-table v-show="!loadingBudget">
                  <thead>
                    <tr>
                      <th>Nº do Orçamento</th>
                      <th>Data</th>
                      <th>Expira em...</th>
                      <th>Valor</th>
                      <th>Status</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="budget in budgetItems" :key="budget.idbudget">
                      <td>{{ budget.code }}</td>
                      <td>{{ $moment(budget.date).format("DD/MM/YYYY") }}</td>
                      <td>
                        {{ $moment(budget.expires_in).format("DD/MM/YYYY") }}
                      </td>
                      <td>
                        {{
                          parseFloat(budget.value).toLocaleString("pt-BR", {
                            style: "currency",
                            currency: "BRL",
                          })
                        }}
                      </td>
                      <td>
                        <v-chip color="primary" v-show="budget.status == 0"
                          >Iniciado</v-chip
                        >
                        <v-chip color="green" dark v-show="budget.status == 1"
                          >Validado</v-chip
                        >
                        <v-chip color="blue" dark v-show="budget.status == 2"
                          >Finalizado</v-chip
                        >
                        <v-chip color="orange" dark v-show="budget.status == 3"
                          >Expirado</v-chip
                        >
                        <v-chip color="red" dark v-show="budget.status == 4"
                          >Cancelado</v-chip
                        >
                      </td>
                      <td>
                        <v-btn
                          v-show="
                            budget.status == 0 ||
                            budget.status == 1 ||
                            budget.status == 3
                          "
                          icon
                          @click="cancelBudget(budget.idbudget)"
                          ><v-icon>mdi-cancel</v-icon></v-btn
                        >
                        <v-btn
                          v-show="budget.status == 1"
                          icon
                          @click="$router.push('/customer/agreement')"
                          ><v-icon>mdi-file-sign</v-icon></v-btn
                        >
                        <v-btn
                          v-show="budget.status == 3"
                          icon
                          @click="extendExpirationDate(budget.idbudget)"
                          ><v-icon>mdi-calendar-clock</v-icon></v-btn
                        >
                      </td>
                    </tr>
                  </tbody>
                </v-simple-table>
              </v-sheet>
            </v-card-text>
          </v-card>
          <v-card v-else>
            <v-toolbar color="grey darken-4" dark><h3>Ops!</h3></v-toolbar>
            <v-card-text>
              <p>Você ainda não cadastrou seu evento!</p>
              <p>
                <v-btn
                  depressed
                  color="red darken-4"
                  dark
                  class="pa-8"
                  @click="$router.push('/customer/event')"
                  ><v-icon class="mr-3">mdi-calendar-check</v-icon>Cadastrar
                  evento</v-btn
                >
              </p>
            </v-card-text>
          </v-card>
          <v-overlay :value="loading"
            ><v-progress-circular indeterminate></v-progress-circular
          ></v-overlay>
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
    events: { date: "", time: "", address: {} },
    formationChecked: false,
    serviceChecked: false,
    eventChecked: false,
    budget: "",
    budgetItems: [],
    budgetEdit: false,
    budgetSoma: 0,
    budgetOpen: false,
    budgetCancel: false,
    distance: "",
    tax: [],
    multipliedTaxValue: 0,
    loadingDatas: false,
    loadingTotalTax: true,
    loadingSomaBudget: false,
    loadingBudget: true,
    loading: false,
  }),
  methods: {
    ...mapActions([
      "setInscribeID",
      "setUserNow",
      "setIsBudget",
      "setIsEvent",
      "setBudget",
    ]),

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
      if (response.data) {
        this.events = response.data;
        this.distance = await this.getDistance();
      }
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
      var tax = this.tax;
      if (tax) {
        tax.forEach((t) => {
          if (t.multiplied == "por km") {
            this.multipliedTaxValue =
              parseFloat(t.value) * (parseFloat(this.distance) / 1000);
          }
        });
      }
      this.loadingTotalTax = false;
    },
    createVariantTax: async function () {
      let tax = this.tax;
      if (tax) {
        tax.forEach(async function (t) {
          let data = new FormData();
          data.append("value", this.distance);
          data.append("tax_idtax", t.idtax);
          data.append("inscribe_idinscribe", this.inscribeID);
          const response = await axios({
            method: "post",
            url: this.apiURL + "/inscribes/variantTax",
            data: data,
          });
          console.log(response);
        });
      }
    },

    createBudget: async function () {
      var data = new FormData();
      data.append("date", this.$moment().format("YYYY-MM-DD"));
      data.append("value", this.budgetSoma);
      data.append("discount", null);
      data.append("addition", null);
      data.append(
        "expires_in",
        this.$moment().add(15, "days").format("YYYY/MM/DD")
      );
      data.append("status", 0);
      data.append("inscribe_idinscribe", this.inscribeID);
      const response = await axios({
        method: "post",
        url: this.apiURL + "/budgets/create",
        data: data,
      });
      this.$swal(response.data.msg, "", response.data.icon);
      // await this.createVariantTax();
      await this.getBudget();
    },
    getBudget: async function () {
      this.loadingBudget = true;
      const response = await axios.get(
        this.apiURL + "/budgets/get/" + this.inscribeID
      );
      if (response.data) {
        this.budgetItems = response.data;
        this.setIsBudget(true);
        await this.verifyBudgetCancel();
        await this.verifyBudgetExpires();
      } else {
        this.setIsBudget(false);
      }
      this.budgetOpen =
        !this.access.isBudget || this.budgetCancel ? true : false;
      this.loadingBudget = false;
    },
    cancelBudget: async function (id) {
      const response = await axios.get(this.apiURL + "/budgets/cancel/" + id);
      this.$swal(response.data.msg, "", response.data.icon);
      await this.verifyBudgetCancel();
      await this.getBudget();
    },
    verifyBudgetCancel: async function () {
      const response = await axios.get(
        this.apiURL + "/budgets/verifyBudgetCancel/" + this.inscribeID
      );
      this.budgetCancel = response.data;
    },
    verifyBudgetExpires: async function () {
      this.loading = true;
      await axios.get(
        this.apiURL + "/budgets/verifyBudgetExpires/" + this.inscribeID
      );
      this.loading = false;
    },
    extendExpirationDate: async function (id) {
      this.loading = true;
      const data = new FormData();
      data.append(
        "new_date",
        this.$moment().add(15, "days").format("YYYY-MM-DD")
      );
      data.append("idbudget", id);
      const response = await axios(
        this.apiURL + "/budgets/extendExpirationDate/" + this.inscribeID,
        {
          method: "POST",
          data: data,
        }
      );
      this.loading = false;
      // this.$swal(response.data.msg, '', response.data.icon);
      await this.getBudget();
    },
  },
  created: async function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
      await this.setIsEvent();
      await this.getEvent();
      await this.getInscribe();
      await this.calculateTaxValue();
      await this.getBudget();
    }
  },
  computed: {
    ...mapGetters(["inscribeID", "userNow", "access"]),
    formationChecked: function () {
      this.formationChecked
        ? this.somaBudget(this.formation.price)
        : this.subtractBudget(this.formation.price);
    },
  },
  mounted() {
    // this.checkBudget();
  },
};
</script>
