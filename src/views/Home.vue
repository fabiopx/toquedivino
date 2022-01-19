<template>
  <v-container>
    <app-frontend v-show="!start" />
    <v-btn v-show="!start" class="mt-2" color="amber lighten-1" fab large dark bottom right v-on:click="startApp">
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

  export default {
    name: 'Home',

    data: () => ({
      start: false,
      tela: 1
    }),

   methods: {
      startApp() {
        this.start = true
        localStorage.start = this.start
        localStorage.tela = this.tela
      },

      restartApp(){
        this.start = false
        localStorage.removeItem('start')
        localStorage.removeItem('services')
        localStorage.removeItem('instruments')
        localStorage.removeItem('formations')
        localStorage.removeItem('tela')
        localStorage.removeItem('dados')
        
    },
    },

    components: {
      AppFrontend,
      AppFrontendSteppers,
    },

    mounted() {
      if(localStorage.start){
        this.start = localStorage.start
      }

      if(localStorage.tela){
        this.tela = localStorage.tela
      } else{
        localStorage.tela = this.tela
      }
    },
    
  }
</script>
