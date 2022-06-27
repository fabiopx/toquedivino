<template>
  <div>
    <v-container class="cadastros">
      <!-- Inscribe Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4">
              <h3 class="white--text">Cadastros</h3>
              <v-spacer></v-spacer>
              <!-- add inscribe -->
              <v-dialog
                v-model="dialogInscribe"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-toolbar-items>
                    <v-btn
                      dark
                      text
                      v-bind="attrs"
                      v-on="on"
                      @click="setCrud('c'), setDataInscribe()"
                    >
                      <v-icon>mdi-plus</v-icon> novo cadastro
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogInscribe', 'formInscribe')"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                      <v-icon>mdi-text-box-plus</v-icon> Cadastrar cliente
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveInscribe()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-form ref="formInscribe" lazy-validation>
                    <v-container>
                      <v-row>
                        <v-col cols="12" md="3">
                          <v-menu
                            v-model="pickDateInscribe"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                v-model="inscribeDatetime"
                                label="Selecione a data"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker
                              v-model="inscribeDatetime"
                              @input="pickDateInscribe = false"
                            ></v-date-picker>
                          </v-menu>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="inscribeAccountable"
                            :rules="inscribeAccountableRules"
                            label="Nome do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                          <v-text-field
                            v-model="inscribeBirthdate"
                            v-mask="maskBirthdate"
                            :rules="inscribeBirthdateRule"
                            label="Data de Nascimento"
                            required
                            @change="is18(inscribeBirthdate)"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="inscribePhone"
                            :rules="inscribePhoneRules"
                            v-mask="maskTel(inscribePhone)"
                            label="Telefone do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="inscribeMobile"
                            :rules="inscribeMobileRules"
                            v-mask="maskTel(inscribeMobile)"
                            label="Celular do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="inscribeEmail"
                            :rules="inscribeEmailRules"
                            label="E-mail do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="2">
                          <v-text-field
                            v-model="inscribeAddress.zipcode"
                            :rules="inscribeAddressRules"
                            label="CEP"
                            v-mask="maskCep"
                            @blur="getCEP('inscribeAddress')"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.street"
                            :rules="inscribeAddressRules"
                            label="Logradouro"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                          <v-text-field
                            v-model="inscribeAddress.number"
                            :rules="inscribeAddressRules"
                            label="Número"
                            required
                          >
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                          <v-text-field
                            v-model="inscribeAddress.complement"
                            label="Complemento"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.neighborhood"
                            :rules="inscribeAddressRules"
                            label="Bairro"
                            required
                          >
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.city"
                            label="Cidade"
                            :rules="inscribeAddressRules"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.state"
                            label="Estado"
                            :rules="inscribeAddressRules"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.country"
                            label="País"
                            :rules="inscribeAddressRules"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="inscribeCpf"
                            label="CPF"
                            v-mask="maskCpf"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field v-model="inscribeRg" label="RG">
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-select
                            v-model="inscribeIdService"
                            label="Serviço"
                            :rules="inscribeIdServiceRules"
                            :items="services"
                            item-text="name"
                            item-value="idservice"
                            required
                          >
                          </v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-select
                            v-model="inscribeIdFormation"
                            label="Formação"
                            :rules="inscribeIdFormationRules"
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
                </v-card>
              </v-dialog>
            </v-toolbar>
            <!-- inscribe table -->
            <v-card-text>
              <v-skeleton-loader
                v-if="firstLoad"
                :tableLoading="true"
                type="table"
              >
              </v-skeleton-loader>
              <v-text-field
                v-model="searchInscribe"
                label="Busca"
                append-icon="mdi-magnify"
                class="mt-4"
              >
              </v-text-field>
              <v-data-table
                v-show="!firstLoad"
                :headers="headersInscribe"
                :items="inscribe"
                :search="searchInscribe"
                :items-per-page="5"
              >
                <template v-slot:item.budget="{ item }">
                  <v-expansion-panels>
                    <v-expansion-panel
                      v-for="budget in item.budget"
                      :key="budget.idbudget"
                    >
                      <v-expansion-panel-header>
                        {{ budget.code }}
                        <v-spacer></v-spacer>
                        <v-chip v-show="budget.status == 0" class="justify-center">Iniciado</v-chip>
                        <v-chip v-show="budget.status == 1" dark color="primary" class="justify-center">Validado</v-chip>
                        <v-chip v-show="budget.status == 2" dark color="primary" class="justify-center">Finalizado</v-chip>
                        <v-chip v-show="budget.status == 3" dark color="amber" class="justify-center">Expirado</v-chip>
                        <v-chip v-show="budget.status == 4" dark color="red" class="justify-center">Cancelado</v-chip>
                      </v-expansion-panel-header>
                      <v-expansion-panel-content>
                        <p>Data: {{ budget.date }}</p>
                        <p>Valor: {{ budget.value }}</p>
                        <v-btn v-if="budget.status == 0" color="primary" @click="validate"><v-icon>mdi-check</v-icon>Validar</v-btn>
                      </v-expansion-panel-content>
                    </v-expansion-panel>
                  </v-expansion-panels>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="blue" icon @click="editInscribe(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    color="red"
                    icon
                    @click="deleteInscribe(item.idinscribe)"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <!-- End Inscribe Card -->
    </v-container>
  </div>
</template>

<script>
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

function FormataStringData(data) {
  var dia = data.split("/")[0];
  var mes = data.split("/")[1];
  var ano = data.split("/")[2];

  return ano + "-" + ("0" + mes).slice(-2) + "-" + ("0" + dia).slice(-2);
}

export default {
  name: "DashboardInscribes",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      inputLoading: false,
      dialogInscribe: false,
      dialogBudget: false,
      maskPhone: "(##) ####-####",
      maskMobile: "(##) #####-####",
      maskCep: "#####-###",
      maskCpf: "###.###.###-##",
      maskCnpj: "##.###.###/####-##",
      maskMoney: "######.##",
      maskBirthdate: "##/##/####",
      formation: [],
      services: [],
      inscribeDatetime: "",
      inscribeAccountable: "",
      inscribeAccountableRules: [
        (v) => !!v || "O campo NOME DO RESPONSÁVEL é requerido",
      ],
      inscribeBirthdate: "",
      inscribeBirthdateRule: [
        (v) => !!v || "O campo DATA DE NASCIMENTO é requrido",
      ],
      inscribePhone: "",
      inscribePhoneRules: [
        (v) => !!v || "O campo TELEFONE DO RESPONSÁVEL é requerido",
      ],
      inscribeMobile: "",
      inscribeMobileRules: [
        (v) => !!v || "O campo CELULAR DO RESPONSÁVEL é requerido",
      ],
      inscribeEmail: "",
      inscribeEmailRules: [
        (v) => !!v || "O campo E-MAIL é requerido",
        (v) => /.+@.+/.test(v) || "Insira um E-mail válido",
      ],
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
      inscribeAddressRules: [(v) => !!v || "Preencha corretamente o endereço"],
      inscribeCpf: "",
      inscribeRg: "",
      inscribeCnpf: "",
      inscribeIdAccount: "",
      inscribeIdService: "",
      inscribeIdServiceRules: [(v) => !!v || "Selecione um serviço"],
      inscribeIdFormation: "",
      inscribeIdFormationRules: [(v) => !!v || "Selecione uma formação"],
      inscribeStatus: "",
      searchInscribe: "",
      headersInscribe: [
        {
          text: "Nome",
          value: "accountable",
        },
        {
          text: "Data",
          value: "datetime",
        },
        {
          text: "Telefone",
          value: "phone",
        },
        {
          text: "Celular",
          value: "mobile",
        },
        {
          text: "Orçamentos",
          value: "budget",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      inscribe: [],
      idInscribe: "",
      pickDateInscribe: false,
      contractService: { taxas: [] },
      contractFormation: {},
      contractFinish: false,
      contractTaxValue: [],
      contractFixTaxValue: 0,
      contractTaxVariantValue: 0,
      contractFormationValue: 0,
      contractValue: 0,
      contractValueExtenso: "",
      contractValueTotal: 0,
      contractDiscount: 0,
      contractAddition: 0,
      contractDownPayment: 0,
      contractDownPaymentExtenso: "",
      contractDownPaymentDate: "",
      contractDownPaymentDateRules: [
        (v) => !!v || "O campo DATA DO EVENTO é requerido",
      ],
      contractSignatures: [],
      pickDateDownPayment: false,
      tabContract: null,
    };
  },

  mounted() {},

  methods: {
    setCrud: function (op) {
      this.crud = op;
    },

    closeDialog: function (d, form = null) {
      this[d] = false;
      if (form != null) {
        this.$refs[form].reset();
      }
    },

    stopContentLoading: function () {
      this.contentLoading = false;
      this.firstLoad = false;
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

    clearFormInscribe: function () {
      this.$refs.formInscribe.reset();
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

    setDataInscribe: function () {
      this.inscribeDatetime = new Date().toISOString().substr(0, 10);
      this.inscribePhone = null;
      this.inscribeMobile = null;
      this.inscribeAddress.zipcode = null;
      this.inscribeCpf = null;
    },

    getFormations: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/formations/get");
      this.formation = response.data;
      this.stopContentLoading();
    },

    getServices: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/services/get");
      this.services = response.data;
      this.stopContentLoading();
    },

    editInscribe: function (item) {
      this.dialogInscribe = true;
      this.idInscribe = item.idinscribe;
      this.crud = "u";
      this.inscribeDatetime = item.datetime;
      this.inscribeAccountable = item.accountable;
      this.inscribeBirthdate = this.$moment(item.birthdate).format(
        "DD/MM/YYYY"
      );
      this.inscribePhone = item.phone;
      this.inscribeMobile = item.mobile;
      this.inscribeCpf = item.cpf;
      this.inscribeRg = item.rg;
      this.inscribeIdService = item.service.idservice;
      this.inscribeIdFormation = item.formation.idformation;

      if (item.address) {
        this.inscribeAddress.street = item.address.street;
        this.inscribeAddress.number = item.address.number;
        this.inscribeAddress.complement = item.address.complement;
        this.inscribeAddress.neighborhood = item.address.neighborhood;
        this.inscribeAddress.city = item.address.city;
        this.inscribeAddress.zipcode = item.address.zipcode;
        this.inscribeAddress.state = item.address.state;
        this.inscribeAddress.country = item.address.country;
      }

      this.inscribeCpf = item.cpf;
      this.inscribeRg = item.rg;
      this.inscribeIdAccount = item.account.idaccount;
      this.inscribeEmail = item.account.email;
      this.inscribeStatus = item.status;
    },

    getInscribe: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/inscribes/get");
      this.inscribe = response.data;
      this.stopContentLoading();
    },

    deleteInscribe: function (id) {
      this.$swal({
        title: "Deseja deletar cadastro?",
        icon: "question",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .get(this.apiURL + "/inscribes/delete/" + id)
            .then((response) => {
              this.$swal(response.data.msg, "", response.data.icon);
              console.log(response.data);
              this.getInscribe();
            });
        }
      });
    },

    saveInscribe: function () {
      this.closeDialog("dialogInscribe");
      this.loadingVisible = true;
      let data = new FormData();
      data.append("datetime", this.inscribeDatetime);
      data.append("email", this.inscribeEmail);
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
      data.append("service_idservice", this.inscribeIdService);
      data.append("formation_idformation", this.inscribeIdFormation);

      if (this.crud == "c") {
        if (this.preInscribeFlag != null) {
          data.append("flag", this.preInscribeFlag);
          data.append("idpre_inscribe", this.idPreInscribe);
        }

        axios(this.apiURL + "/inscribes/create", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.clearFormInscribe();
          this.inscribeDateTime = "";
          this.inscribeAccountable = "";
          this.inscribeBirthdate = "";
          this.inscribePhone = "";
          this.inscribeMobile = "";
          this.inscribeEmail = "";
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
          this.inscribeIdAccount = "";
          this.inscribeIdService = "";
          this.inscribeIdFormation = "";
          this.inscribeStatus = "";
          this.inscribe = [];
          this.$swal(response.data.msg, "", response.data.icon);
          console.log(response.data);
          this.getInscribe();
        });
      } else if (this.crud == "u") {
        data.append("account_idaccount", this.inscribeIdAccount);
        data.append("status", this.inscribeStatus);
        axios(this.apiURL + "/inscribes/update/" + this.idInscribe, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.loadingVisible = false;
          this.idInscribe = "";
          this.inscribeDateTime = "";
          this.inscribeAccountable = "";
          this.inscribeBirthdate = "";
          this.inscribePhone = "";
          this.inscribeMobile = "";
          this.inscribeEmail = "";
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
          this.inscribeIdAccount = "";
          this.inscribeIdService = "";
          this.inscribeIdFormation = "";
          this.inscribeStatus = "";
          this.inscribe = [];
          this.$swal(response.data.msg, "", response.data.icon);
          console.log(response.data);
          this.getInscribe();
        });
      }
    },

    validate: async function (id) {
      const response = await axios.get(
        this.apiURL + "/inscribes/validate/" + id
      );
      const resp = response.data;
      this.$swal(resp.msg, "", resp.icon);
      await this.getInscribe();
    },
  },

  created: async function () {
    await this.getFormations();
    await this.getServices();
    await this.getInscribe();
  },
};
</script>