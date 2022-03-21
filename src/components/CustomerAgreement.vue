<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark> Orçamento </v-toolbar>
            <v-card-text>
              <v-banner single-line>
                <h3>Formação</h3>
                <p class="font-italic">{{ formation.name }}</p>
                <p>{{ formation.description }}</p>
                <v-chip color="grey darken-4" dark
                  >R$ {{ formation.price }}</v-chip
                >
                <template v-slot:actions>
                  <v-btn icon @click="checkedFormation(), addFormation()">
                    <v-icon v-show="!formationChecked"
                      >mdi-checkbox-blank-circle-outline</v-icon
                    >
                    <v-icon v-show="formationChecked"
                      >mdi-checkbox-marked-circle</v-icon
                    >
                  </v-btn>
                  <v-btn icon>
                    <v-icon>mdi-circle-edit-outline</v-icon>
                  </v-btn>
                </template>
              </v-banner>
              <v-banner single-line>
                <h3>Serviço</h3>
                <p class="font-italic">{{ service.name }}</p>
                <p>{{ service.description }}</p>
                <!-- <div v-show="tax">
                  <h4>{{ tax.name }}</h4>
                  <p>{{ tax.description }}</p>
                  <v-chip>R$ {{ tax.value }} <span v-if="tax.type == '2'">{{ tax.multiplied }}</span></v-chip>
                </div> -->
                <template v-slot:actions>
                  <v-btn icon @click="checkedService()">
                    <v-icon v-show="!serviceChecked"
                      >mdi-checkbox-blank-circle-outline</v-icon
                    >
                    <v-icon v-show="serviceChecked"
                      >mdi-checkbox-marked-circle</v-icon
                    >
                  </v-btn>
                  <v-btn icon>
                    <v-icon>mdi-circle-edit-outline</v-icon>
                  </v-btn>
                </template>
              </v-banner>
              <v-banner single-line>
                <h3>Evento</h3>
                <p>Data: {{ events.date }}</p>
                <p>Horário: {{ events.time }}</p>
                <p>Endereço: 
                  {{ events.address.street }}, 
                  {{ events.address.number }} 
                  {{ (events.address.complement) ? ", " + events.address.complement : "" }}
                  {{ events.address.neigborhood }},
                  {{ events.address.city }} -
                  {{ events.address.state }},
                  {{ events.address.zipcode }}
                  </p>
                  <template v-slot:actions>
                  <v-btn icon @click="checkedEvent()">
                    <v-icon v-show="!eventChecked"
                      >mdi-checkbox-blank-circle-outline</v-icon
                    >
                    <v-icon v-show="eventChecked"
                      >mdi-checkbox-marked-circle</v-icon
                    >
                  </v-btn>
                  <v-btn icon>
                    <v-icon>mdi-circle-edit-outline</v-icon>
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
              <v-divider></v-divider>
              <v-btn color="grey darken-4" class="mt-3 pa-4" dark>
                <v-icon class="mr-2">mdi-content-save</v-icon>Salvar orçamento
              </v-btn>
            </v-card-text>
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
    events:{ address: {}},
    formationChecked: false,
    serviceChecked: false,
    eventChecked: false,
    budgetItems: [],
    budgetSoma: 0,
    distance: '',
    tax:'',
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
    checkedFormation: function () {
      this.formationChecked = !this.formationChecked;
    },
    checkedService: function () {
      this.serviceChecked = !this.serviceChecked;
    },
    checkedEvent: function(){
      this.eventChecked = !this.eventChecked;
    },
    getInscribe: async function () {
      const response = await axios.get(
        this.apiURL + "/api/getInscribeCustomers/" + this.userNow.id
      );
      this.formation = response.data.formation;
      this.service = response.data.service;
      this.getServiceTax();
    },
    getEvent: async function(){
      const response = await axios.get(this.apiURL + "/api/getEventCustomers/" + this.inscribeID);
      this.events = response.data;
      this.getDistance();
    },
    getServiceTax: async function(){
      const response = await axios.get(this.apiURL + "/api/getServices/" + this.service.idservice);
      console.log(response.data.taxas)
      this.tax = response.data.taxas
    },
    getDistance() {
      var service = new google.maps.DistanceMatrixService();
      service.getDistanceMatrix({
        origins: ['Americana-SP'],
        destinations: [this.events.address.state],
        travelMode: 'DRIVING',
      }).then((response) => {
        console.log(response.rows[0].elements[0].distance.value)
      })
    },
  },
  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
      this.getInscribe();
      this.getEvent();
    }
  },
  computed: {
    ...mapGetters(["inscribeID", "userNow", "isAgreement"]),
  },
  isBudget: async function(){
    const response = await axios.get(this.apiURL + "/api/isBudget/")
  }
};
</script>