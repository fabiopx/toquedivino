<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>
                <span class="small">Cadastro</span>
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader v-if="loadingInscribeFields" type="text@5" loading>
              </v-skeleton-loader>
              <v-form v-show="!loadingInscribeFields" ref="formInscribe">
                <v-container>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Nome do Responsável"
                        v-model="inscribeAccountable"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Telefone"
                        v-model="inscribePhone"
                        v-mask="maskTel(inscribePhone)"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4" lg="2">
                      <v-text-field
                        label="CEP"
                        v-model="inscribeAddress.zipcode"
                        v-mask="maskCep"
                        @blur="getCEP('inscribeAddress')"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="8" lg="6">
                      <v-text-field label="Logradouro" v-model="inscribeAddress.street">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field label="Número" v-model="inscribeAddress.number">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Complemento"
                        v-model="inscribeAddress.complement"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field label="Bairro" v-model="inscribeAddress.neighborhood">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field label="Cidade" v-model="inscribeAddress.city">
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field label="Estado" v-model="inscribeAddress.state">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field label="País" v-model="inscribeAddress.country">
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field label="CPF" v-model="inscribeCpf"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field label="RG" v-model="inscribeRg"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-select
                        v-model="inscribeService.idservice"
                        label="Serviço"
                        :items="services"
                        item-text="name"
                        item-value="idservice"
                        required
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-select
                        v-model="inscribeFormation.idformation"
                        label="Formação"
                        :items="formation"
                        item-text="name"
                        item-value="idformation"
                        required
                      >
                      </v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn depressed dark large color="grey darken-4" @click="saveInscribe()">
                <v-icon>mdi-content-save</v-icon> Salvar
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
  }),

  methods: {
    ...mapActions(["setUserNow"]),
    
    resetAllVars: function () {
      this.inscribeAccountable = "";
      this.inscribePhone = "";
      this.inscribeMobile = "";
      this.inscribeAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.inscribeCpf = "";
      this.inscribeRg = "";
      this.inscribeService = "";
      this.inscribeFormation = "";
      this.inscribeServiceTax = "";
      this.services = [];
      this.formation = [];
    },
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
    getServices: function () {
      this.loadingAgreementService = true;
      axios.get(this.apiURL + "/services/get").then((response) => {
        this.services = response.data;
        this.loadingAgreementService = false;
      });
    },
    getFormations: function () {
      this.loadingAgreementFormation = true;
      axios.get(this.apiURL + "/formations/get").then((response) => {
        this.formation = response.data;
        this.loadingAgreementFormation = false;
      });
    },
    getInscribe: function () {
      this.loadingInscribeFields = true;
      axios
        .get(this.apiURL + "/inscribes/getCustomers/" + this.userNow.id)
        .then((response) => {
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
        });
    },
    saveInscribe: function () {
      let data = new FormData();
      data.append("accountable", this.inscribeAccountable);
      data.append("phone", this.inscribePhone);
      data.append("mobile", this.inscribeMobile);
      data.append("address", JSON.stringify(this.inscribeAddress));
      data.append("cpf", this.inscribeCpf);
      data.append("rg", this.inscribeRg);
      data.append("idservice", this.inscribeService.idservice);
      data.append("idformation", this.inscribeFormation.idformation);
      axios(this.apiURL + "/inscribes/updateCustomers/" + this.inscribeID, {
        method: "POST",
        data: data,
      }).then((response) => {
        this.$swal(response.data.msg, "", response.data.icon);
        this.getInscribe();
      });
    },
  },

  computed: {
    ...mapGetters(["userNow", "isAgreement"]),
  },

  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    this.getInscribe();
    this.getServices();
    this.getFormations();
  },
};
</script>
