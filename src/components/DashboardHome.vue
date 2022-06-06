<template>
  <div>
    <v-container class="home">
      <v-row class="justify-space-around mt-5">
        <v-col cols="6" v-for="(card, i) in cards" :key="i">
          <v-card elevation="5">
            <v-card-title
              class="blue-grey--text"
              v-text="card.title"
            ></v-card-title>
            <v-card-text>
              <v-sheet max-width="calc(100% - 32px)">
                <GChart
                  v-bind:type="card.typeChart"
                  v-bind:data="card.dataChart"
                ></GChart>
              </v-sheet>
            </v-card-text>
            <v-card-actions>
              <v-btn
                pill
                depressed
                color="blue-grey"
                class="white--text"
                @click="showActive(card.link)"
              >
                mais...</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import {GChart} from "vue-google-charts/legacy"
export default {
  name: "DashboardHome",
  components: {
    GChart
  },

  data() {
    return {
      cards: [
        {
          title: "Contratos",
          link: "contratos",
          typeChart: "ColumnChart",
          dataChart: [
            [
              "jan",
              "fev",
              "mar",
              "abr",
              "mai",
              "jun",
              "jul",
              "ago",
              "set",
              "out",
              "nov",
              "dez",
            ],
            [10, 15, 25, 19, 30, 24, 36, 20, 26, 30, 32, 35],
          ],
        },
        {
          title: "Financeiro",
          link: "financeiro",
          typeChart: "PieChart",
          dataChart: [
            ["Tipo de Lançamento", "Valor"],
            ["Receita", 15000.0],
            ["Despesa", 10000.0],
          ],
        },
        {
          title: "Contratos",
          link: "contratos",
          typeChart: "ColumnChart",
          dataChart: [
            [
              "jan",
              "fev",
              "mar",
              "abr",
              "mai",
              "jun",
              "jul",
              "ago",
              "set",
              "out",
              "nov",
              "dez",
            ],
            [10, 15, 25, 19, 30, 24, 36, 20, 26, 30, 32, 35],
          ],
        },
        {
          title: "Financeiro",
          link: "financeiro",
          typeChart: "PieChart",
          dataChart: [
            ["Tipo de Lançamento", "Valor"],
            ["Receita", 15000.0],
            ["Despesa", 10000.0],
          ],
        },
      ],
    };
  },

  mounted() {},

  methods: {},
};
</script>

<style lang="scss" scoped>
</style>