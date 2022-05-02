<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <h2><v-icon class="mr-3">mdi-file-sign</v-icon> Contrato</h2>
            </v-toolbar>
            <v-card-text>
              <v-sheet
                outlined
                rounded
                class="d-flex justify-space-around align-center"
              >
                <v-img
                  max-width="200"
                  :src="require('../assets/undraw_contract_re_ves9.svg')"
                ></v-img>
                <div v-if="isBudget">
                  <h1 class="red--text darken-4 h2">
                    Você ainda não gerou seu contrato.
                  </h1>
                  <p v-if="isBudget && budget.status == 1" class="subtitle-1 mt-3">
                    Existe um orçamento ativo na sua conta, você já pode gerar
                    seu contrato.
                  </p>
                </div>
                <div v-if="!isAgreement && !isBudget">
                  <h1 class="red--text darken-4 h2">Faça um orçamento</h1>
                  <p class="subtitle-1">
                    Não há nenhum orçamento realizado em sua conta. Faça um
                    orçamento:
                  </p>
                </div>
              </v-sheet>
              <v-sheet v-if="!isAgreement" class="mt-3">
                <v-btn
                  color="red darken-4"
                  class="pa-8 mr-3"
                  dark
                  @click="$router.push('/customer/budget')"
                  ><v-icon class="mr-3">mdi-file-document-edit</v-icon>Acessar
                  orçamento</v-btn
                >
                <v-btn
                  v-if="isBudget && budget.status == 1"
                  color="red darken-4"
                  class="pa-8"
                  dark
                  ><v-icon class="mr-3">mdi-text-box-plus</v-icon>Gerar
                  contrato</v-btn
                >
              </v-sheet>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
  }),
  methods: {
    ...mapActions(["setInscribeID", "setUserNow", "setBudgetActive"]),
  },
  computed: {
    ...mapGetters([
      "inscribeID",
      "userNow",
      "isAgreement",
      "isBudget",
      "budget",
    ]),
  },
  created: async function () {
    await this.setBudgetActive();
  },
};
</script>