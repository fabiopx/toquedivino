<template>
  <v-container>
    <app-frontend v-show="!start" />
    <v-btn v-show="!start" class="mt-2 white--text" depressed color="amber lighten-1" large v-on:click="startApp">
      <v-icon>mdi-arrow-right-circle</v-icon> Começar
    </v-btn>
    <app-frontend-steppers v-show="start"></app-frontend-steppers>
  </v-container>
  
</template>

<script>
  import AppFrontend from '../components/AppFrontend'
  import AppFrontendSteppers from '../components/AppFrontendSteppers'

  export default {
    name: 'Home',

    data: () => ({
      start: false,
      services: [],
    }),

   methods: {
      startApp() {
        this.start = true
        localStorage.start = this.start
      },
    },

    components: {
      AppFrontend,
      AppFrontendSteppers,
    },
  }
</script>
