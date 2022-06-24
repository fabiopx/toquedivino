<template>
  <div>
    <v-container class="cadastros">
      <!-- Inscribe Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="blue-grey">
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
                  <v-toolbar dark color="blue-grey">
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
                        <v-col cols="3">
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
                        <v-col cols="9">
                          <v-text-field
                            v-model="inscribeAccountable"
                            :rules="inscribeAccountableRules"
                            label="Nome do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="4">
                          <v-text-field
                            v-model="inscribePhone"
                            :rules="inscribePhoneRules"
                            v-mask="maskTel(inscribePhone)"
                            label="Telefone do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="4">
                          <v-text-field
                            v-model="inscribeMobile"
                            :rules="inscribeMobileRules"
                            v-mask="maskTel(inscribeMobile)"
                            label="Celular do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col>
                          <v-text-field
                            v-model="inscribeEmail"
                            :rules="inscribeEmailRules"
                            label="E-mail do responsável"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="2">
                          <v-text-field
                            v-model="inscribeAddress.zipcode"
                            :rules="inscribeAddressRules"
                            label="CEP"
                            v-mask="maskCep"
                            @blur="getCEP('inscribeAddress')"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.street"
                            :rules="inscribeAddressRules"
                            label="Logradouro"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="2">
                          <v-text-field
                            v-model="inscribeAddress.number"
                            :rules="inscribeAddressRules"
                            label="Número"
                            required
                          >
                          </v-text-field>
                        </v-col>
                        <v-col cols="2">
                          <v-text-field
                            v-model="inscribeAddress.complement"
                            label="Complemento"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.neighborhood"
                            :rules="inscribeAddressRules"
                            label="Bairro"
                            required
                          >
                          </v-text-field>
                        </v-col>
                        <v-col cols="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.city"
                            label="Cidade"
                            :rules="inscribeAddressRules"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                          <v-text-field
                            :loading="inputLoading"
                            v-model="inscribeAddress.state"
                            label="Estado"
                            :rules="inscribeAddressRules"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="3">
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
                        <v-col cols="6">
                          <v-text-field
                            v-model="inscribeCpf"
                            label="CPF"
                            v-mask="maskCpf"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field v-model="inscribeRg" label="RG">
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
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
                        <v-col cols="6">
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
export default {
  name: "DashboardInscribes",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      inputLoading: false,
      dialogInscribe: false,
      inscribeDatetime: "",
      inscribeAccountable: "",
      inscribeAccountableRules: [
        (v) => !!v || "O campo NOME DO RESPONSÁVEL é requerido",
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
      inscribeAddress: address,
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

    editInscribe: function (item) {
      this.dialogInscribe = true;
      this.idInscribe = item.idinscribe;
      this.crud = "u";
      this.inscribeDatetime = item.datetime;
      this.inscribeAccountable = item.accountable;
      this.inscribePhone = item.phone;
      this.inscribeMobile = item.mobile;
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

    getInscribe: function () {
      this.firstLoad = true;
      axios.get(this.apiURL + "/inscribes/get").then((response) => {
        this.inscribe = response.data;
        this.stopContentLoading();
      });
    },

    deleteInscribe: function (id) {
      this.$swal({
        title: "Deseja deletar cadastro?",
        icon: "question",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/inscribes/delete/" + id).then((response) => {
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
        })
          .then((response) => {
            this.loadingVisible = false;
            this.clearFormInscribe();
            this.inscribeDateTime = "";
            this.inscribeAccountable = "";
            this.inscribePhone = "";
            this.inscribeMobile = "";
            this.inscribeEmail = "";
            this.inscribeAddress = address;
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
            this.getPreInscribe();
          })
          .catch((err) => {
            this.loadingVisible = false;
            this.$swal(err, "", "error");
          });
      } else if (this.crud == "u") {
        data.append("account_idaccount", this.inscribeIdAccount);
        data.append("status", this.inscribeStatus);
        axios(this.apiURL + "/inscribes/update/" + this.idInscribe, {
          method: "POST",
          data: data,
        })
          .then((response) => {
            this.loadingVisible = false;
            this.clearFormInscribe();
            this.idInscribe = "";
            this.inscribeDateTime = "";
            this.inscribeAccountable = "";
            this.inscribePhone = "";
            this.inscribeMobile = "";
            this.inscribeEmail = "";
            this.inscribeAddress = address;
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
          })
          .catch((err) => {
            this.loadingVisible = false;
            this.$swal(err, "", "error");
          });
      }
    },
  },
};
</script>