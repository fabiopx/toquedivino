<template>
  <v-container>
    <app-frontend v-show="!start" />
    <v-btn v-show="!start" class="mt-2 white--text" depressed color="amber lighten-1" large @click="startApp()">
      <v-icon>mdi-arrow-right-circle</v-icon> Começar
    </v-btn>
  </v-container>
  
</template>

<script>
  import AppFrontend from '../components/AppFrontend'

  export default {
    name: 'Home',

    data: () => ({
      start: false,
    }),

    methods: {
      starApp() {
        this.start = true
        localStorage.start = this.start
        this.getServices()
        this.getInstruments()
      },
      getServices(){
        axios.get(this.urlApi + 'getServices')
        .then(response => {
            this.services = response.data
            localStorage.setItem('services', JSON.stringify(this.services))
            this.loadingVisible =  false
        })
      }
    },

    components: {
      AppFrontend,
    },
  }
</script>
