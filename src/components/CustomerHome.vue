<template>
  <div>
    <v-container v-show="!isAgreement">
      <v-card>
        <v-toolbar color="grey darken-4" dark>
          <v-icon class="mr-2">mdi-playlist-check</v-icon> Dados reunidos até
          aqui...
        </v-toolbar>
        <v-card-text>
          <v-skeleton-loader
            v-show="loadingData1"
            type="sentences"
          ></v-skeleton-loader>
          <v-list v-show="!loadingData1">
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <b>Formação:</b> {{ inscribe.formation.name }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <b>Serviço:</b> {{ inscribe.service.name }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <v-skeleton-loader
            v-show="loadingData2"
            type="article"
          ></v-skeleton-loader>
          <v-sheet v-show="!loadingData2" class="ml-4 mt-4">
            <h2 class="mb-3">Dados pessoais</h2>
            <p><b>Nome:</b> {{ inscribe.accountable }}</p>
            <p><b>Telefone:</b> {{ inscribe.phone }}</p>
            <p><b>Celular:</b> {{ inscribe.mobile }}</p>
            <p>
              <b>Endereço:</b> {{ inscribe.address.street }},
              {{ inscribe.address.number }}
              {{
                inscribe.address.complement
                  ? ", " + inscribe.address.complement + ","
                  : ""
              }}
              {{ inscribe.address.neighborhood }}, {{ inscribe.address.city }} -
              {{ inscribe.address.state }}, {{ inscribe.address.zipcode }},
              {{ inscribe.address.country }}
            </p>
            <v-divider></v-divider>
            <p class="mt-4"><v-icon color="grey darken-4" class="mr-2">mdi-step-forward</v-icon>O próximo passo é oferecer maiores informações sobre o evento e realizar o orçamento.</p>
          </v-sheet>
        </v-card-text>
        <v-card-actions>
          <v-btn v-show="isEvent" color="grey darken-4" depressed dark
            ><v-icon class="mr-2">mdi-account-cash</v-icon> Realizar
            orçamento</v-btn
          >
          <v-btn color="grey darken-4" depressed dark class="ml-4"
            @click="$router.push('/customer/inscribe')"><v-icon class="mr-2">mdi-pencil</v-icon
            >Cadastrar evento</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data: () => ({
    inscribe: {formation: "", service: "", address:""},
    loadingData1: false,
    loadingData2: false,
    isEvent: false
  }),

  methods: {
    ...mapActions(["setInscribeID", "setUserNow"]),
    getInscribe: function () {
      this.loadingData1 = true;
      this.loadingData2 = true;
      axios
        .get(
          process.env.VUE_APP_URL + "getInscribeCustomers/" + this.userNow.id
        )
        .then((response) => {
          this.inscribe = response.data;
          this.loadingData1 = false;
          this.loadingData2 = false;
        });
    },
    verifyEvent: function(){
      axios.get(process.env.VUE_APP_URL + "getEventCustomers/" + this.inscribeID)
      .then((response) => {
        const resp = response.data
        (resp) ? this.isEvent = true : this.isEvent = false
      })
    }

  },

  computed: {
    ...mapGetters(["inscribeID", "userNow", "isAgreement"]),
  },

  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    this.getInscribe();
  },
};
</script>