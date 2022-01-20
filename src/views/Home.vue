<template>
  <v-container>
    <app-frontend v-show="!start" />
    <v-btn v-show="!start" class="mt-2" color="amber lighten-1" fab large dark bottom right @click="startApp">
      <v-icon>mdi-arrow-right-drop-circle</v-icon>
    </v-btn>
    <app-frontend-steppers v-show="start"></app-frontend-steppers>
    <v-btn v-show="start" color="amber ligthen-1" class="mt-2" fab large dark bottom left v-on:click="restartApp()">
      <v-icon>mdi-restart</v-icon>
    </v-btn>
  </v-container>
  
</template>

<script>
  import AppFrontend from '../components/AppFrontend'
  import AppFrontendSteppers from '../components/AppFrontendSteppers'
  import {mapGetters, mapActions} from 'vuex' 

  export default {
    name: 'Home',

    data: () => ({
      //
    }),
    

    computed: {
      ...mapGetters(['tela', 'start']),
    },


   methods: {
     ...mapActions(['getServices']),

      startApp() {
        console.log('start')
      },

      restartApp(){
        console.log('restart')
      },
    },

    components: {
      AppFrontend,
      AppFrontendSteppers,
    },

    mounted() {
      //
    },
    
  }
</script>
