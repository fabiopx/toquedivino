<template>
  <div>
    <v-navigation-drawer dark v-model="drawer" app color="grey darken-4">
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
            <v-list-item-content>
              <v-list-item-title
                v-text="nav.text"
                @click="$router.push({ path: nav.link })"
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
        <v-img
          :src="require('../assets/logotipo.png')"
          width="120"
        ></v-img>
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
    urlApi: process.env.VUE_APP_URL,
    drawer: false,
    colorToolbar: "grey darken-4",
    inscribe: null,
    navegador: [
      {
        icon: "mdi-home",
        text: "Home",
        link: "/customer",
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
    
  }),

  beforeCreate: function () {
    if (!this.$session.exists()) {
      this.$router.push("/customer/login");
    }
  },

  created: function () {
    if(this.$session.exists()){
      this.getInscribeID()
    }
  },

  mounted() {},

  methods: {
    ...mapActions(["setUserNow", "setInscribeID"]),

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
    getInscribeID: function () {
			axios
				.get(process.env.VUE_APP_URL + "getInscribeCustomers/" + this.userNow.id)
				.then((response) => {
					const resp = response.data;
          this.setInscribeID(resp.idinscribe)
          console.log(resp.idinscribe)
				});
		},
  },

  computed: {
    ...mapGetters(['userNow']),
  },
};
</script>
