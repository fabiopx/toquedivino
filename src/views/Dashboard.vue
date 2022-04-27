<template>
  <div>
    <v-navigation-drawer v-model="drawer" color="blue-grey" app>
      <v-list>
        <v-list-item class="px-2" dark>
          <v-list-item-avatar>
            <v-img :src="userNow.photo"></v-img>
          </v-list-item-avatar>
          <v-list-item-title>{{ userNow.name }}</v-list-item-title>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <v-list nav dense dark v-for="item in pages" :key="item.id">
        <v-list-item v-if="!item.subgroup" v-model="selectedPage" link>
          <v-list-item-icon>
            <v-icon v-text="item.icon"></v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title
              class="text-uppercase"
              v-text="item.text"
              @click="$router.push({path: item.link})"
            >
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-group v-else :prepend-icon="item.icon" color="white" no-action>
          <template v-slot:activator>
            <v-list-item-content>
              <v-list-item-title
                class="text-uppercase"
                dark
                v-text="item.text"
              ></v-list-item-title>
            </v-list-item-content>
          </template>
          <v-list-item
            v-for="sub in item.subgroups"
            :key="sub.title"
            v-model="selectedPage"
            link
          >
            <v-list-item-content>
              <v-list-item-title
                v-text="sub.title"
                @click="$router.push({path: sub.link})"
              >
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar color="blue-grey" app>
      <v-app-bar-nav-icon dark @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="white--text">
        <v-img
          max-height="61"
          max-width="180"
          :src="require('../assets/logotipo_branco.png')"
        ></v-img>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon color="white" @click="logout()">mdi-logout</v-icon>
      </v-btn>
    </v-app-bar>
    <v-main>
      <router-view></router-view>
    </v-main>
  </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex";
export default {
  name: "dashboard",

  data() {
    return {
      drawer: false,
      pages: [
        {
          id: 1,
          text: "Home",
          icon: "mdi-home",
          link: "/dashboard/home",
          subgroup: false,
        },
        {
          id: 2,
          text: "Usuários",
          icon: "mdi-account-circle",
          link: "/dashboard/users",
          subgroup: false,
        },
        {
          id: 3,
          text: "Serviços",
          icon: "mdi-bookmark-music",
          link: null,
          subgroup: true,
          subgroups: [
            {
              title: "Taxas",
              link: "/dashboard/taxes",
            },
            {
              title: "Serviços",
              link: "/dashboard/services",
            },
          ],
        },
        {
          id: 4,
          text: "Formações",
          icon: "mdi-shape-plus",
          link: null,
          subgroup: true,
          subgroups: [
            {
              title: "Instrumentos",
              link: "/dashboard/instruments",
            },
            {
              title: "Formações",
              link: "/dashboard/formations",
            },
          ],
        },
        {
          id: 5,
          text: "Repertório",
          icon: "mdi-music",
          link: null,
          subgroup: true,
          subgroups: [
            {
              title: "Momentos",
              link: "/dashboard/moments",
            },
            {
              title: "Músicas",
              link: "/dashboard/musics",
            },
          ],
        },
        {
          id: 6,
          text: "Gerencial",
          icon: "mdi-file-document-edit",
          link: null,
          subgroup: true,
          subgroups: [
            {
              title: "Leads",
              link: "/dashboard/leads",
            },
            {
              title: "Cadastros",
              link: "/dashboard/inscribes",
            },
            {
              title: "Contratos",
              link: "/dashboard/contracts",
            },
            {
              title: "Assinaturas",
              link: "/dashboard/signatures",
            },
          ],
        },
      ],
      selectedPage: 0,
    };
  },

  mounted() {},

  methods: {
    ...mapActions(["setLoginNow"]),
  },
  computed: {
    ...mapGetters(["loginNow"]),
  }
};
</script>