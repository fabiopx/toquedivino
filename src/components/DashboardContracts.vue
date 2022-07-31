<template>
  <div>
    <v-container class="contratos">
      <!-- Contracts Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="blue-grey">
              <h3 class="white--text">Contratos</h3>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-dialog
                  v-model="dialogContractTrash"
                  persistent
                  transition="dialog-bottom-transition"
                  max-width="900"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      text
                      dark
                      v-bind="attrs"
                      v-on="on"
                      @click="getContractsTrash()"
                    >
                      <v-icon>mdi-delete-circle</v-icon>
                    </v-btn>
                  </template>
                  <v-card>
                    <v-toolbar dark color="blue-grey">
                      <v-icon class="mr-3">mdi-delete-circle</v-icon>Lixeira
                      <v-spacer></v-spacer>
                      <v-btn icon @click.stop="closeDialogContractTrash()">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-toolbar>
                    <v-card-text>
                      <v-skeleton-loader
                        v-if="firstLoad"
                        :tableLoading="true"
                        type="table"
                      >
                      </v-skeleton-loader>
                      <v-data-table
                        v-show="!firstLoad"
                        :headers="headersContractsTrash"
                        :items="itemsContractTrash"
                        :search="searchContract"
                        :items-per-page="5"
                      >
                        <template v-slot:item.actions="{ item }">
                          <v-btn
                            color="red"
                            icon
                            @click="deleteContract(item.idinscribe)"
                          >
                            <v-icon>mdi-delete</v-icon>
                          </v-btn>
                          <v-btn
                            color="purple"
                            icon
                            @click="removeContract(item.idinscribe)"
                          >
                            <v-icon>mdi-file-remove</v-icon>
                          </v-btn>
                          <v-btn
                            color="primary"
                            icon
                            @click="undoContract(item.idinscribe)"
                          >
                            <v-icon>mdi-file-undo</v-icon>
                          </v-btn>
                        </template>
                      </v-data-table>
                    </v-card-text>
                  </v-card>
                </v-dialog>
              </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="firstLoad"
                :tableLoading="true"
                type="table"
              >
              </v-skeleton-loader>
              <v-text-field
                v-model="searchContract"
                label="Busca"
                append-icon="mdi-magnify"
                class="mt-4"
              >
              </v-text-field>
              <v-data-table
                v-show="!firstLoad"
                :headers="headersContracts"
                :items="itemsContract"
                :search="searchContract"
                :items-per-page="5"
              >
                <template v-slot:item.status="{ item }">
                  <v-chip v-show="item.status == 1" dark color="green"
                    >Em análise</v-chip
                  >
                  <v-chip v-show="item.status == 2" dark color="primary"
                    >Contrato analisado
                  </v-chip>
                </template>

                <template v-slot:item.actions="{ item }">
                  <v-btn
                    v-show="(item.agreement.sign == 0, item.status == 2)"
                    color="blue-grey"
                    icon
                    dark
                    @click="openDialogContractSign(item)"
                  >
                    <v-icon>mdi-draw</v-icon>
                  </v-btn>
                  <v-dialog
                    v-model="dialogContractSign"
                    scrollable
                    persistent
                    max-width="700"
                  >
                    <v-card>
                      <v-card-title class="blue-grey white--text"
                        >Assinar contrato
                      </v-card-title>
                      <v-spacer></v-spacer>
                      <v-card-text> </v-card-text>
                      <v-card-actions>
                        <v-menu offset-y>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              depressed
                              color="blue-grey"
                              class="white--text mr-2"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon>mdi-draw</v-icon> Assinar
                            </v-btn>
                          </template>
                          <v-list>
                            <v-subheader>ASSINANTES DESTE CONTRATO</v-subheader>
                            <v-list-item-group>
                              <v-list-item
                                v-for="(sign, i) in contractSignatures"
                                :key="i"
                              >
                                <v-list-item-content>
                                  <v-list-item-title
                                    @click="signWithPassword(sign)"
                                  >
                                    {{ sign.name }}</v-list-item-title
                                  >
                                </v-list-item-content>
                              </v-list-item>
                            </v-list-item-group>
                          </v-list>
                        </v-menu>
                        <v-dialog
                          v-model="dialogSignWithPassword"
                          max-width="400"
                        >
                          <v-card>
                            <v-card-title class="blue-grey white--text"
                              >Assinatura com senha</v-card-title
                            >
                            <v-card-text v-show="formSignWithPassword">
                              <div class="mt-4 mb-4">
                                {{ upperCase(signatureWithPassword.name) }}
                              </div>
                              <v-text-field
                                type="password"
                                v-model="signaturePassword"
                                label="Digite a senha"
                                outlined
                              >
                              </v-text-field>
                            </v-card-text>
                            <v-card-text v-show="loadingSignWithPassword">
                              <v-card color="blue-grey">
                                <v-card-text>
                                  <v-progress-linear
                                    indeterminate
                                    color="white"
                                    class="mb-0"
                                  >
                                  </v-progress-linear>
                                </v-card-text>
                              </v-card>
                            </v-card-text>
                            <v-card-text v-show="alertSignWithPassword">
                              <v-alert type="error">Senha incorreta</v-alert>
                            </v-card-text>
                            <v-card-actions>
                              <v-spacer></v-spacer>
                              <v-btn
                                color="blue-grey white--text"
                                depressed
                                @click="
                                  doSignWithPassword(
                                    signatureWithPassword.idaccount
                                  )
                                "
                              >
                                <v-icon>mdi-draw</v-icon>Assinar
                              </v-btn>
                            </v-card-actions>
                          </v-card>
                        </v-dialog>
                        <v-btn depressed @click="closeDialogContractSign()">
                          <v-icon>mdi-close</v-icon> Fechar
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>

                  <!-- <v-btn v-show="item.status == 2" color="primary" icon dark>
                                                <v-icon>mdi-file-link</v-icon>
                                            </v-btn> -->

                  <v-btn
                    v-show="item.status == 2"
                    color="red"
                    icon
                    dark
                    @click="cancelContract(item.idinscribe)"
                  >
                    <v-icon>mdi-file-cancel</v-icon>
                  </v-btn>

                  <v-btn
                    v-show="item.status == 1"
                    color="blue"
                    icon
                    dark
                    @click="openAnalyzeContract(item)"
                  >
                    <v-icon>mdi-file-search</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
              <v-dialog
                v-model="dialogAnalyzeContract"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
              >
                <v-card>
                  <v-toolbar color="blue-grey" dark>
                    <v-btn icon dark @click="closeDialogAnalyzeContract()">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Analisar contrato</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn text @click="validateContract(idInscribe)"
                        ><v-icon>mdi-check</v-icon> Validar</v-btn
                      >
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col>
                          <v-list subheader three-line>
                            <v-subheader>
                              <h3>Resumo</h3>
                            </v-subheader>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title
                                  >Responsável pelo contrato
                                </v-list-item-title>
                                <v-list-item-subtitle
                                  ><b>Nome:</b> {{ inscribeAccountable }} |
                                  <b>Telefone:</b> {{ inscribePhone }} |
                                  <b>Celular:</b>
                                  {{ inscribeMobile }}</v-list-item-subtitle
                                >
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title
                                  >Endereço do responsável
                                </v-list-item-title>
                                <v-list-item-subtitle>
                                  {{ inscribeAddress.street }},
                                  {{ inscribeAddress.number }}
                                  {{ inscribeAddress.complement }},
                                  {{ inscribeAddress.neighborhood }},
                                  {{ inscribeAddress.district }} -
                                  {{ inscribeAddress.city }},
                                  {{ inscribeAddress.state }} -
                                  {{ inscribeAddress.country }}
                                </v-list-item-subtitle>
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title
                                  >Dados pessoais
                                </v-list-item-title>
                                <v-list-item-subtitle
                                  ><b>CPF: </b>{{ inscribeCpf }} | <b>RG: </b
                                  >{{ inscribeRg }}</v-list-item-subtitle
                                >
                              </v-list-item-content>
                            </v-list-item>
                          </v-list>
                        </v-col>
                      </v-row>
                      <v-sheet outlined rounded class="pa-5">
                        <v-row>
                          <v-col cols="12" lg="6">
                            <b>Serviço:</b> {{ contractService.name }}
                          </v-col>
                          <v-col
                            cols="12"
                            lg="6"
                            v-if="contractService.taxas.length"
                          >
                            <v-row>
                              <v-col><b>Taxas:</b></v-col>
                            </v-row>
                            <v-row
                              v-for="tax in contractService.taxas"
                              :key="tax.name"
                            >
                              <v-col cols="5">
                                <b>{{ tax.name }}:</b><br />R$
                                {{ printMoeda(tax.value) }}
                              </v-col>
                              <v-col cols="2" v-show="tax.type == 2">X</v-col>
                              <v-col cols="5" v-show="tax.type == 2">
                                <v-text-field
                                  v-model="tax.vValue"
                                  height="20"
                                  outlined
                                  @blur="
                                    addMultipliedContractValue(),
                                      calculateContractValue(),
                                      calculateContractValueTotal()
                                  "
                                >
                                </v-text-field>
                              </v-col>
                              <v-col v-show="tax.type == 2">
                                {{ tax.multiplied }}
                              </v-col>
                            </v-row>
                          </v-col>
                        </v-row>
                        <v-divider></v-divider>
                        <v-row>
                          <v-col cols="12" lg="6">
                            <b>Formação:</b> {{ contractFormation.name }}
                          </v-col>
                          <v-col cols="12" lg="6">
                            <b>Valor:</b>
                            R$ {{ printMoeda(contractFormation.price) }}
                          </v-col>
                        </v-row>
                      </v-sheet>
                      <v-form ref="formContract">
                        <v-row>
                          <v-col cols="12" md="4">
                            <v-text-field
                              label="Valor do contrato"
                              v-model="contractValue"
                              v-currency="{
                                currency: 'BRL',
                                locale: 'pt-BR',
                                autoDecimalMode: true,
                              }"
                              disabled
                            >
                            </v-text-field>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-text-field
                              label="Desconto ao valor do contrato"
                              v-model="contractDiscount"
                              ref="inputContractDiscount"
                              v-currency="{
                                currency: null,
                                locale: 'pt-BR',
                                autoDecimalMode: true,
                              }"
                              @blur="calculateContractValueTotal()"
                            >
                            </v-text-field>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-text-field
                              label="Adição ao valor do contrato"
                              v-model="contractAddition"
                              ref="inputContractAddition"
                              v-currency="{
                                currency: null,
                                locale: 'pt-BR',
                                autoDecimalMode: true,
                              }"
                              @blur="calculateContractValueTotal()"
                            >
                            </v-text-field>
                          </v-col>
                        </v-row>
                        <v-chip color="primary" class="pa2" large>
                          <span><b>TOTAL:</b></span
                          ><span> R$ {{ printMoeda(contractValueTotal) }}</span>
                        </v-chip>
                        <v-divider></v-divider>
                        <v-row>
                          <v-col>
                            <v-text-field
                              label="Valor de entrada"
                              v-model="contractDownPayment"
                              ref="inputContractDownPayment"
                              v-currency="{
                                currency: null,
                                locale: 'pt-BR',
                                autoDecimalMode: true,
                              }"
                            >
                            </v-text-field>
                          </v-col>
                          <v-col>
                            <v-menu
                              v-model="pickDateDownPayment"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              transition="scale-transition"
                              offset-y
                              min-width="auto"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="contractDownPaymentDate"
                                  label="Data da entrada"
                                  prepend-icon="mdi-calendar"
                                  readonly
                                  v-bind="attrs"
                                  :rules="contractDownPaymentDateRules"
                                  v-on="on"
                                >
                                </v-text-field>
                              </template>
                              <v-date-picker
                                v-model="contractDownPaymentDate"
                                @input="pickDateDownPayment = false"
                              >
                              </v-date-picker>
                            </v-menu>
                          </v-col>
                        </v-row>
                      </v-form>
                    </v-container>
                  </v-card-text>
                </v-card>
              </v-dialog>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardContracts",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      contentLoading: false,
      setIP: {},
      dialogContract: false,
      dialogContractSign: false,
      dialogContractTrash: false,
      dialogSignWithPassword: false,
      dialogAnalyzeContract: false,
      formSignWithPassword: true,
      searchContract: "",
      headersContracts: [
        {
          text: "Nome do responsável",
          value: "accountable",
        },
        {
          text: "Código do contrato",
          value: "agreement.code",
        },
        {
          text: "Evento",
          value: "event.name",
        },
        {
          text: "Data do contrato",
          value: "agreement.date",
        },
        {
          text: "Status",
          value: "status",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      itemsContract: [],
      headersContractsTrash: [
        {
          text: "Nome do responsável",
          value: "accountable",
        },
        {
          text: "Código do contrato",
          value: "agreement.code",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      itemsContractTrash: [],
      contractSignatures: [],
      signatureWithPassword: {},
      signaturePassword: "",
      loadingSignWithPassword: false,
      alertSignWithPassword: false,
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
      ePhoneRules: [
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
      engagedBrideAccountable: false,
      engagedGroomAccountable: false,
      engagedBrideName: "",
      engagedBrideNameRules: [
        (v) => !!v || "O campo NOME DA NOIVA é requerido",
      ],
      engagedBrideAddress: {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      },
      engagedBrideAddressRules: [(v) => !!v || "Este campo é requerido"],
      engagedBridePhone: "",
      engagedBridePhoneRules: [
        (v) => !!v || "O campo TELEFONE DA NOIVA é requerido",
      ],
      engagedBrideMobile: "",
      engagedBrideMobileRules: [
        (v) => !!v || "O campo CELULAR DA NOIVA é requerido",
      ],
      engagedBrideCpf: "",
      engagedBrideRg: "",
      engagedBrideBirthdate: "",
      engagedBrideEmail: "",
      engagedGroomName: "",
      engagedGroomNameRules: [
        (v) => !!v || "O campo NOME DO NOIVO é requerido",
      ],
      engagedGroomAddress: {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      },
      engagedGroomAddressRules: [(v) => !!v || "Este campo é requerido"],
      engagedGroomPhone: "",
      engagedGroomPhoneRules: [
        (v) => !!v || "O campo TELEFONE DO NOIVO é requerido",
      ],
      engagedGroomMobile: "",
      engagedGroomMobileRules: [
        (v) => !!v || "O campo CELULAR DO NOIVO é requerido",
      ],
      engagedGroomCpf: "",
      engagedGroomRg: "",
      engagedGroomBirthdate: "",
      engagedGroomEmail: "",
      selectEngaged: false,
    };
  },

  mounted() {},

  methods: {
    stopContentLoading: function () {
      this.contentLoading = false;
      this.firstLoad = false;
    },
    closeDialogContractTrash: function () {
      this.dialogContractTrash = false;
      this.itemsContractsTrash = [];
    },
    printMoeda: function (value) {
      let val = (value / 1).toFixed(2).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    getContractsTrash: function () {
      this.firstLoad = true;
      axios.get(this.apiURL + "/contract/get/3").then((response) => {
        this.itemsContractTrash = response.data;
        this.stopContentLoading();
      });
    },
    getIP: function () {
      axios
        .get(
          "https://api.ipgeolocation.io/ipgeo?apiKey=641aff88cd584ec7815acacaff6dada7&fields=geo"
        )
        .then((response) => {
          this.setIP = response.data;
        });
    },
    getContracts: function () {
      this.firstLoad = true;
      axios.get(this.apiURL + "/contract/get").then((response) => {
        this.itemsContract = response.data;
        this.stopContentLoading();
      });
    },
    deleteContract: function (id) {
      this.$swal({
        title: "Deseja deletar definitivamente o contrato?",
        icon: "question",
        text: "Isso irá remover todos os dados do contrato, incluindo os dados do cadastro do cliente.",
        confirmButtonText: "Deletar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/contract/delete/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            console.log(response.data);
            this.getContractsTrash();
          });
        }
      });
    },
    cancelContract: function (id) {
      this.$swal({
        title: "Deseja cancelar o contrato",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Cancelar Contrato",
        cancelButtonText: "Não cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(this.apiURL + "/contract/cancel/" + id)
            .then((response) => response.json())
            .then((json) => {
              this.$swal(json.msg, "", json.icon);
              this.getContracts();
            });
        }
      });
    },
    removeContract: function (id) {
      this.$swal({
        title: "Deseja remover o contrato?",
        text: "Isso irá remover os dados do contrato, mas irá manter os dados do cadastro do cliente.",
        icon: "question",
        confirmButtonText: "Remover",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/contract/remove/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            console.log(response.data);
            this.getContractsTrash();
          });
        }
      });
    },
    undoContract: function (id) {
      this.$swal({
        title: "Deseja restaurar o contrato",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Restaurar Contrato",
        cancelButtonText: "Não restaurar",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(this.apiURL + "/contract/undo/" + id)
            .then((response) => response.json())
            .then((json) => {
              this.$swal(json.msg, "", json.icon);
              this.getContractsTrash();
              this.getContracts();
            });
        }
      });
    },
    openDialogContractSign: function (item) {
      this.dialogContractSign = true;
      this.inscribeAccountable = item.accountable;
      this.inscribeCpf = item.cpf;
      this.inscribeRg = item.rg;
      this.inscribePhone = item.phone;
      this.inscribeMobile = item.mobile;
      this.selectEngaged = item.engaged != null ? true : false;
      if (item.engaged != null) {
        this.engagedGroomName = item.engaged.groom_name;
        this.engagedGroomAddress = JSON.parse(item.engaged.groom_address);
        this.engagedGroomPhone = item.engaged.groom_phone;
        this.engagedGroomMobile = item.engaged.groom_mobile;
        this.engagedGroomCpf = item.engaged.groo_cpf;
        this.engagedGroomRg = item.engaged.groom_rg;
        this.engagedGroomBirthdate = toDateFormat(item.engaged.groom_birthdate);
        this.engagedGroomEmail = item.engaged.groom_email;
        this.engagedBrideName = item.engaged.bride_name;
        this.engagedBrideAddress = JSON.parse(item.engaged.bride_address);
        this.engagedBridePhone = item.engaged.bride_phone;
        this.engagedBrideMobile = item.engaged.bride_mobile;
        this.engagedBrideCpf = item.engaged.bride_cpf;
        this.engagedBrideRg = item.engaged.bride_rg;
        this.engagedBrideBirthdate = toDateFormat(item.engaged.bride_birthdate);
        this.engagedBrideEmail = item.engaged.bride_email;
      }
      this.contractValueTotal = item.agreement.totalValue;
      this.contractValueExtenso = item.agreement.totalValue.extenso();
      this.contractDownPayment = item.agreement.down_payment;
      this.contractDownPaymentExtenso = item.agreement.down_payment.extenso();
      this.contractDownPaymentDate = toDateFormat(
        item.agreement.down_payment_date
      );
      this.contractSignatures = item.signatures;
    },
    closeDialogContractSign: function () {
      this.dialogContractSign = false;
      this.inscribeAccountable = "";
      this.inscribeCpf = "";
      this.inscribeRg = "";
      this.inscribePhone = "";
      this.inscribeMobile = "";
      this.selectEngaged = false;
      this.engagedGroomName = "";
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
      this.contractValueTotal = "";
      this.contractValueExtenso = "";
      this.contractDownPayment = "";
      this.contractDownPaymentExtenso = "";
      this.contractDownPaymentDate = "";
      this.contractSignatures = [];
    },
    signWithPassword: function (item) {
      this.dialogContractSignature = false;
      this.dialogSignWithPassword = true;
      this.signatureWithPassword = item;
    },
    openAnalyzeContract: function (item) {
      this.dialogAnalyzeContract = true;
      this.idInscribe = item.idinscribe;
      this.inscribeAccountable = item.accountable;
      this.inscribePhone = item.phone;
      this.inscribeMobile = item.mobile;
      this.inscribeAddress = item.address;
      this.inscribeCpf = item.cpf;
      this.inscribeRg = item.rg;
      this.contractFormation = item.formation;
      this.contractService = item.service;
      this.calculateContractTaxValue();
      this.calculateContractFormationValue();
      this.calculateContractValue();
    },
    closeDialogAnalyzeContract: function () {
      this.dialogAnalyzeContract = false;
      this.idInscribe = "";
      this.inscribeAccountable = "";
      this.inscribePhone = "";
      this.inscribeMobile = "";
      this.inscribeAddress = "";
      this.inscribeCpf = "";
      this.inscribeRg = "";
      this.formationName = "";
      this.serviceName = "";
      this.contractService = { taxas: [] };
      this.contractFormation = {};
      this.contractTaxValue = [];
      this.$ci.setValue(this.$refs.inputContractDiscount, 0);
      this.$ci.setValue(this.$refs.inputContractAddition, 0);
      this.contractFormationValue = 0;
      this.contractTaxVariantValue = 0;
      this.contractFixTaxValue = 0;
      this.contractValue = 0;
      this.contractValueTotal = 0;
      this.$ci.setValue(this.$refs.inputContractDownPayment, 0);
      this.contractDownPaymentDate = "";
    },
    sortContractSign: function (array) {
      return _.orderBy(array, "type", "asc");
    },
    upperCase: function (text) {
      return _.upperCase(text);
    },
    doSignWithPassword: function (id) {
      this.formSignWithPassword = false;
      this.loadingSignWithPassword = true;
      let data = new FormData();
      data.append("password", this.signaturePassword);
      data.append("idaccount", id);
      data.append("date", new Date().toMysqlFormat());
      data.append("ip", this.setIP.ip);
      data.append(
        "geolocation",
        this.setIP.latitude + "," + this.setIP.longitude
      );
      data.append("hash", this.signaturePassword.hashCode());
      axios(this.apiURL + "/signatures/signWithPassword/", {
        method: "POST",
        data: data,
      }).then((response) => {
        if (response.data) {
          this.loadingSignWithPassword = false;
          this.dialogSignWithPassword = false;
        } else {
          this.loadingSignWithPassword = false;
          this.formSignWithPassword = true;
          this.alertSignWithPassword = true;
        }
      });
    },
    calculateContractTaxValue: function () {
      let value = 0;
      if (this.contractService.taxas != null) {
        this.contractService.taxas.forEach(function (t) {
          if (t.type == 1) {
            value = parseFloat(value) + parseFloat(t.value);
          }
        });
      }
      this.contractFixTaxValue = value;
    },
    calculateContractFormationValue: function () {
      this.contractFormationValue = this.contractFormation.price;
    },
    addMultipliedContractValue: function () {
      let sMultiplied = 0;
      if (this.contractService.taxas != null) {
        this.contractService.taxas.forEach(function (item) {
          sMultiplied += parseFloat(item.value) * parseFloat(item.vValue);
        });

        this.contractTaxVariantValue = sMultiplied;
      } else {
        this.contractTaxVariantValue = 0;
      }
    },
    calculateContractValue: function () {
      let value =
        parseFloat(this.contractFormationValue) +
        parseFloat(this.contractFixTaxValue) +
        parseFloat(this.contractTaxVariantValue);
      this.contractValue = value;
      this.contractValueTotal = value;
    },
    calculateContractValueTotal: function () {
      this.contractValueTotal =
        parseFloat(this.contractValue) -
        this.$ci.parse(this.contractDiscount) +
        this.$ci.parse(this.contractAddition);
    },
    validateContract: function (id) {
      this.closeDialog("dialogAnalyzeContract");
      this.loadingVisible = true;
      let data = new FormData();
      data.append("value", this.contractValue);
      data.append("discount", this.contractDiscount);
      data.append("addition", this.contractAddition);
      data.append("variant_tax", JSON.stringify(this.contractService.taxas));
      data.append("downpayment", this.$ci.parse(this.contractDownPayment));
      data.append("downpaymentdate", this.contractDownPaymentDate);
      axios(this.apiURL + "/contract/validate/" + id, {
        method: "POST",
        data: data,
      }).then((response) => {
        this.loadingVisible = false;
        this.contractService = { taxas: [] };
        this.contractFormation = {};
        this.contractTaxValue = [];
        this.contractAddition = 0;
        this.contractDiscount = 0;
        this.contractFormationValue = 0;
        this.contractTaxVariantValue = 0;
        this.contractFixTaxValue = 0;
        this.contractValue = 0;
        this.contractValueTotal = 0;
        this.$swal(response.data.msg, "", response.data.icon);
        this.getContracts();
      });
    },
  },
  created: function(){
    this.getContracts();
    this.getIP();
  }
};
</script>

<style lang="scss" scoped>
</style>