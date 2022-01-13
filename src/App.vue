<template>
  <v-app>
    <v-main class="deep-purple bg-app">
      <router-view/>
    </v-main>
  </v-app>
</template>

<script>

export default {
  name: 'App',

  data: () => ({
    //
  }),

 
   
};
</script>

<style scoped>
.bg-app{
  background-image: url('assets/bg-app-1.png');
  background-size: cover;
  background-attachment: fixed;
}
</style>
