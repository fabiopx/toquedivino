<template>
  <div>
    <v-container class="home">
      <v-row class="justify-space-around mt-5">
        <v-col cols="12" md="6" v-for="(card, i) in cards" :key="i">
          <v-card elevation="5" height="400">
            <v-card-title
              class="blue-grey--text"
              v-text="card.title"
            ></v-card-title>
            <v-card-text>
              <v-sheet max-width="calc(100% - 32px)" height="250">
                <v-img :src="card.img"></v-img>
              </v-sheet>
            </v-card-text>
            <v-card-actions>
              <v-btn
                pill
                depressed
                color="blue-grey"
                class="white--text"
                @click="$router.push(card.link)"
              >
                acessar</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardHome",

  data() {
    return {
      imgPath: process.env.VUE_APP_IMGPATH,
      cards: [
        {
          title: "Usuários",
          link: "/dashboard/users",
          img: process.env.VUE_APP_IMGPATH + "undraw_usability_testing_re_uu1g.svg"
        },
        {
          title: "Serviços",
          link: "/dashboard/services",
          img: process.env.VUE_APP_IMGPATH + "undraw_operating_system_re_iqsc.svg"
        },
        {
          title: "Formações",
          link: "/dashboard/formations",
          img: process.env.VUE_APP_IMGPATH + "undraw_compose_music_re_wpiw.svg"
        },
        {
          title: "Gerencial",
          link: "/dashboard/contracts",
          img: process.env.VUE_APP_IMGPATH + "undraw_manage_chats_re_0yoj.svg"
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