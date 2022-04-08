<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>
                <span class="small">Comissão de Formatura</span>
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="loadingCommitteFields"
                type="text, button"
              ></v-skeleton-loader>
              <v-form
                v-show="!loadingCommitteFields"
                ref="formGraduationCommitte"
              >
                <v-row>
                  <v-col>
                    <v-text-field
                      v-model="graduationCommitteName"
                      label="Membro da comissão"
                      :rules="graduationCommitteNameRules"
                      required
                    >
                    </v-text-field>
                    <v-btn
                      color="grey darken-4"
                      class="white--text"
                      depressed
                      @click="addMemberGraduationCommitte()"
                    >
                      <v-icon>mdi-plus</v-icon>
                      Acrescentar
                    </v-btn>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-chip
                      v-for="(member, i) in graduationCommitteMember"
                      :key="i"
                      class="ma-1"
                      close
                      @click:close="removeMemberGraduationCommitte(i, 1)"
                    >
                      {{ member }}
                    </v-chip>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-alert v-show="alert" type="info" dismissible
                      >{{ alertMsg }}
                    </v-alert>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="grey darken-4"
                @click="saveCommitte()"
              >
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
    loadingCommitteFields: false,
    committeID: "",
    graduationCommitteName: "",
    graduationCommitteNameRules: [(v) => !!v || "Por favor digite um nome"],
    graduationCommitteMember: [],
  }),

  methods: {
    ...mapActions(["setUserNow"]),
    resetAllVars: function () {
      this.currentFileBride = undefined;
      this.currentFileGroom = undefined;
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
      this.eventID = "";
      this.eventName = "";
      this.eventDate = "";
      this.eventTime = "";
      this.eventAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.engagedID = "";
      this.engagedBrideName = "";
      this.engagedBrideAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.engagedBridePhone = "";
      this.engagedBrideMobile = "";
      this.engagedBrideCpf = "";
      this.engagedBrideRg = "";
      this.engagedBrideBirthdate = "";
      this.engagedBrideEmail = "";
      (this.engagedBrideResponsibleFor = false), (this.engagedGroomName = "");
      this.engagedGroomAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.engagedGroomPhone = "";
      this.engagedGroomMobile = "";
      this.engagedGroomCpf = "";
      (this.engagedGroomRg = ""), (this.engagedGroomBirthdate = "");
      this.engagedGroomEmail = "";
      (this.engagedGroomResponsibleFor = false),
        (this.graduationCommitteName = "");
      this.graduationCommitteMember = [];
    },
    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },
    addMemberGraduationCommitte: function () {
      if (this.$refs.formGraduationCommitte.validate()) {
        this.graduationCommitteMember.push(this.graduationCommitteName);
        this.$refs.formGraduationCommitte.reset();
        this.alert = false;
        this.alertMsg = "";
      }
    },
    removeMemberGraduationCommitte: function (i, n) {
      this.graduationCommitteMember.splice(i, n);
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
    getCommitte: async function () {
      this.loadingCommitteFields = true;
      const response = await axios.get(
        this.apiURL + "/committe/getCustomers/" + this.inscribeID
      );
      const resp = response.data;
      if (resp) {
        this.crud = "u";
        this.committeID = resp.idgraduation_committe;
        this.graduationCommitteName = "";
        this.graduationCommitteMember = resp.committe_name;
      } else {
        this.crud = "c";
      }
      this.loadingCommitteFields = false;
    },
    saveCommitte: function () {
      let data = new FormData();
      data.append(
        "committe_name",
        JSON.stringify(this.graduationCommitteMember)
      );
      data.append("inscribe_idinscribe", this.inscribeID);
      if (this.crud == "c") {
        axios(this.apiURL + "/committe/createCustomers", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getCommitte();
        });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/committe/updateCustomers/" + this.committeID, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getCommitte();
        });
      }
    },
  },

  computed: {
    ...mapGetters(["userNow", "isAgreement"]),
  },

  created: async function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    await this.getInscribe();
    await this.getCommitte();
  },
};
</script>
