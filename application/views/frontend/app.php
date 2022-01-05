<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Allura&family=Caveat&family=Dawning+of+a+New+Day&family=Fuggles&family=Kristi&family=League+Script&family=Mrs+Saint+Delafield&family=Parisienne&family=Pinyon+Script&family=Reenie+Beanie&family=Sacramento&family=Shadows+Into+Light&family=Zeyada&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/fonts.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/vue-loading.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/app.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <title>Toque Divino APP</title>
</head>

<body>
    <!-- início do preloader -->
    <div id="preloader">
        <div class="inner">
            <!-- HTML DA ANIMAÇÃO DO PRELOADER! -->
            <div class="text-center">
                <img src="assets/img/loading-1.svg" width="150" alt="loading"><br>
                <img class="img-fluid" width="200" src="<?php echo base_url('assets/img/logotipo_branco.png'); ?>"
                    alt="Logotipo Toque Divino">
            </div>
            <!-- <div class="bolas">
                <div></div>
                <div></div>
                <div></div>
            </div> -->
        </div>
    </div>
    <!-- fim do preloader -->

    <v-app id="app">
        <loading :active.sync="loadingVisible" :can-cancel="true"></loading>
        <v-main class="deep-purple bg-app">
            <!-- Entrada App -->
            <v-container v-show="!start">
                <v-row justify="center" class="align-center" no-gutters>
                    <v-col lg="4" class="hidden-sm-and-down">
                        <v-img class="mt-5" transition="scroll-x-transition"
                            src="assets/img/undraw_Sync_files_re_ws4c.svg" width="350"></v-img>
                    </v-col>
                    <v-col class="d-flex align-center">
                        <v-card class="transparent mt-5" elevation="0">
                            <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                            <h1 class="text-h2 white--text text-left mt-7">Vamos agora juntar os dados para entender seu
                                evento!</h1>
                            <v-btn class="mt-2 white--text" depressed color="amber lighten-1" large @click="startApp()">
                                <v-icon>mdi-arrow-right-circle</v-icon> Começar
                            </v-btn>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <!-- Reunir Dados: telas-->
            <v-container v-show="start">
                <v-stepper v-model="tela">
                    <v-stepper-header>
                        <v-stepper-step :complete="tela > 1" step="1">Qual o evento?</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 2" step="2">Selecione os instrumentos</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 3" step="3">Selecione uma formação</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 4" step="4">Quem será responsável?</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 5" step="5">Dados complementares</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step step="6">Finalizando</v-stepper-step>
                    </v-stepper-header>
                    <v-stepper-items>
                        <v-stepper-content step="1">
                            <v-row>
                                <v-col>
                                    <v-img src="assets/img/undraw_selection_re_ycpo.svg" max-width="500"></v-img>
                                </v-col>
                                <v-col>
                                    <v-form ref="firstScreen">
                                        <v-select v-model="selectedService" outlined class="mt-3"
                                            label="Selecione o evento" :items="services" item-text="name"
                                            :rules="serviceRules" class="align-center" return-object>
                                        </v-select>
                                    </v-form>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="restartApp()">Recomeçar</v-btn>
                                    <v-btn depressed color="primary" @click="nextScreen()">
                                        Continuar
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content>
                        <v-stepper-content step="2">
                            <p class="text-h6">Quais instrumentos você gostaria que tocasse no seu evento?</p>
                            <v-alert type="error" v-show="alertSelectedInstruments">Selecione ao menos um instrumento
                            </v-alert>
                            <v-item-group v-model="selectedInstruments" multiple>
                                <v-container>
                                    <v-row>
                                        <v-col v-for="instrument in instruments" :key="instrument.idinstrument">
                                            <v-item v-slot="{active, toggle}" :value="instrument.idinstrument">
                                                <v-card outlined :color="active ? 'blue-grey' : ''"
                                                    class="d-flex align-center pa-3" width="130" height="130"
                                                    @click="toggle">
                                                    <v-scroll-x-transition>
                                                        <div v-show="!active" class="text-h2 flex-grow-1 text-center">
                                                            <v-img :src="instrument.image" width="40" class="ma-2">
                                                            </v-img>
                                                            <p class="grey--text text-left text-body-1">
                                                                {{instrument.name}}</p>
                                                        </div>
                                                    </v-scroll-x-transition>
                                                    <v-scroll-x-transition>
                                                        <div v-if="active" class="text-h2 flex-grow-1 text-center">
                                                            <v-img :src="instrument.image" width="40" class="ma-2">
                                                            </v-img>
                                                            <p class="white--text text-left text-body-1">
                                                                {{instrument.name}}</p>
                                                        </div>
                                                    </v-scroll-x-transition>
                                                </v-card>
                                            </v-item>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-item-group>
                        </v-stepper-content>
                        <v-stepper-content step="3">

                        </v-stepper-content>
                        <v-stepper-content step="4">

                        </v-stepper-content>
                        <v-stepper-content step="5">

                        </v-stepper-content>
                        <v-stepper-content step="6">

                        </v-stepper-content>
                    </v-stepper-items>
                </v-stepper>
            </v-container>
        </v-main>
    </v-app>

    <!-- Módulos CDN -->
    <script src="<?php echo base_url('assets/js/vue.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
    <!-- <script src="<?php echo base_url('assets/js/vuetify.js'); ?>"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script src="<?php echo base_url('assets/js/v-mask.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue-ip.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue-currency-input.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/sweetalert2.all.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue2-storage.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/vue-google-charts/dist/vue-google-charts.browser.js'); ?>"></script>
    <script type="text/javascript"
        src="<?php echo base_url('node_modules/vuetify-upload-button/dist/vuetify-upload-button.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue-loading-overlay.min.js'); ?>"></script>

    <script src="<?php echo base_url('node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/@ckeditor/ckeditor5-vue2/dist/ckeditor.js'); ?>"></script>

    <script src="<?php echo base_url('assets/js/jquery-2.1.3.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/bootstrap/dist/js/bootstrap.bundle.js'); ?>"></script>

    <script>
    //<![CDATA[
    $(window).on('load', function() {
        $('#preloader .inner').fadeOut();
        $('#preloader').delay(350).fadeOut('slow');
        $('body').delay(350).css({
            'overflow': 'visible'
        });
    })
    //]]>

    // window.onbeforeunload = function() {
    //     return "Não será apresentado na tela";
    // }
    </script>
    <script type="module" src="<?php echo base_url('assets/js/app.js'); ?>"></script>
</body>

</html>