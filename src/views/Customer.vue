<template>
  <div>
    <v-navigation-drawer dark v-model="drawer" app color="primary">
      <v-list dense>
        <v-list-item>
          <v-list-item-avatar>
            <v-img :src="userNow.photo"></v-img>
          </v-list-item-avatar>
        </v-list-item>
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-list-item link v-bind="attrs" v-on="on">
              <v-list-item-content>
                <v-list-item-title class="text-h6">
                  {{ accountName }}
                </v-list-item-title>
              </v-list-item-content>
              <v-list-item-action>
                <v-icon>mdi-menu-down</v-icon>
              </v-list-item-action>
            </v-list-item>
          </template>
          <v-list>
            <v-list-item link @click="logout()">
              <v-icon>mdi-logout</v-icon> Sair
            </v-list-item>
          </v-list>
        </v-menu>
      </v-list>
      <v-divider></v-divider>
      <v-list nav dense>
        <v-list-item-group>
          <v-list-item v-for="nav in navegador" :key="nav.link" link>
            <v-list-item-icon>
              <v-icon v-text="nav.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title
                v-text="nav.text"
                @click="$router.push({path: nav.link})"
              >
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar app :color="colorToolbar">
      <v-app-bar-nav-icon
        @click="drawer = !drawer"
        color="white"
      ></v-app-bar-nav-icon>

      <v-toolbar-title>
        <v-img :src="require('../assets/logotipo_branco.png')" width="120"></v-img>
      </v-toolbar-title>
    </v-app-bar>
    <v-main>
        <router-view></router-view>
    </v-main>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: "customer",

  data: () => ({
    urlApi: process.env.VUE_APP_URL,
    drawer: false,
    colorToolbar: "primary",
    navegador: [
      {
        icon: "mdi-home",
        text: "Home",
        link: "/customer/home",
      },
      {
        icon: "mdi-account",
        text: "Dados de acesso",
        link: "/customer/account",
      },
      {
        icon: "mdi-folder-information",
        text: "Cadastro",
        link: "/customer/inscribe",
      },
      {
        icon: "mdi-music-circle",
        text: "Repertório",
        link: "/customer/repertory",
      },
      {
        icon: "mdi-file-document-edit",
        text: "Contrato",
        link: "/customer/agreement",
      },
    ],
    showPage: "home",
    showTabInscribe: "inscribe",
    tabInscribeTitle: "Dados do cadastro",
    showPassword: false,
    maskPhone: "(##) ####-####",
    maskMobile: "(##) #####-####",
    maskCpf: "###.###.###-##",
    maskCnpj: "##.###.###/####-##",
    maskMoney: "######.##",
    maskBirthdate: "##/##/####",
    maskCep: "#####-###",
    alert: false,
    alertMsg: "",
    colorToolbar: "blue darken-4",
    alertLogin: { status: false, msg: "" },
    inputLoading: false,
    loginEmail: "",
    loginEmailRules: [(v) => !!v || "Digite o e-mail para realizar o login"],
    loginPassword: "",
    loginPasswordRules: [(v) => !!v || "Digite a senha para realizar o login"],
    loadingAccountFields: false,
    crud: "c",
    accountName: "",
    accountEmail: "",
    accountPassword: "",
    accountPhone: "",
    accountPhoto: "assets/img/profile.svg",
    currentFile: undefined,
    progressUpload: 0,
    progressShow: false,
    uploadSuccess: false,
    uploadError: false,
    uploadMsg: "",
    photo: "",
    services: [],
    formation: [],
    loadingInscribeFields: false,
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
    eventDateCountDown: "",
    eventTime: "",
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
    pickDateEvent: false,
    pickTimeEvent: false,
    loadingEngagedFields: false,
    selectEngaged: false,
    engagedID: "",
    engagedBrideAccountable: false,
    engagedGroomAccountable: false,
    engagedBrideName: "",
    engagedBrideNameRules: [(v) => !!v || "O campo NOME DA NOIVA é requerido"],
    engagedBridePhoto: "assets/img/woman.png",
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
    engagedGroomNameRules: [(v) => !!v || "O campo NOME DO NOIVO é requerido"],
    engagedGroomPhoto: "assets/img/man.png",
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
    engagedBrideUploadPhoto: false,
    engagedGroomUploadPhoto: false,
    loadingCommitteFields: false,
    committeID: "",
    graduationCommitteName: "",
    graduationCommitteNameRules: [(v) => !!v || "Por favor digite um nome"],
    graduationCommitteMember: [],
    loadingSelectFields: false,
    loadingListRepertory: false,
    startedRepertory: false,
    repertory: [],
    repertoryID: "",
    repertoryMoments: "",
    repertoryMomentsRules: [(v) => !!v || "Selecione um Momento"],
    repertoryMusic: "",
    repertoryMusicRules: [(v) => !!v || "Selecione um Música"],
    repertorySequence: "",
    moments: [],
    music: [],
    loadingAgreementService: false,
    loadingAgreementFormation: false,
    loadingAgreementEvent: false,
    loadingAgreementValues: false,
    contractCode: "",
    contractDate: "",
    contractValue: "",
    contractDiscount: "",
    contractAddition: "",
    contractDownPayment: "",
    contractDownPaymentExtenso: "",
    contractDownPaymentDate: "",
    contractValueTotal: "",
    contractValueExtenso: "",
    dialogContractSign: false,
    hasSignature: false,
    dialogSignWithPassword: false,
    formSignWithPassword: true,
    loadingSignWithPassword: false,
    alertSignWithPassword: false,
    signaturePassword: "",
    setIP: {},
  }),

  beforeCreate: function(){
    if (!this.$session.exists()){
      this.$router.push('/customer/login')
    }
  },

  created: function(){
    
  },

  mounted(){

  },

  methods: {
    ...mapActions(['setUserNow']),

    logout: function(){   
      this.setUserNow({ id: "", name: "", photo: "", logged: false, login: true }) 
      this.$session.destroy();
      this.$router.push('/customer/login')
    }
  },

  computed: {
    ...mapGetters(['userNow']),

  }
};
</script>
