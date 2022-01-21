<template>
  <v-container>
    
    <app-frontend v-show="!isStart" />
    <v-btn v-show="!isStart" class="mt-2 float-right" color="amber lighten-1" depressed x-large dark bottom right @click="startApp()">
      <v-icon>mdi-arrow-right-drop-circle</v-icon> Começar
    </v-btn>
    
    <app-frontend-steppers v-show="isStart"></app-frontend-steppers>
    <v-btn v-show="isStart" color="amber ligthen-1" class="mt-2"  depressed x-large dark bottom left @click="restartApp()">
      <v-icon>mdi-restart</v-icon> Recomeçar
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
      ...mapGetters(['isStart']),
      
    },


   methods: {
     ...mapActions(['start', 'restart']),
      startApp: function(){
        this.start()
      },
      restartApp: function(){
        this.restart()
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
