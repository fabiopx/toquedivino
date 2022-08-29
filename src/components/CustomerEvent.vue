<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>
                <span class="small"
                  ><v-icon class="mr-3">mdi-calendar-check</v-icon>Evento</span
                >
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>

            <v-card-text>
              <v-skeleton-loader
                v-if="loadingEventFields"
                type="text@4"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingEventFields" ref="formEvents">
                <v-container>
                  <v-row>
                    <v-col cols="12" md="6" lg="6">
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
                            :rules="eventDateRule"
                          >
                          </v-text-field>
                        </template>
                        <v-date-picker
                          v-model="eventDate"
                          @input="pickDateEvent = false"
                          locale="pt-br"
                        >
                        </v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col cols="12" md="6" lg="6">
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
                            :rules="eventTimeRule"
                          >
                          </v-text-field>
                        </template>
                        <v-time-picker
                          v-if="pickTimeEvent"
                          v-model="eventTime"
                          format="24hr"
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
                        color="red darken-4"
                        dark
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
                          >Digite o nome da rua (avenida, alameda, etc) e o
                          número da residência para preencher automaticamente o
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
                        types="establishment"
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
                        v-mask="maskCep" :rules="eventAddressRule"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="8" lg="6">
                      <v-text-field
                        label="Logradouro"
                        v-model="eventAddress.street" :rules="eventAddressRule"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Número"
                        v-model="eventAddress.number" :rules="eventAddressRule"
                      >
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
                        v-model="eventAddress.neighborhood" :rules="eventAddressRule"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Cidade"
                        v-model="eventAddress.city" :rules="eventAddressRule"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field label="Estado" v-model="eventAddress.state" :rules="eventAddressRule">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field label="País" v-model="eventAddress.country" :rules="eventAddressRule">
                      </v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-form>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="red darken-4"
                class="pa-8"
                @click="saveEvent()"
              >
                <v-icon class="mr-3">mdi-content-save</v-icon> Salvar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

function toDateFormat(input) {
  var datePart = input.match(/\d+/g),
    year = datePart[0], // get only two digits
    month = datePart[1],
    day = datePart[2];

  return day + "/" + month + "/" + year;
}

function FormataStringData(data) {
  var dia = data.split("/")[0];
  var mes = data.split("/")[1];
  var ano = data.split("/")[2];

  return ano + "-" + ("0" + mes).slice(-2) + "-" + ("0" + dia).slice(-2);
}

function getAge(date) {
  var today = new Date();
  var birthdate = new Date(convertToMMDDYYYY(date.split("/")));
  var year = today.getFullYear() - birthdate.getFullYear();
  var month = today.getMonth() - birthdate.getMonth();
  if (month < 0 || (month === 0 && today.getDate() < birthdate.getDate())) {
    year--;
  }
  return year;
}

function convertToMMDDYYYY(date) {
  return date[1] + "-" + date[0] + "-" + date[2];
}

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    crud: "c",
    inputLoading: false,
    showPassword: false,
    alert: false,
    alertMsg: "",
    maskPhone: "(##) ####-####",
    maskMobile: "(##) #####-####",
    maskCpf: "###.###.###-##",
    maskCnpj: "##.###.###/####-##",
    maskMoney: "######.##",
    maskBirthdate: "##/##/####",
    maskCep: "#####-###",
    buscarEndereco: false,
    tooltipEndereco: false,
    services: [],
    formation: [],
    loadingInscribeFields: false,
    inscribeID: "",
    inscribeAccountable: "",
    inscribePhone: "",
    inscribeMobile: "",
    inscribeAddress: {
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      zipcode: "",
      state: "",
      country: "",
    },
    inscribeCpf: "",
    inscribeRg: "",
    inscribeStatus: "",
    inscribeService: "",
    inscribeFormation: "",
    inscribeServiceTax: "",
    inscribeAgree: false,
    loadingEventFields: false,
    eventEmpty: true,
    eventID: "",
    eventName: "",
    eventDate: "",
    eventDateRule: [(v) => !!v || "O campo Data é requerido"],
    eventDateCountDown: "",
    eventTime: "",
    eventTimeRule: [(v) => !!v || "O campo Horário é requerido"],
    countDownNow: new Date(),
    countDownTime: undefined,
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
    eventAddressRule: [(v) => !!v || "Digite o endereço completo"],
    pickDateEvent: false,
    pickTimeEvent: false,
  }),

  mounted(){
    // this.$refs.address.focus();
  },

  methods: {
    ...mapActions(["setUserNow", "setIsEvent"]),

    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },
    is18: function (date) {
      if (getAge(date) < 18) {
        this.$swal("Responsável precisa ter mais que 18 anos");
      }
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
    getCEP: function (obj) {
      var cep = this[obj].zipcode.replace(/\D/g, "");

      this.inputLoading = true;

      this[obj].street = "Carregando...";
      this[obj].neighborhood = "Carregando...";
      this[obj].city = "Carregando...";
      this[obj].state = "Carregando...";
      this[obj].country = "Carregando...";

      if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if (validacep.test(cep)) {
          axios
            .get("https://viacep.com.br/ws/" + cep + "/json/")
            .then((response) => {
              this.inputLoading = false;
              this[obj].street = response.data.logradouro;
              this[obj].neighborhood = response.data.bairro;
              this[obj].city = response.data.localidade;
              this[obj].state = response.data.uf;
              this[obj].country = "Brasil";
            })
            .catch((err) => {
              this.inputLoading = false;
              this.$swal(err.message, "", "error");
            });
        } else {
          this.inputLoading = false;
          this.$swal("CEP inválido", "", "error");
          this[obj].zipcode = "";
        }
      } else {
        this.$swal("Por favor digite CEP!", "", "error");
      }
    },
    getInscribe: async function () {
      this.loadingInscribeFields = true;
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      const resp = response.data;
      if (resp) {
        this.inscribeID = resp.idinscribe;
        this.inscribeAccountable = resp.accountable;
        this.inscribePhone = resp.phone;
        this.inscribeMobile = resp.mobile;
        this.inscribeAddress = resp.address;
        this.inscribeCpf = resp.cpf;
        this.inscribeRg = resp.rg;
        this.inscribeStatus = resp.status;
        this.inscribeService = resp.service;
        this.inscribeFormation = resp.formation;
        this.eventName = resp.service.name;
      }
      this.loadingInscribeFields = false;
      this.loadingAgreementValues = false;
    },
    getEvent: async function () {
      this.loadingEventFields = true;
      this.loadingAgreementEvent = true;
      const response = await axios.get(
        this.apiURL + "/events/getCustomers/" + this.inscribeID
      );
      const resp = response.data;
      if (resp) {
        this.eventID = resp.idevent;
        this.eventName = resp.name;
        this.eventDate = resp.date;
        this.eventDateCountDown = new Date(resp.date);
        this.eventTime = resp.time;
        this.eventAddress = resp.address;
        this.crud = "u";
        this.eventEmpty = false;
        this.countDownTime = this.eventDateCountDown - this.countDownNow;
      } else {
        this.crud = "c";
      }
      this.loadingEventFields = false;
      this.loadingAgreementEvent = false;
    },
    saveEvent: function () {
      if (this.$refs.formEvents.validate()) {
        let data = new FormData();
        data.append("eventname", this.eventName);
        data.append("eventdate", this.eventDate);
        data.append("eventtime", this.eventTime);
        data.append("eventaddress", JSON.stringify(this.eventAddress));
        data.append("idinscribe", this.inscribeID);
        if (this.crud == "c") {
          axios(this.apiURL + "/events/createCustomers", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.setIsEvent();
            this.getEvent();
            this.$router.push('/customer/home');
          });
        } else if (this.crud == "u") {
          axios(this.apiURL + "/events/updateCustomers/" + this.eventID, {
            method: "POST",
            data: data,
          }).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getEvent();
          });
        }
      }
    },
  },

  computed: {
    ...mapGetters(["userNow", "isAgreement", "access"]),
  },

  created: async function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
      await this.setIsEvent();
    }
    await this.getInscribe();
    await this.getEvent();
    console.log(this.access.isEvent);
  },
};
</script>
