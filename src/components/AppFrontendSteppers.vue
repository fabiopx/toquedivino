<template>
  <v-card>
    <v-card-text>
      <v-stepper v-model="tela">
        <v-stepper-header>
          <v-stepper-step :complete="tela > 1" step="1"></v-stepper-step>
          <v-divider></v-divider>
          <v-stepper-step :complete="tela > 2" step="2"></v-stepper-step>
          <v-divider></v-divider>
          <v-stepper-step :complete="tela > 3" step="3"></v-stepper-step>
          <v-divider></v-divider>
          <v-stepper-step :complete="tela > 4" step="4"></v-stepper-step>
          <v-divider></v-divider>
          <v-stepper-step :complete="tela == 5" step="5"></v-stepper-step>
          <!-- <v-divider></v-divider>
          <v-stepper-step :complete="tela == 6" step="6"></v-stepper-step> -->
        </v-stepper-header>
        <v-stepper-items>
          <v-stepper-content step="1">
            <v-row>
              <v-col cols="12" md="5" lg="6">
                <v-img
                  :src="require('../assets/undraw_my_answer_re_k4dv.svg')"
                  max-width="500"
                ></v-img>
              </v-col>
              <v-col cols="12" md="7" lg="6">
                <v-form ref="firstScreen">
                  <v-select
                    v-model="selectedService"
                    outlined
                    class="mt-3"
                    label="Selecione o evento"
                    :items="services"
                    item-text="name"
                    :rules="serviceRules"
                    return-object
                    v-on:change="onChangeService()"
                  >
                  </v-select>
                </v-form>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-btn
                  depressed
                  class="d-block mr-0 ml-auto"
                  color="primary"
                  @click="nextScreen()"
                >
                  Continuar <v-icon>mdi-menu-right</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-stepper-content>

          <v-stepper-content step="2">
            <v-row>
              <v-col>
                <p class="text-h6 blue--text darken-4">
                  Quais instrumentos você gostaria que tocasse no seu evento?
                </p>
                <v-alert type="error" v-show="alertSelectedInstruments"
                  >Selecione ao menos um instrumento
                </v-alert>
                <v-item-group v-model="selectedInstruments" multiple>
                  <v-container>
                    <v-row>
                      <v-col
                        cols="6"
                        md="4"
                        lg="3"
                        v-for="instrument in instruments"
                        :key="instrument.idinstrument"
                      >
                        <v-item
                          v-slot="{ active, toggle }"
                          :value="instrument.idinstrument"
                        >
                          <v-card
                            outlined
                            :color="active ? 'blue-grey' : ''"
                            class="d-flex align-center pa-3"
                            width="130"
                            height="130"
                            @click="toggle"
                          >
                            <v-scroll-x-transition>
                              <div
                                v-show="!active"
                                class="text-h2 flex-grow-1 text-center"
                              >
                                <v-img
                                  :src="
                                    require('../assets/instrument/' +
                                      instrument.image)
                                  "
                                  width="40"
                                  class="ma-2"
                                >
                                </v-img>
                                <p class="grey--text text-left text-body-1">
                                  {{ instrument.name }}
                                </p>
                              </div>
                            </v-scroll-x-transition>
                            <v-scroll-x-transition>
                              <div
                                v-if="active"
                                class="text-h2 flex-grow-1 text-center"
                              >
                                <v-img
                                  :src="
                                    require('../assets/instrument/' +
                                      instrument.image)
                                  "
                                  width="40"
                                  class="ma-2"
                                >
                                </v-img>
                                <p class="white--text text-left text-body-1">
                                  {{ instrument.name }}
                                </p>
                              </div>
                            </v-scroll-x-transition>
                          </v-card>
                        </v-item>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-item-group>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-btn
                  depressed
                  class="float-left"
                  color="primary"
                  @click="prevScreen()"
                >
                  <v-icon>mdi-menu-left</v-icon>Voltar
                </v-btn>
                <v-btn
                  depressed
                  class="float-right"
                  color="primary"
                  @click="nextScreen()"
                >
                  Continuar <v-icon>mdi-menu-right</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-stepper-content>

          <v-stepper-content step="3">
            <v-row>
              <v-col>
                <p class="text-h6 blue--text darken-4">
                  Estas são as <b>Formações</b> que contém o(s) instrumento(s)
                  que você selecionou. Escolha uma para prosseguirmos:
                </p>
                <v-alert type="error" v-show="alertSelectedFormation"
                  >Selecione uma formação
                </v-alert>
                <v-skeleton-loader
                  v-show="loadingFormations"
                  type="list-item-three-line"
                  class="mx-auto"
                ></v-skeleton-loader>
                <v-container v-show="!loadingFormations">
                  <v-row>
                    <v-col
                      cols="12"
                      md="6"
                      lg="3"
                      v-for="formation in formations"
                      :key="formation.idformation"
                    >
                      <v-radio-group v-model="selectedFormation">
                        <v-card
                          color="primary"
                          class="white--text"
                          max-width="300"
                        >
                          <v-card-title>{{ formation.name }}</v-card-title>
                          <v-card-text class="white--text">{{
                            formation.description
                          }}</v-card-text>
                          <v-divider class="white"></v-divider>
                          <v-card-actions class="grey">
                            <v-btn
                              icon
                              color="white"
                              class="mt-2 ml-2"
                              title="Assista um vídeo desta formação"
                              @click="openDialogFormationVideo(formation)"
                            >
                              <v-icon>mdi-youtube</v-icon>
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-radio
                              color="white"
                              label="selecionar"
                              dark
                              :value="formation"
                              @click="isSelected('selectedFormation', 'alertSelectedFormation')"
                            ></v-radio>
                          </v-card-actions>
                        </v-card>
                      </v-radio-group>
                    </v-col>
                  </v-row>
                  <v-dialog
                    v-model="dialogVideoFormation"
                    max-width="600"
                    persistent
                  >
                    <v-card>
                      <v-card>
                        <v-toolbar color="primary" dark>
                          {{ formation.name }}
                        </v-toolbar>
                        <v-card-text>
                          <div style="--aspect-ratio: 16/9;">
                            <iframe
                              width="560"
                              height="315"
                              :src="formation.video"
                              title="YouTube video player"
                              frameborder="0"
                              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                              allowfullscreen
                            ></iframe>
                          </div>
                        </v-card-text>
                        <v-card-actions class="justify-end">
                          <v-btn text @click="closeDialogFormationVideo()">
                            Close
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-card>
                  </v-dialog>
                  <v-dialog
                    v-model="dialogTooltipFormation"
                    max-width="600"
                    persistent
                  >
                    <v-card>
                      <v-card>
                        <v-toolbar color="primary" dark>
                          {{ formation.name }}
                        </v-toolbar>
                        <v-card-text>
                          {{ formation.description }}
                        </v-card-text>
                        <v-card-actions class="justify-end">
                          <v-btn text @click="closeDialogFormationTooltip()">
                            Close
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-card>
                  </v-dialog>
                </v-container>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-btn
                  depressed
                  class="float-left"
                  color="primary"
                  @click="prevScreen()"
                >
                  <v-icon>mdi-menu-left</v-icon> Voltar
                </v-btn>
                <v-btn
                  depressed
                  class="float-right"
                  color="primary"
                  @click="nextScreen()"
                >
                  Continuar
                  <v-icon>mdi-menu-right</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-stepper-content>

          <v-stepper-content step="4">
            <v-row>
              <v-col>
                <v-sheet elevation="0" outlined rounded class="pa-5">
                  <p class="text-h5 blue--text">Dados reunidos até aqui</p>
                  <p>
                    <b class="blue--text">Evento:</b> {{ selectedService.name }}
                  </p>
                  <p>
                    <b class="blue--text">Formação:</b>
                    {{ selectedFormation.name }}
                  </p>
                </v-sheet>
                <p class="text-h5 blue--text">
                  Quem será o responsável pelo cadastro?
                </p>
                <v-form ref="formInscribePartOne">
                  <v-text-field
                    v-model="inscribeAccountable"
                    label="Nome"
                    :rules="inscribeAccountableRules"
                  >
                  </v-text-field>
                  <v-text-field
                    v-model="inscribeBirthdate"
                    label="Data de nascimento"
                    v-mask="maskBirthdate"
                    :rules="inscribeBirthdateRules"
                    @change="is18(inscribeBirthdate)"
                  ></v-text-field>
                  <v-text-field
                    v-model="inscribeEmail"
                    label="E-mail"
                    :rules="inscribeEmailRules"
                    @change="verifyEmail()"
                  >
                  </v-text-field>
                  <v-text-field
                    v-model="inscribeMobile"
                    label="Celular"
                    :rules="inscribeMobileRules"
                    v-mask="maskTel(inscribeMobile)"
                    @change="saveLead()"
                  >
                  </v-text-field>
                </v-form>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-btn
                  depressed
                  class="float-left"
                  color="primary"
                  @click="prevScreen()"
                >
                  <v-icon>mdi-menu-left</v-icon> Voltar
                </v-btn>
                <v-btn
                  depressed
                  class="float-right"
                  color="primary"
                  @click="finish()"
                >
                  <v-icon>mdi-menu-right</v-icon> Finalizar
                </v-btn>
              </v-col>
            </v-row>
          </v-stepper-content>
          <!-- <v-stepper-content step="5">
            <v-row>
              <v-col>
                <v-sheet elevation="0" outlined rounded class="pa-5">
                  <p class="text-h6 blue--text">Dados reunidos até aqui</p>
                  <p>
                    <b class="blue--text">Evento:</b> {{ selectedService.name }}
                  </p>
                  <p>
                    <b class="blue--text">Formação:</b>
                    {{ selectedFormation.name }}
                  </p>
                  <p>
                    <b class="blue--text">Responsável:</b>
                    {{ inscribeAccountable }}
                    <b class="blue--text ml-3">E-mail:</b> {{ inscribeEmail }}
                    <b class="blue--text ml-3">Telefone:</b>
                    {{ inscribePhone }} <b class="blue--text ml-3">Celular:</b>
                    {{ inscribeMobile }}
                  </p>
                </v-sheet>
                <p class="text-h6 blue--text mt-3">Dados pessoais: endereço</p>
                <v-row>
                  <v-col cols="12">
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
                <v-form ref="formInscribePartTwo">
                  <v-row>
                    <v-col cols="12" lg="10">
                      <v-text-field
                        v-model="inscribeAddress.street"
                        label="Logradouro"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" lg="2">
                      <v-text-field
                        v-model="inscribeAddress.zipcode"
                        label="CEP"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" lg="4">
                      <v-text-field
                        v-model="inscribeAddress.number"
                        label="Número"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" lg="8">
                      <v-text-field
                        v-model="inscribeAddress.complement"
                        label="Complemento"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" lg="6">
                      <v-text-field
                        v-model="inscribeAddress.neighborhood"
                        label="Bairro"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" lg="6">
                      <v-text-field
                        v-model="inscribeAddress.city"
                        label="Cidade"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" lg="6">
                      <v-text-field
                        v-model="inscribeAddress.state"
                        label="Estado"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" lg="6">
                      <v-text-field
                        v-model="inscribeAddress.country"
                        label="País"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                </v-form>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-btn
                  depressed
                  class="float-left"
                  color="primary"
                  @click="prevScreen()"
                >
                  <v-icon>mdi-menu-left</v-icon> Voltar
                </v-btn>
                <v-btn
                  depressed
                  class="float-right"
                  color="primary"
                  @click="finish()"
                >
                  Finalizar <v-icon>mdi-menu-right</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-stepper-content> -->

          <v-stepper-content step="5">
            <v-row>
              <v-col>
                <p>
                  Que legal! Agora temos as informações necessárias para montar
                  seu orçamento.
                </p>
                <p>
                  Para continuar, acesse a
                  <router-link to="customer">área restrita</router-link>. As
                  informações de acesso foi enviada para seu e-mail.
                </p>
                <p></p>
              </v-col>
            </v-row>
          </v-stepper-content>
        </v-stepper-items>
        <v-overlay v-show="loading">
          <v-progress-circular indeterminate></v-progress-circular>
        </v-overlay>
      </v-stepper>
    </v-card-text>
    <v-card-actions>
      <v-btn
        color="amber ligthen-1 ml-2"
        depressed
        x-large
        dark
        @click="restartApp()"
      >
        <v-icon>mdi-restart</v-icon> Recomeçar
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

function twoDigits(d) {
  if (0 <= d && d < 10) return "0" + d.toString();
  if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
  return d.toString();
}

function toDateFormat(input) {
  var datePart = input.match(/\d+/g),
    year = datePart[0].substring(2), // get only two digits
    month = datePart[1],
    day = datePart[2];

  return day + "/" + month + "/" + year;
}

Date.prototype.toMysqlFormat = function () {
  return (
    this.getFullYear() +
    "-" +
    twoDigits(1 + this.getMonth()) +
    "-" +
    twoDigits(this.getDate()) +
    " " +
    twoDigits(this.getHours()) +
    ":" +
    twoDigits(this.getMinutes()) +
    ":" +
    twoDigits(this.getSeconds())
  );
};

function getAge(dataNasc) {
  var dataAtual = new Date();

  var anoAtual = dataAtual.getFullYear();

  var anoNascParts = dataNasc.split("/");

  var diaNasc = anoNascParts[0];

  var mesNasc = anoNascParts[1];

  var anoNasc = anoNascParts[2];

  var idade = anoAtual - anoNasc;

  var mesAtual = dataAtual.getMonth() + 1;

  //Se mes atual for menor que o nascimento, nao fez aniversario ainda;

  if (mesAtual < mesNasc) {
    idade--;
  } else {
    //Se estiver no mes do nascimento, verificar o dia

    if (mesAtual == mesNasc) {
      if (new Date().getDate() < diaNasc) {
        //Se a data atual for menor que o dia de nascimento ele ainda nao fez aniversario

        idade--;
      }
    }
  }

  return idade;
}

export default {
  name: "AppFrontendSteppers",

  data: () => ({
    urlApi: process.env.VUE_APP_URL,
    active: false,
    buscarEndereco: false,
    tooltipEndereco: false,
    loading: false,
    loadingFormations: false,
    maskPhone: "(##) ####-####",
    maskMobile: "(##) #####-####",
    maskCep: "#####-###",
    maskCpf: "###.###.###-##",
    maskCnpj: "##.###.###/####-##",
    maskBirthdate: "##/##/####",
    setIP: {},
    serviceRules: [(v) => !!v || "Por favor diga qual o tipo do evento"],
    selectedService: "",
    selectedInstruments: [],
    alertSelectedInstruments: false,
    selectedFormation: null,
    alertSelectedFormation: false,
    inscribeID: "",
    inscribeAccountable: "",
    inscribeAccountableRules: [(v) => !!v || "O campo NOME é requerido"],
    inscribeBirthdate: "",
    inscribeBirthdateRules: [
      (v) => !!v || "O campo DATA DE NASCIMENTO é requerido",
    ],
    inscribeEmail: "",
    inscribeEmailRules: [
      (v) => !!v || "O campo EMAIL é requerido",
      (v) => /.+@.+/.test(v) || "Insira um E-mail válido",
    ],
    inscribePhone: "",
    inscribePhoneRules: [(v) => !!v || "O campo TELEFONE é requerido"],
    inscribeMobile: "",
    inscribeMobileRules: [(v) => !!v || "O campo CELULAR é requerido"],
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
    endInscribe: false,
    eventName: "",
    eventDate: "",
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
    eventAddressRules: [(v) => !!v || "Este campo é obrigatório!"],
    pickDateEvent: false,
    pickTimeEvent: false,
    dialogVideoFormation: false,
    formation: "",
    selectedFormation: "",
    dialogTooltipFormation: false,
    setIP: { ip: null },
  }),

  computed: {
    ...mapGetters(["isStart"]),
  },

  mounted() {
    this.getServices();
    this.getInstruments();
    this.getIP();
  },

  methods: {
    ...mapActions([
      "getServices",
      "getInstruments",
      "getFormations",
      "next",
      "prev",
      "setSelectedService",
      "setInscribe",
      "restart",
    ]),

    restartApp: function () {
      this.restart();
    },

    is18: function (date) {
      if (getAge(date) < 18) {
        this.$swal("Responsável precisa ter mais que 18 anos");
        this.inscribeBirthdate = null;
      }
      // console.log(getAge(date));
    },

    verifyEmail: async function () {
      let data = new FormData();
      data.append("email", this.inscribeEmail);
      const resp = await axios(this.urlApi + "/user/verifyEmail", {
        method: "POST",
        data: data,
      });
      if (resp.data) {
        this.$swal("Este e-mail já está sendo utilizado");
        this.inscribeEmail = null;
      }
    },

    nextScreen: function () {
      if (this.tela == 1) {
        if (this.$refs.firstScreen.validate()) {
          this.next();
        }
      } else if (this.tela == 2) {
        if (this.selectedInstruments.length != 0) {
          this.alertSelectedInstruments = false;
          this.next();
          this.getFormationsByInstruments();
        } else {
          this.alertSelectedInstruments = true;
        }
      } else if (this.tela == 3) {
        if (this.selectedFormation) {
          this.next();
        } else {
          this.alertSelectedFormation = true;
        }
      } else if (this.tela == 4) {
        if (this.$refs.formInscribePartOne.validate()) {
          this.next();
        }
      }
    },
    prevScreen: function () {
      if (this.tela >= 1) {
        this.prev();
      }
    },
    finish: function () {
      if (this.$refs.formInscribePartOne.validate()) {
        this.saveInscribe();
        this.next();
      }
    },
    onChangeService: function () {
      this.setSelectedService(this.selectedService);
    },
    getFormationsByInstruments: async function () {
      this.loadingFormations = true;
      await this.getFormations(this.selectedInstruments);
      this.loadingFormations = false;
    },
    redirect: function (url) {
      window.open(url, "_blank");
    },
    isSelected: function (selected, alert) {
      if (this[selected]) {
        this[alert] = false;
      }
    },
    accessVideo: function (video) {
      window.open(video, "_blank");
    },
    openDialogFormationVideo: function (formation) {
      this.dialogVideoFormation = true;
      this.formation = formation;
    },
    closeDialogFormationVideo: function () {
      this.formation = "";
      this.dialogVideoFormation = false;
    },
    openDialogFormationTooltip: function (formation) {
      this.dialogTooltipFormation = true;
      this.formation = formation;
    },
    closeDialogFormationTooltip: function () {
      this.formation = "";
      this.dialogTooltipFormation = false;
    },
    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },
    getIP: function () {
      axios.get("https://api.ipify.org?format=json").then((response) => {
        this.setIP = response.data;
      });
    },
    getAddressData: function (addressData, placeResultData, id) {
      this.inscribeAddress.street = addressData.route;
      this.inscribeAddress.number = addressData.street_number
        ? addressData.street_number
        : "";
      const address = placeResultData.formatted_address.split("-");
      const neighborhood = address[1].split(",");
      this.inscribeAddress.neighborhood = neighborhood[0];
      this.inscribeAddress.state = addressData.administrative_area_level_1;
      this.inscribeAddress.country = addressData.country;
      this.inscribeAddress.city = addressData.administrative_area_level_2;
      this.inscribeAddress.zipcode = addressData.postal_code
        ? addressData.postal_code
        : "";
      this.buscarEndereco = false;
    },
    // saveLead: function () {
    //   let data = new FormData();
    //   data.append("datetime", new Date().toMysqlFormat());
    //   data.append("ip", this.setIP.ip);
    //   data.append("origin", "app toque divino");
    //   data.append("accountable", this.inscribeAccountable);
    //   // data.append("phone", this.inscribePhone);
    //   data.append("mobile", this.inscribeMobile);
    //   data.append("email", this.inscribeEmail);
    //   data.append("flag", 0);
    //   axios(this.urlApi + "/leads/create", {
    //     method: "POST",
    //     data: data,
    //   }).then((response) => {
    //     console.log(response.data);
    //   });
    // },
    // deleteLead: function () {
    //   let data = new FormData();
    //   data.append("ip", this.setIP.id);
    //   data.append("email", this.inscribeEmail);
    //   data.append("mobile", this.inscribeMobile);
    //   axios(this.urlApi + "/leads/delete", {
    //     method: "POST",
    //     data: data,
    //   }).then((response) => {
    //     console.log(response.data);
    //   });
    // },
    saveInscribe: function () {
      this.loading = true;
      let data = new FormData();
      data.append("datetime", this.$moment().format("YYYY-MM-DD"));
      data.append("email", this.inscribeEmail);
      data.append("accountable", this.inscribeAccountable);
      data.append("birthdate", toDateFormat(this.inscribeBirthdate));
      data.append("phone", this.inscribePhone);
      data.append("mobile", this.inscribeMobile);
      // data.append("address", JSON.stringify(this.inscribeAddress));
      data.append("cpf", "");
      data.append("rg", "");
      data.append("service_idservice", this.selectedService.idservice);
      data.append("formation_idformation", this.selectedFormation.idformation);
      data.append("ip", this.setIP.id);
      axios(this.urlApi + "/inscribes/createApp", {
        method: "POST",
        data: data,
      }).then((response) => {
        console.log(response.data);
        this.loading = false;
      });
    },
  },

  computed: {
    ...mapGetters([
      "tela",
      "start",
      "services",
      "instruments",
      "formations",
      "selService",
      "inscribe",
    ]),
  },
};
</script>

<style scoped>
  [style*="--aspect-ratio"] > :first-child {
    width: 100%;
  }

  [style*="--aspect-ratio"] > img {  
    height: auto;
  }

  @supports (--custom:property) {
    [style*="--aspect-ratio"] {
      position: relative;
    }
    
    [style*="--aspect-ratio"]::before {
      content: "";
      display: block;
      padding-bottom: calc(100% / (var(--aspect-ratio)));
    }
    
    [style*="--aspect-ratio"] > :first-child {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
    }  
  }
</style>

