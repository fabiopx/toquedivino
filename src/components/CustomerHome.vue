<template>
  <div>
    <v-container v-show="!isAgreement">
      <v-card>
        <v-toolbar color="grey darken-4" dark>
          <v-toolbar-title><h2>Seja bem-vindo!</h2></v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <p class="ml-5 subtitle-1">Confira abaixo os dados que reunimos até aqui.</p>
          <v-sheet v-show="!loadingData2" class="ml-4 mt-4 pa-3" outlined>
            <div class="d-flex justify-space-around mt-5">
              <v-img max-width="200" :src="require('../assets/undraw_setup_re_y9w8.svg')"></v-img>
              <v-sheet outlined rounded>
                <v-skeleton-loader
                  v-show="loadingData1"
                  type="sentences"
                ></v-skeleton-loader>
                <v-list v-show="!loadingData1">
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>
                        <b class="red--text darken-4">Formação:</b> {{ inscribe.formation.name }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>
                        <b class="red--text darken-4">Serviço:</b> {{ inscribe.service.name }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-sheet>
            </div>
            <v-divider class="mt-5 mb-3"></v-divider>
            <v-skeleton-loader
              v-show="loadingData2"
              type="article"
            ></v-skeleton-loader>
            <v-list>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>
                    <h2 class="mb-3">Dados pessoais</h2>
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    <p><b class="red--text darken-4">Nome:</b> {{ inscribe.accountable }}</p>
                    <p><b class="red--text darken-4">Telefone:</b> {{ inscribe.phone }}</p>
                    <p><b class="red--text darken-4">Celular:</b> {{ inscribe.mobile }}</p>
                    <p>
                      <b class="red--text darken-4">Endereço:</b> {{ inscribe.address.street }},
                      {{ inscribe.address.number }}
                      {{
                        inscribe.address.complement
                          ? ", " + inscribe.address.complement + ","
                          : ""
                      }}
                      {{ inscribe.address.neighborhood }},
                      {{ inscribe.address.city }} -
                      {{ inscribe.address.state }},
                      {{ inscribe.address.zipcode }},
                      {{ inscribe.address.country }}
                    </p>
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-sheet>
          <p class="ml-5 mt-3" v-show="!isEvent">
            O próximo passo é oferecer maiores informações sobre o evento e
            realizar o orçamento.
          </p>
          <p class="ml-5 mt-3" v-show="isEvent" v-if="!isBudget">
            Seu evento foi cadastrado como sucesso! Agora você pode realizar o
            orçamento Toque Divino.
          </p>
        </v-card-text>
        <v-skeleton-loader
          v-show="loadingActions"
          type="actions"
          class="ml-5"
        ></v-skeleton-loader>
        <v-card-actions v-show="!loadingActions">
          <v-btn
            v-show="isEvent && !isBudget"
            color="red darken-4"
            depressed
            dark
            class="ml-5 pa-8"
            @click="$router.push('/customer/budget')"
            ><v-icon class="mr-2">mdi-account-cash</v-icon> Realizar
            orçamento</v-btn
          >
          <v-btn
            v-show="!isEvent"
            color="red darken-4"
            depressed
            dark
            class="ml-5 pa-8"
            @click="$router.push('/customer/event')"
            ><v-icon class="mr-3">mdi-calendar-check</v-icon>Cadastrar
            evento</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    inscribe: { formation: "", service: "", address: "" },
    loadingData1: false,
    loadingData2: false,
    loadingActions: true,
    dialogEvents: false,
    eventEmpty: true,
    eventID: "",
    eventName: "",
    eventDate: "",
    eventDateCountDown: "",
    eventTime: "",
    eventAddress: {
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      zipcode: "",
      state: "",
      country: "",
    },
    pickDateEvent: false,
    pickTimeEvent: false,
    maskPhone: "(##) ####-####",
    maskMobile: "(##) #####-####",
    maskCpf: "###.###.###-##",
    maskCnpj: "##.###.###/####-##",
    maskMoney: "######.##",
    maskBirthdate: "##/##/####",
    maskCep: "#####-###",
    buscarEndereco: false,
    tooltipEndereco: false,
    isEvent: false,
  }),

  methods: {
    ...mapActions(["setInscribeID", "setUserNow", "setIsBudget"]),
    getInscribe: async function () {
      this.loadingData1 = true;
      this.loadingData2 = true;
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      this.inscribe = response.data;
      // console.log(this.inscribe.idinscribe)
      this.setInscribeID(this.inscribe.idinscribe);
      this.loadingData1 = false;
      this.loadingData2 = false;
    },
    verifyEvent: async function () {
      await this.getInscribe();
      const response = await axios.get(
        this.apiURL + "/events/getCustomers/" + this.inscribeID
      );
      const resp = response.data;
      if (resp) {
        this.isEvent = true;
        this.eventID = resp.idevent;
        this.eventName = resp.name;
        this.eventDate = resp.date;
        this.eventTime = resp.time;
        this.eventAddress = resp.address;
        this.eventEmpty = false;
      }
      // console.log(this.isEvent);
    },
    verifyBudget: async function () {
      const response = await axios.get(
        this.apiURL + "/budgets/isBudget/" + this.inscribeID
      );
      this.setIsBudget(response.data);
      this.loadingActions = false;
    },
    getAddressData: function (addressData, placeResultData, id) {
      this.eventAddress.street = addressData.route;
      this.eventAddress.number = addressData.street_number
        ? addressData.street_number
        : "";
      const address = placeResultData.formatted_address.split("-");
      const neighborhood = address[1].split(",");
      this.eventAddress.neighborhood = neighborhood[0];
      this.eventAddress.state = addressData.administrative_area_level_1;
      this.eventAddress.country = addressData.country;
      this.eventAddress.city = addressData.administrative_area_level_2;
      this.eventAddress.zipcode = addressData.postal_code
        ? addressData.postal_code
        : "";
      this.buscarEndereco = false;
    },
    openDialogEvents: function () {
      this.dialogEvents = true;
      this.eventName = this.inscribe.service.name;
    },
    closeDialogEvents: function () {
      this.dialogEvents = false;
    },
    saveEvent: function () {
      let data = new FormData();
      data.append("eventname", this.eventName);
      data.append("eventdate", this.eventDate);
      data.append("eventtime", this.eventTime);
      data.append("eventaddress", JSON.stringify(this.eventAddress));
      data.append("idinscribe", this.inscribeID);
      axios(this.apiURL + "/events/createCustomers", {
        method: "POST",
        data: data,
      }).then((response) => {
        this.$swal(response.data.msg, "", response.data.icon);
        this.closeDialogEvents();
        this.verifyEvent();
      });
    },
  },

  computed: {
    ...mapGetters(["inscribeID", "userNow", "isAgreement", "isBudget"]),
  },

  created: async function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
      await this.verifyEvent();
      await this.verifyBudget();
    }
  },
};
</script>
