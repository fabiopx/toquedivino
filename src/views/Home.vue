<template>
  <v-container>
    <v-row>
      <v-col>
        <app-frontend v-show="!isStart" />
        

        <app-frontend-steppers v-show="isStart"></app-frontend-steppers>
        
      </v-col>
    </v-row>
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
