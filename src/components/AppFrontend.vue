<template>
    <v-row justify="center" class="align-center" no-gutters>
      <v-col lg="4" class="hidden-sm-and-down">
        <v-img class="mt-5" transition="scroll-x-transition"
          :src="require('../assets/undraw_mobile_apps_re_3wjf.svg')" width="350">
        </v-img>
      </v-col>
      <v-col class="d-flex align-center">
        <v-card class="transparent mt-5" elevation="0">
          <v-card-text>
            <img :src="require('../assets/logotipo_branco.png')" width="200" alt="logotipo">
            <h1 class="text-h2 white--text text-left mt-7">Vamos agora juntar os dados para entender seu evento!</h1>
          </v-card-text>
          <v-card-actions>
            <v-btn color="amber lighten-1" depressed x-large dark @click="startApp()">
              <v-icon>mdi-arrow-right-drop-circle</v-icon> Começar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex' 
  export default {
    name: 'AppFrontend',

    data: () => ({
      //
    }),

    computed: {
      ...mapGetters(['isStart']),
      
    },

    methods: {

      ...mapActions(['start']),

      startApp: function(){
        this.start()
      },

    }
    
  }
</script>
