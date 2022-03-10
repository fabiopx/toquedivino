<template>
  <div>
    <v-container>
      <v-card>
        <v-toolbar color="grey darken-4" dark>
            <v-icon class="mr-2">mdi-playlist-check</v-icon> Dados reunidos até aqui...
        </v-toolbar>
        <v-card-text>
          <v-list>
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
          <v-sheet>
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
          </v-sheet>
        </v-card-text>
        <v-card-actions>
          <v-btn color="grey darken-4" depressed dark><v-icon class="mr-2">mdi-account-cash</v-icon> Realizar orçamento</v-btn>
          <v-btn color="grey darken-4" depressed dark>Editar cadastro</v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'

export default {
  data: () => ({
    inscribe: "",
  }),

  methods: {
      ...mapActions(["setInscribeID"]),
      getInscribe: async function(){
          const response = await axios.get(process.env.VUE_APP_URL + "getInscribeCustomers/" + this.userNow.id)
          this.inscribe = response.data
      }
  },

  computed: {
      ...mapGetters(["inscribeID", "userNow"]),
  },

  created: function(){
      this.getInscribe()
  }
};
</script>