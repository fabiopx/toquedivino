<template>
  <div>
    <v-container class="contratos">
      <!-- Contracts Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <h3>Contratos</h3>
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
                  <v-chip v-if="item.status == 2" dark color="green"
                    >Ativo</v-chip
                  >
                  <v-chip v-show="item.status == 3" dark color="primary"
                    >Cancelado
                  </v-chip>
                </template>

                <template v-slot:item.actions="{ item }">
                  
                  <!-- <v-btn v-show="item.status == 2" color="primary" icon dark>
                          <v-icon>mdi-file-link</v-icon>
                        </v-btn> -->

                  <v-btn
                    v-show="item.status == 1"
                    color="red"
                    icon
                    dark
                    @click="cancelContract(item.idinscribe)"
                  >
                    <v-icon>mdi-file-cancel</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
              
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
      inscribeAccountable: "",
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
    getContracts: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/contract/get");
      this.itemsContract = response.data;
      this.stopContentLoading();
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
      if (item.agreement) {
        this.contractValueTotal = item.agreement.totalValue;
        this.contractValueExtenso = item.agreement.totalValue.extenso();
        this.contractDownPayment = item.agreement.down_payment;
        this.contractDownPaymentExtenso = item.agreement.down_payment.extenso();
        this.contractDownPaymentDate = toDateFormat(
          item.agreement.down_payment_date
        );
        this.contractSignatures = item.signatures;
      }
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
    getDistance() {
      return new Promise((resolve, reject) => {
        var service = new google.maps.DistanceMatrixService();
        service
          .getDistanceMatrix({
            origins: ["Americana-SP"],
            destinations: [this.events.address.city],
            travelMode: "DRIVING",
          })
          .then((response) => {
            // console.log(response.rows[0].elements[0].distance.value * 2);
            resolve(response.rows[0].elements[0].distance.value * 2);
          })
          .catch((error) => {
            reject(error);
          });
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
  created: async function () {
    await this.getContracts();
    this.getIP();
  },
};
</script>

<style lang="scss" scoped>
</style>