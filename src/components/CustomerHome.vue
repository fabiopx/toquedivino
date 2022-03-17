<template>
  <div>
    <v-container v-show="!isAgreement">
      <v-card>
        <v-toolbar color="grey darken-4" dark>
          <h2>Seja bem-vindo!</h2>
        </v-toolbar>
        <v-card-text>
          <p class="ml-5">Confira abaixo os dados que reunimos até aqui.</p>
          <v-sheet v-show="!loadingData2" class="ml-4 mt-4" outlined>
            <v-skeleton-loader
              v-show="loadingData1"
              type="sentences"
            ></v-skeleton-loader>
            <v-list v-show="!loadingData1">
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>
                    <b>Formação:</b> {{ inscribe.formation.name }}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>
                    <b>Serviço:</b> {{ inscribe.service.name }}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
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
                    <p><b>Nome:</b> {{ inscribe.accountable }}</p>
                    <p><b>Telefone:</b> {{ inscribe.phone }}</p>
                    <p><b>Celular:</b> {{ inscribe.mobile }}</p>
                    <p>
                      <b>Endereço:</b> {{ inscribe.address.street }},
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
          <p class="ml-5 mt-3" v-show="isEvent">
            Seu evento foi cadastrado como sucesso! Agora você pode realizar o
            orçamento Toque Divino.
          </p>
        </v-card-text>
        <v-card-actions>
          <v-skeleton-loader
            v-show="loadingActions"
            type="button"
            class="ml-5"
          ></v-skeleton-loader>
          <v-btn
            v-show="!loadingActions"
            v-if="isEvent"
            color="red darken-4"
            depressed
            dark
            class="ml-5 pa-8"
            @click="$router.push('/customer/agreement')"
            ><v-icon class="mr-2">mdi-account-cash</v-icon> Realizar<br />
            orçamento</v-btn
          >
          <v-btn
            v-show="!loadingActions"
            v-else
            color="red darken-4"
            depressed
            dark
            class="ml-5 pa-8"
            @click="openDialogEvents"
            >Cadastrar<br />evento</v-btn
          >
        </v-card-actions>
      </v-card>
      <v-dialog
        v-model="dialogEvents"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
      >
        <v-card>
          <v-toolbar color="grey darken-4" dark>
            <v-btn @click="closeDialogEvents" icon dark
              ><v-icon>mdi-close</v-icon></v-btn
            >
            <v-toolbar-title>Cadastrar evento</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn color="red darken-4" depressed @click="saveEvent()"
                ><v-icon class="mr-2">mdi-content-save</v-icon>Salvar</v-btn
              >
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
            <v-form ref="formEvents">
              <v-container>
                <v-row>
                  <v-col cols="12" lg="4">
                    <v-text-field
                      v-model="eventName"
                      label="Evento"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6" lg="4">
                    <v-menu
                      v-model="pickDateEvent"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="eventDate"
                          label="Data do evento"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        >
                        </v-text-field>
                      </template>
                      <v-date-picker
                        v-model="eventDate"
                        @input="pickDateEvent = false"
                      >
                      </v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col cols="12" md="6" lg="4">
                    <v-menu
                      ref="menu"
                      v-model="pickTimeEvent"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="eventTime"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="eventTime"
                          label="Horário oficial do Evento"
                          prepend-icon="mdi-calendar-clock"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        >
                        </v-text-field>
                      </template>
                      <v-time-picker
                        v-if="pickTimeEvent"
                        v-model="eventTime"
                        full-width
                        @click:minute="$refs.menu.save(eventTime)"
                      >
                      </v-time-picker>
                    </v-menu>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-btn
                      @click="buscarEndereco = !buscarEndereco"
                      depressed
                      color="primary"
                    >
                      <v-icon>mdi-magnify</v-icon> Buscar endereço
                    </v-btn>
                    <v-tooltip v-model="tooltipEndereco" class="ml-3" bottom>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          v-show="!buscarEndereco"
                          @click="tooltipEndereco = !tooltipEndereco"
                          icon
                        >
                          <v-icon
                            dark
                            color="grey lighten-1"
                            v-bind="attrs"
                            v-on="on"
                            >mdi-help-circle</v-icon
                          >
                        </v-btn>
                      </template>
                      <span
                        >Digite o nome da rua (avenida, alameda, etc) e o número
                        da residência para preencher automaticamente o
                        formulário</span
                      >
                    </v-tooltip>
                    <vuetify-google-autocomplete
                      v-show="buscarEndereco"
                      outlined
                      class="mt-2"
                      ref="address"
                      id="map"
                      placeholder="Digite o endereço"
                      v-on:placechanged="getAddressData"
                      country="br"
                    ></vuetify-google-autocomplete>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="4" lg="2">
                    <v-text-field
                      label="CEP"
                      v-model="eventAddress.zipcode"
                      v-mask="maskCep"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="8" lg="6">
                    <v-text-field
                      label="Logradouro"
                      v-model="eventAddress.street"
                    >
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" md="6" lg="2">
                    <v-text-field label="Número" v-model="eventAddress.number">
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" md="6" lg="2">
                    <v-text-field
                      label="Complemento"
                      v-model="eventAddress.complement"
                    >
                    </v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field
                      label="Bairro"
                      v-model="eventAddress.neighborhood"
                    >
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      label="Cidade"
                      v-model="eventAddress.city"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field label="Estado" v-model="eventAddress.state">
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field label="País" v-model="eventAddress.country">
                    </v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data: () => ({
    inscribe: { formation: "", service: "", address: "" },
    loadingData1: false,
    loadingData2: false,
    loadingActions: true,
    isEvent: false,
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
  }),

  methods: {
    ...mapActions(["setInscribeID", "setUserNow"]),
    getInscribe: function () {
      this.loadingData1 = true;
      this.loadingData2 = true;
      axios
        .get(
          process.env.VUE_APP_URL + "getInscribeCustomers/" + this.userNow.id
        )
        .then((response) => {
          this.inscribe = response.data;
          this.loadingData1 = false;
          this.loadingData2 = false;
        });
    },
    verifyEvent: function () {
      axios
        .get(process.env.VUE_APP_URL + "getEventCustomers/" + this.inscribeID)
        .then((response) => {
          const resp = response.data;
          if (resp) {
            this.isEvent = true;
            this.eventID = resp.idevent;
            this.eventName = resp.name;
            this.eventDate = resp.date;
            this.eventTime = resp.time;
            this.eventAddress = resp.address;
            this.eventEmpty = false;
            this.loadingActions = false;
          } else {
            this.isEvent = false;
            this.loadingActions = false;
          }
        });
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
      axios(process.env.VUE_APP_URL + "createEventCustomers", {
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
    ...mapGetters(["inscribeID", "userNow", "isAgreement"]),
  },

  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    this.getInscribe();
    this.verifyEvent();
  },
};
</script>