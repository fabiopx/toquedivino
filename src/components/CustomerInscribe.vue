<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>
                <h2>
                  <v-icon class="mr-3">mdi-file-document-multiple</v-icon
                  >Cadastro
                </h2>
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="loadingInscribeFields"
                type="text@5"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingInscribeFields" ref="formInscribe">
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        label="Nome do Responsável"
                        v-model="inscribeAccountable"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="inscribeBirthdate"
                        v-mask="maskBirthdate"
                        label="Data de nascimento"
                        @change="is18(inscribeBirthdate)"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        label="Telefone"
                        v-model="inscribePhone"
                        v-mask="maskTel(inscribePhone)"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        label="Celular"
                        v-model="inscribeMobile"
                        v-mask="maskTel(inscribeMobile)"
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
                      <v-text-field
                        label="Logradouro"
                        v-model="inscribeAddress.street"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Número"
                        v-model="inscribeAddress.number"
                      >
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
                      <v-text-field
                        label="Bairro"
                        v-model="inscribeAddress.neighborhood"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Cidade"
                        v-model="inscribeAddress.city"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Estado"
                        v-model="inscribeAddress.state"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="País"
                        v-model="inscribeAddress.country"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="CPF"
                        v-model="inscribeCpf"
                        @input="pendente=true"
                        @change="verificarCpf"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="RG"
                        v-model="inscribeRg"
                      ></v-text-field>
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
              <v-btn
                depressed
                dark
                large
                color="red darken-4"
                class="pa-8"
                @click="saveInscribe()"
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

function FormataStringData(data) {
  var dia = data.split("/")[0];
  var mes = data.split("/")[1];
  var ano = data.split("/")[2];

  return ano + "-" + ("0" + mes).slice(-2) + "-" + ("0" + dia).slice(-2);
}

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

const validar = (cpf) => checkAll(prepare(cpf));

const notDig = (i) => ![".", "-", " "].includes(i);
const prepare = (cpf) => cpf.trim().split("").filter(notDig).map(Number);
const is11Len = (cpf) => cpf.length === 11;
const notAllEquals = (cpf) => !cpf.every((i) => cpf[0] === i);
const onlyNum = (cpf) => cpf.every((i) => !isNaN(i));

const calcDig = (limit) => (a, i, idx) => a + i * (limit + 1 - idx);
const somaDig = (cpf, limit) => cpf.slice(0, limit).reduce(calcDig(limit), 0);
const resto11 = (somaDig) => 11 - (somaDig % 11);
const zero1011 = (resto11) => ([10, 11].includes(resto11) ? 0 : resto11);

const getDV = (cpf, limit) => zero1011(resto11(somaDig(cpf, limit)));
const verDig = (pos) => (cpf) => getDV(cpf, pos) === cpf[pos];

const checks = [is11Len, notAllEquals, onlyNum, verDig(9), verDig(10)];
const checkAll = (cpf) => checks.map((f) => f(cpf)).every((r) => !!r);

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    imgPath: process.env.VUE_APP_IMGPATH,
    crud: "c",
    inputLoading: false,
    showPassword: false,
    loadingChips: false,
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
    inscribeBirthdate: "",
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
    inscribeInstrumentsByFormation: [],
    inscribeServiceTax: "",
    inscribeAgree: false,
    addInstruments: false,
    valido: false,
    pendente: true
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
      // console.log(getAge(date));
    },
    verificarCpf() {
      this.valido = validar(this.inscribeCpf);
      if(!this.valido){
        this.$swal("CPF inválido!");
      }
      this.pendente = false;
    },
    openAddInstruments: function () {
      this.addInstruments = true;
      this.getInstrumentsByFormation();
    },
    closeAddInstruments: function () {
      this.addInstruments = false;
      this.inscribeInstrumentsByFormation = [];
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
    getInstrumentsByFormation: async function () {
      this.loadingChips = true;
      const response = await axios.get(
        this.apiURL + "/formations/get/" + this.inscribeFormation.idformation
      );
      this.inscribeInstrumentsByFormation = response.data[0].instruments;
      this.loadingChips = false;
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
            this.inscribeBirthdate = this.$moment(resp.birthdate).format(
              "DD/MM/YYYY"
            );
            this.inscribePhone = resp.phone;
            this.inscribeMobile = resp.mobile;
            this.inscribeAddress = resp.address ? resp.address : "";
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
      data.append(
        "birthdate",
        this.$moment(FormataStringData(this.inscribeBirthdate)).format(
          "YYYY-MM-DD"
        )
      );
      data.append("phone", this.inscribePhone);
      data.append("mobile", this.inscribeMobile);
      data.append("address", JSON.stringify(this.inscribeAddress));
      data.append("cpf", this.inscribeCpf);
      data.append("rg", this.inscribeRg);
      data.append("idservice", this.inscribeService.idservice);
      data.append("idformation", this.inscribeFormation.idformation);
      data.append("status", 1);
      axios(this.apiURL + "/inscribes/updateCustomers/" + this.inscribeID, {
        method: "POST",
        data: data,
      }).then((response) => {
        this.$swal(response.data.msg, "", response.data.icon);
        this.getInscribe();
        if (this.access.first) this.$router.push("/customer/home");
      });
      // console.log(this.$moment(FormataStringData(this.inscribeBirthdate)).format("YYYY-MM-DD"));
    },
  },

  computed: {
    ...mapGetters(["userNow", "isAgreement", "access"]),
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
