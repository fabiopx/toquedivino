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
                            <v-row>
                                <v-col>
                                    <p class="text-h6">Quais instrumentos você gostaria que tocasse no seu evento?</p>
                                    <v-alert type="error" v-show="alertSelectedInstruments">Selecione ao menos um
                                        instrumento
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
                                                                <div v-show="!active"
                                                                    class="text-h2 flex-grow-1 text-center">
                                                                    <v-img :src="instrument.image" width="40"
                                                                        class="ma-2">
                                                                    </v-img>
                                                                    <p class="grey--text text-left text-body-1">
                                                                        {{instrument.name}}</p>
                                                                </div>
                                                            </v-scroll-x-transition>
                                                            <v-scroll-x-transition>
                                                                <div v-if="active"
                                                                    class="text-h2 flex-grow-1 text-center">
                                                                    <v-img :src="instrument.image" width="40"
                                                                        class="ma-2">
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
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="prevScreen()">Voltar</v-btn>
                                    <v-btn depressed color="primary" @click="nextScreen()">Continuar</v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content>
                        <v-stepper-content step="3">
                            <v-row>
                                <v-col>
                                    <p class="text-h6">Estas são as <b>Formações</b> que contém o(s) instrumento(s) que
                                        você
                                        selecionou. Escolha uma para prosseguirmos:</p>
                                    <v-alert type="error" v-show="alertSelectedFormation">Selecione uma formação
                                    </v-alert>
                                    <v-item-group v-model="selectedFormation" active-class="blue darken-4"
                                        @change="isSelected('selectedFormation', 'alertSelectedFormation')">
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" md="6" lg="3" v-for="formation in formations"
                                                    :key="formation.idformation">
                                                    <v-item v-slot="{active, toggle}" :value="formation">
                                                        <v-card color="primary" class="white--text" max-width="300"
                                                            @click="toggle">
                                                            <v-icon class="mt-2 ml-2 white--text">
                                                                {{active ? 'mdi-check-circle' : 'mdi-checkbox-blank-circle-outline'}}
                                                            </v-icon>
                                                            <!-- <v-spacer></v-spacer> -->

                                                            <v-card-title>{{formation.name}}</v-card-title>

                                                            <v-card-actions>
                                                                <v-btn icon color="white" class="mt-2 ml-2"
                                                                    title="Assista um vídeo desta formação"
                                                                    @click="openDialogFormationVideo(formation)">
                                                                    <v-icon>mdi-youtube</v-icon>
                                                                </v-btn>

                                                                <v-btn icon color="white" class="mt-2 ml-2"
                                                                    @click="openDialogFormationTooltip(formation)">
                                                                    <v-icon>mdi-tooltip-plus</v-icon>
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-item>
                                                </v-col>
                                            </v-row>
                                            <v-dialog v-model="dialogVideoFormation" max-width="600" persistent>
                                                <v-card>
                                                    <v-card>
                                                        <v-toolbar color="primary" dark> {{formation.name}}
                                                        </v-toolbar>
                                                        <v-card-text>
                                                            <iframe width="560" height="315" :src="formation.video"
                                                                title="YouTube video player" frameborder="0"
                                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                allowfullscreen></iframe>
                                                        </v-card-text>
                                                        <v-card-actions class="justify-end">
                                                            <v-btn text @click="closeDialogFormationVideo()">
                                                                Close
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-card>
                                            </v-dialog>
                                            <v-dialog v-model="dialogTooltipFormation" max-width="600" persistent>
                                                <v-card>
                                                    <v-card>
                                                        <v-toolbar color="primary" dark> {{formation.name}}
                                                        </v-toolbar>
                                                        <v-card-text>
                                                            {{formation.description}}
                                                        </v-card-text>
                                                        <v-card-actions class="justify-end">
                                                            <v-btn text @click="closeDialogFormationTooltip()">
                                                                Close
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-card>
                                            </v-dialog>
                                        </v-container>
                                    </v-item-group>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="prevScreen()">Voltar</v-btn>
                                    <v-btn depressed color="primary" @click="nextScreen()">Continuar</v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content>
                        <v-stepper-content step="4">
                            <v-row>
                                <v-col>
                                    <v-sheet elevation="0" outlined rounded class="pa-5">
                                        <p class="text-h5 blue--text">Dados reunidos até aqui</p>
                                        <p><b class="blue--text">Evento:</b> {{dados.service.name}}</p>
                                        <p><b class="blue--text">Formação:</b> {{dados.formation.name}}</p>
                                    </v-sheet>
                                    <p class="text-h5 blue--text">Quem será o responsável pelo cadastro?</p>
                                    <v-form ref="formInscribePartOne">
                                        <v-text-field v-model="inscribeAccountable" label="Nome"
                                            :rules="inscribeAccountableRules">
                                        </v-text-field>
                                        <v-text-field v-model="inscribeEmail" label="E-mail"
                                            :rules="inscribeEmailRules">
                                        </v-text-field>
                                        <v-text-field v-model="inscribePhone" label="Telefone"
                                            v-mask="maskTel(inscribePhone)">
                                        </v-text-field>
                                        <v-text-field v-model="inscribeMobile" label="Celular"
                                            :rules="inscribeMobileRules" v-mask="maskTel(inscribeMobile)">
                                        </v-text-field>
                                    </v-form>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="prevScreen()">Voltar</v-btn>
                                    <v-btn depressed color="primary" @click="nextScreen()">Continuar</v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content>
                        <v-stepper-content step="5">
                            <v-row>
                                <v-col>
                                    <v-sheet elevation="0" outlined rounded class="pa-5">
                                        <p class="text-h6 blue--text">Dados reunidos até aqui</p>
                                        <p><b class="blue--text">Evento:</b> {{dados.service.name}}</p>
                                        <p><b class="blue--text">Formação:</b> {{dados.formation.name}}</p>
                                        <p>
                                            <b class="blue--text">Responsável:</b> {{dados.inscribe.accountable}}
                                            <b class="blue--text ml-3">E-mail:</b> {{dados.inscribe.email}}
                                            <b class="blue--text ml-3">Telefone:</b> {{dados.inscribe.phone}}
                                            <b class="blue--text ml-3">Celular:</b> {{dados.inscribe.mobile}}
                                        </p>
                                    </v-sheet>
                                    <p class="text-h6 blue--text mt-3">Dados pessoais: endereço</p>
                                   
                                    <v-form ref="formInscribePartTwo">
                                        <v-row>
                                            <v-col cols="2">
                                                <v-text-field v-model="inscribeAddress.zipcode" label="CEP"
                                                    @keyup="queryHere(inscribeAddress.zipcode)"></v-text-field>
                                            </v-col>
                                            <v-col>
                                                <v-text-field v-model="inscribeAddress.street" label="Logradouro">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="4">
                                                <v-text-field v-model="inscribeAddress.number" label="Número">
                                                </v-text-field>
                                            </v-col>
                                            <v-col>
                                                <v-text-field v-model="inscribeAddress.complement" label="Complemento">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-text-field v-model="inscribeAddress.neighborhood" label="Bairro">
                                                </v-text-field>
                                            </v-col>
                                            <v-col>
                                                <v-text-field v-model="inscribeAddress.city" label="Cidade">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-text-field v-model="inscribeAddress.state" label="Estado">
                                                </v-text-field>
                                            </v-col>
                                            <v-col>
                                                <v-text-field v-model="inscribeAddress.country" label="País">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="prevScreen()">Voltar</v-btn>
                                    <v-btn depressed color="primary" @click="nextScreen()">Pular etapa</v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content>
                        <v-stepper-content step="6">
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="prevScreen()">Voltar</v-btn>
                                    <v-btn depressed color="primary" >Finalizar</v-btn>
                                </v-col>
                            </v-row>
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