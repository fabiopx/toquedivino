<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark> Orçamento </v-toolbar>
            <v-card-text>
              <v-sheet class="pa-4" outlined elevation="1">
                <v-banner single-line>
                  <h3>Formação</h3>
                  <p class="font-italic">{{ formation.name }}</p>
                  <p>{{ formation.description }}</p>
                  <v-chip color="grey darken-4" dark>{{
                    parseFloat(formation.price).toLocaleString("pt-BR", {
                      style: "currency",
                      currency: "BRL",
                    })
                  }}</v-chip>
                  <template v-slot:actions>
                    <v-btn
                      v-show="!isBudget"
                      depressed
                      dark
                      color="grey darken-4"
                      @click="checkedFormation(), addFormation()"
                    >
                      <v-icon v-show="!formationChecked"
                        >mdi-checkbox-blank-circle-outline</v-icon
                      >
                      <v-icon v-show="formationChecked"
                        >mdi-checkbox-marked-circle</v-icon
                      >
                      <span class="ml-2">Confirmar</span>
                    </v-btn>
                  </template>
                </v-banner>
                <v-banner single-line>
                  <h3>Serviço</h3>
                  <p class="font-italic">{{ service.name }}</p>
                  <p>{{ service.description }}</p>
                  <div v-show="tax" v-for="t in tax" :key="t.idtax">
                    <h4>Taxa: {{ t.name }}</h4>
                    <p>{{ t.description }}</p>
                    <p>
                      Origem: Americana-SP<br />
                      Destino:
                      {{ events.address.city + "-" + events.address.state
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
                      <span v-if="t.type == '2'" class="ml-2">{{
                        t.multiplied
                      }}</span>
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
                    class="mt-2"
                    >Total:
                    {{
                      parseFloat(
                        multipliedTaxValue.toFixed("2")
                      ).toLocaleString("pt-BR", {
                        style: "currency",
                        currency: "BRL",
                      })
                    }}</v-chip
                  >
                  <template v-slot:actions>
                    <v-btn
                      v-show="!isBudget"
                      depressed
                      dark
                      color="grey darken-4"
                      @click="checkedService(), addTax()"
                    >
                      <v-icon v-show="!serviceChecked"
                        >mdi-checkbox-blank-circle-outline</v-icon
                      >
                      <v-icon v-show="serviceChecked"
                        >mdi-checkbox-marked-circle</v-icon
                      >
                      <span class="ml-2">Confirmar</span>
                    </v-btn>
                  </template>
                </v-banner>
                <v-banner single-line>
                  <h3>Evento</h3>
                  <p>Data: {{ events.date }}</p>
                  <p>Horário: {{ events.time }}</p>
                  <p>
                    Endereço: {{ events.address.street }},
                    {{ events.address.number }}
                    {{
                      events.address.complement
                        ? ", " + events.address.complement
                        : ""
                    }}
                    {{ events.address.neigborhood }},
                    {{ events.address.city }} - {{ events.address.state }},
                    {{ events.address.zipcode }}
                  </p>
                  <template v-slot:actions>
                    <v-btn
                      v-show="!isBudget"
                      depressed
                      dark
                      color="grey darken-4"
                      @click="checkedEvent()"
                    >
                      <v-icon v-show="!eventChecked"
                        >mdi-checkbox-blank-circle-outline</v-icon
                      >
                      <v-icon v-show="eventChecked"
                        >mdi-checkbox-marked-circle</v-icon
                      >
                      <span class="ml-2">Confirmar</span>
                    </v-btn>
                  </template>
                </v-banner>
                <v-banner>
                  <b class="text-uppercase"
                    >Total:
                    {{
                      budgetSoma.toLocaleString("pt-BR", {
                        style: "currency",
                        currency: "BRL",
                      })
                    }}</b
                  >
                </v-banner>
              </v-sheet>
            </v-card-text>
            <v-card-actions>
              <div class="ml-3">
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

function adicionaZero(numero) {
  if (numero <= 9) return "0" + numero;
  else return numero;
}

function dateNow() {
  var date = new Date();
  return (
    date.getFullYear() +
    "-" +
    adicionaZero(date.getMonth() + 1).toString() +
    "-" +
    adicionaZero(date.getDate().toString())
  );
}

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    formation: {},
    service: {},
    events: { address: {} },
    formationChecked: false,
    serviceChecked: false,
    eventChecked: false,
    budgetItems: [],
    budgetSoma: 0,
    isBudget: false,
    distance: "",
    tax: [],
    multipliedTaxValue: 0,
    loadingTotalTax: true,
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
    checkedFormation: function () {
      this.formationChecked = !this.formationChecked;
    },
    checkedService: function () {
      this.serviceChecked = !this.serviceChecked;
    },
    checkedEvent: function () {
      this.eventChecked = !this.eventChecked;
    },
    verifyAllChecked: function () {
      if (this.formationChecked && this.serviceChecked && this.eventChecked) {
        this.createBudget();
      } else {
        this.$swal("É preciso confirmar todas as informações", "", "error");
      }
    },
    getInscribe: async function () {
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      this.formation = response.data.formation;
      this.service = response.data.service;
      await this.getServiceTax();
    },
    getEvent: async function () {
      const response = await axios.get(
        this.apiURL + "/events/getCustomers/" + this.inscribeID
      );
      this.events = response.data;
      this.distance = await this.getDistance();
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
            console.log(response.rows[0].elements[0].distance.value * 2);
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
      console.log(response.data);
    },
    saveBudget: async function () {
      var data = new FormData();
      data.append("date", dateNow());
      data.append("value", this.budgetSoma);
      data.append("discount", null);
      data.append("addition", null);
      data.append("expires_in", null);
      data.append("status", 0);
      data.append("inscribe_idinscribe", this.inscribeID);
      const response = await axios({
        method: "post",
        url: this.apiURL + "/budgets/create",
        data: data,
      });
      this.$swal(response.data.msg, "", response.data.icon);
    },
    getBudget: function () {
      axios.get(this.apiURL + "/budgets/get");
    },
  },
  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
      this.getEvent();
      this.getInscribe();
      this.calculateTaxValue();
      this.verifyBudget();
    }
  },
  computed: {
    ...mapGetters(["inscribeID", "userNow", "isAgreement"]),
  },
};
</script>
