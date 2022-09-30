<template>
  <div>
    <v-navigation-drawer dark v-model="drawer" app color="grey darken-4">
      <v-list dense>
        <v-list-item>
        </v-list-item>
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-list-item link v-bind="attrs" v-on="on">
              <v-list-item-content>
                <v-list-item-title class="text-h6">
                  {{ userNow.name }}
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
            <v-list-item-content v-show="!nav.sub">
              <v-list-item-title
                v-text="nav.text"
                @click="$router.push({ path: nav.link })"
              >
              </v-list-item-title>
            </v-list-item-content>
            <v-list-item-content v-show="nav.sub">
              <v-list-item-title v-text="nav.text"></v-list-item-title>
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
        <v-img :src="require('../assets/logotipo.png')" width="120"></v-img>
      </v-toolbar-title>
    </v-app-bar>
    <v-main>
      <router-view></router-view>
    </v-main>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";

export default {
  name: "customer",

  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    drawer: false,
    colorToolbar: "grey darken-4",
    inscribe: null,
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
        icon: "mdi-file-document-multiple",
        text: "Cadastro",
        link: "/customer/inscribe",
      },
      {
        icon: "mdi-calendar-check",
        text: "Evento",
        link: "/customer/event",
      },
      {
        icon: "mdi-heart-circle",
        text: "Noivos",
        link: "/customer/engaged",
      },
      {
        icon: "mdi-account-school",
        text: "Comitê de Formatura",
        link: "/customer/committe",
      },
      {
        icon: "mdi-music-circle",
        text: "Repertório",
        link: "/customer/repertory",
      },
      {
        icon: "mdi-file-document-edit",
        text: "Orçamento",
        link: "/customer/budget",
      },
      {
        icon: "mdi-file-sign",
        text: "Contrato",
        link: "/customer/agreement",
      },
    ],
  }),

  beforeCreate: function () {
    if (!this.$session.exists()) {
      this.$router.push("/customer/login");
    } else{
      this.$router.push("/customer/home");
    }
  },

  created: async function () {
    if (this.$session.exists()) {
      await this.getInscribeID();
      await this.verifyIsAgreement();
    }
  },

  mounted() {},

  methods: {
    ...mapActions(["setUserNow", "setInscribeID", "setIsAgreement"]),

    logout: function () {
      this.setUserNow({
        id: "",
        name: "Customer",
        photo: process.env.VUE_APP_IMGPATH + "profile.svg",
        logged: false,
        login: true,
      });
      this.$session.destroy();
      this.$router.push("/customer/login");
    },
    getInscribeID: async function () {
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      const resp = response.data;
      this.setInscribeID(resp.idinscribe);
    },
    verifyIsAgreement: async function () {
      const response = await axios.get(
        this.apiURL + "/agreements/get/" + this.inscribeID
      );
      const resp = response.data(resp)
        ? this.setIsAgreement(true)
        : this.setIsAgreement(false);
    },
  },

  computed: {
    ...mapGetters(["userNow", "inscribeID", "isAgreement"]),
  },
};
</script>
