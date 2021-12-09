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
</head>

<body>
    <!-- início do preloader -->
    <div id="preloader">
        <div class="inner">
            <!-- HTML DA ANIMAÇÃO MUITO LOUCA DO SEU PRELOADER! -->
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
                <!-- Tela 1 -->
                <v-card v-show="tela == 1">
                    <v-toolbar color="blue darken-4" class="pa-2 align-center">
                        <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                        <v-spacer></v-spacer>
                        <v-btn icon class="white--text" @click="restartApp()">
                            <v-icon>mdi-reload</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="firstScreen">
                            <v-select v-model="selectedService" class="mt-3" label="Qual tipo de evento?"
                                :items="services" item-text="name" :rules="serviceRules" return-object></v-select>
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
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn large class="white--text" depressed color="blue darken-4" @click="nextScreen()">avançar
                        </v-btn>
                    </v-card-actions>
                </v-card>
                <!-- Tela 2 -->
                <v-card v-show="tela == 2">
                    <v-toolbar color="blue darken-4" class="pa-2 align-center">
                        <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                        <v-spacer></v-spacer>
                        <v-btn icon class="white--text" @click="restartApp()">
                            <v-icon>mdi-reload</v-icon>
                        </v-btn>
                        <v-btn icon class="white--text" @click="prevScreen()">
                            <v-icon>mdi-arrow-left</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <p class="text-h6">Estas são as <b>Formações</b> que contém o(s) instrumento(s) que você
                            selecionou. Escolha uma para prosseguirmos:</p>
                        <v-alert type="error" v-show="alertSelectedFormation">Selecione uma formação</v-alert>
                        <v-item-group v-model="selectedFormation" active-class="blue darken-4"
                            @change="isSelected('selectedFormation', 'alertSelectedFormation')">
                            <v-container>
                                <v-row>
                                    <v-col cols="12" md="6" lg="3" v-for="formation in formations"
                                        :key="formation.idformation">
                                        <v-item v-slot="{active, toggle}" :value="formation">
                                            <v-card color="primary" class="white--text" max-width="300" @click="toggle">
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
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn large depressed class="white--text" color="blue darken-4" @click="nextScreen()">Avançar
                        </v-btn>
                    </v-card-actions>
                </v-card>
                <!-- Tela 3 -->
                <v-card v-show="tela == 3">
                    <v-toolbar color="blue darken-4" class="pa-2 align-center">
                        <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                        <v-spacer></v-spacer>
                        <v-btn icon class="white--text" @click="restartApp()">
                            <v-icon>mdi-reload</v-icon>
                        </v-btn>
                        <v-btn icon class="white--text" @click="prevScreen()">
                            <v-icon>mdi-arrow-left</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-sheet elevation="0" outlined rounded class="pa-5">
                            <p class="text-h5 blue--text">Dados reunidos até aqui</p>
                            <p><b class="blue--text">Evento:</b> {{dados.service.name}}</p>
                            <p><b class="blue--text">Formação:</b> {{dados.formation.name}}</p>
                        </v-sheet>
                        <p class="text-h5 blue--text">Quem será o responsável pelo cadastro?</p>
                        <v-form ref="formInscribePartOne">
                            <v-text-field v-model="inscribeAccountable" label="Nome" :rules="inscribeAccountableRules">
                            </v-text-field>
                            <v-text-field v-model="inscribeEmail" label="E-mail" :rules="inscribeEmailRules">
                            </v-text-field>
                            <v-text-field v-model="inscribePhone" label="Telefone" :rules="inscribePhoneRules"
                                v-mask="maskTel(inscribePhone)">
                            </v-text-field>
                            <v-text-field v-model="inscribeMobile" label="Celular" :rules="inscribeMobileRules"
                                v-mask="maskTel(inscribeMobile)">
                            </v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-4" class="white--text" depressed @click="nextScreen()">Avançar</v-btn>
                    </v-card-actions>
                </v-card>
                <!-- Tela 4 -->
                <v-card v-show="tela == 4">
                    <v-toolbar color="blue darken-4" class="pa-2 align-center">
                        <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                        <v-spacer></v-spacer>
                        <v-btn icon class="white--text" @click="restartApp()">
                            <v-icon>mdi-reload</v-icon>
                        </v-btn>
                        <v-btn icon class="white--text" @click="prevScreen()">
                            <v-icon>mdi-arrow-left</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
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
                                        @blur="getCEP('inscribeAddress')"></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field v-model="inscribeAddress.street" label="Logradouro"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="4">
                                    <v-text-field v-model="inscribeAddress.number" label="Número"></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field v-model="inscribeAddress.complement" label="Complemento">
                                    </v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text-field v-model="inscribeAddress.neighborhood" label="Bairro"></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field v-model="inscribeAddress.city" label="Cidade"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text-field v-model="inscribeAddress.state" label="Estado"></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field v-model="inscribeAddress.country" label="País"></v-text-field>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-4" class="white--text" depressed @click="nextScreen()">Avançar</v-btn>
                    </v-card-actions>
                </v-card>
                <!-- Tela 5 -->
                <v-card v-show="tela == 5">
                    <v-toolbar color="blue darken-4" class="pa-2 align-center">
                        <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                    </v-toolbar>
                    <v-card-text>
                        <v-sheet elevation="0" outlined rounded class="pa-5">
                            <p class="text-h5 blue--text">Resumo</p>
                            <v-divider></v-divider>
                            <p class="text-caption font-italic blue--text">Dados do evento</p>
                            <p>
                                <b class="blue--text text-overline">Evento:</b> {{dados.service.name}}
                                <!-- <p v-show="dados.service.taxas != null" class="font-weight-bold">
                                Taxas:
                                <span v-for="tax in dados.service.taxas">
                                    <v-chip class="ma-2">
                                        <span class="mr-2">{{tax.name}}</span>
                                        <span v-show="tax.type == 1">R$ {{printMoeda(tax.value)}}</span>
                                        <span v-show="tax.type == 2">R$ {{printMoeda(tax.value)}}
                                            {{tax.multiplied}}</span>
                                    </v-chip>
                                </span>
                            </p> -->

                            </p>
                            <p>
                                <b class="blue--text text-overline">Formação:</b> {{dados.formation.name}}
                                ({{dados.formation.description}})
                                <!-- </p>
                            <p class="font-weight-bold">Valor: <v-chip class="ma-2">R$
                                    {{printMoeda(dados.formation.price)}}</v-chip>
                            </p> -->
                                <v-divider></v-divider>
                            <p class="text-caption font-italic blue--text">Dados pessoais</p>
                            <p>
                                <b class="blue--text text-overline">Responsável:</b> {{dados.inscribe.accountable}}
                                <b class="blue--text text-overline ml-3">E-mail:</b> {{dados.inscribe.email}}
                                <b class="blue--text text-overline ml-3">Telefone:</b> {{dados.inscribe.phone}}
                                <b class="blue--text text-overline ml-3">Celular:</b> {{dados.inscribe.mobile}}
                            </p>
                            <p>
                                <b class="blue--text text-overline">Logradouro:</b> {{dados.address.street}}
                                <b class="blue--text text-overline ml-3">Número:</b> {{dados.address.number}}
                                <b class="blue--text text-overline ml-3">Complemento:</b> {{dados.address.complement}}
                            </p>
                            <p>
                                <b class="blue--text text-overline">Bairro:</b> {{dados.address.neighborhood}}
                                <b class="blue--text text-overline ml-3">Cidade:</b> {{dados.address.city}}
                                <b class="blue--text text-overline ml-3">CEP:</b> {{dados.address.zipcode}}
                                <b class="blue--text text-overline ml-3">Estado:</b> {{dados.address.state}}
                                <b class="blue--text text-overline ml-3">País:</b> {{dados.address.country}}
                            </p>
                            <v-divider></v-divider>
                            <p v-show="endInscribe" class="text-overline grey--text">Para finalizarmos seu cadastro
                                informe os dados do evento (neste momento, pelo menos a cidade onde acontecerá o evento). 
                                Em seguida, verifique seu e-mail, pois enviaremos os dados de acesso à sua
                                Área de Cliente. Na Área de Cliente você poderá acessar seus dados de cadastro e
                                contratar nossos serviços.</p>
                            <v-form v-show="endInscribe" ref="formEvent">
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" lg="4">
                                            <v-text-field label="Evento" v-model="eventName"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6" lg="4">
                                            <v-menu v-model="pickDateEvent" :close-on-content-click="false"
                                                :nudge-right="40" transition="scale-transition" offset-y
                                                min-width="auto">
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field v-model="eventDate" label="Data do evento"
                                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                    </v-text-field>
                                                </template>
                                                <v-date-picker v-model="eventDate" @input="pickDateEvent = false">
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col cols="12" md="6" lg="4">
                                            <v-menu ref="menu" v-model="pickTimeEvent" :close-on-content-click="false"
                                                :nudge-right="40" :return-value.sync="eventTime"
                                                transition="scale-transition" offset-y max-width="290px"
                                                min-width="290px">
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field v-model="eventTime" label="Horário do Evento"
                                                        prepend-icon="mdi-calendar-clock" readonly v-bind="attrs"
                                                        v-on="on">
                                                    </v-text-field>
                                                </template>
                                                <v-time-picker v-if="pickTimeEvent" v-model="eventTime" full-width
                                                    @click:minute="$refs.menu.save(eventTime)">
                                                </v-time-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="4" lg="2">
                                            <v-text-field label="CEP" v-model="eventAddress.zipcode" v-mask="maskCep"
                                                @blur="getCEP('eventAddress')"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="8" lg="6">
                                            <v-text-field label="Logradouro" v-model="eventAddress.street">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6" lg="2">
                                            <v-text-field label="Número" v-model="eventAddress.number">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6" lg="2">
                                            <v-text-field label="Complemento" v-model="eventAddress.complement">
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field label="Bairro" v-model="eventAddress.neighborhood">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field label="Cidade" v-model="eventAddress.city" :rules="eventAddressRules"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field label="Estado" v-model="eventAddress.state">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field label="País" v-model="eventAddress.country">
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-form>
                        </v-sheet>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-4" class="white--text" depressed @click="restartApp()">
                            <v-icon>mdi-reload</v-icon>recomeçar
                        </v-btn>
                        <v-btn v-show="!endInscribe" color="blue darken-4" class="white--text" depressed
                            @click="endInscribe = true">
                            <v-icon>mdi-exit-to-app</v-icon>cadastrar-se
                        </v-btn>
                        <v-btn v-show="endInscribe" color="blue darken-4" class="white--text" depressed
                            @click="endScreen()">
                            <v-icon>mdi-exit-to-app</v-icon>finalizar
                        </v-btn>
                    </v-card-actions>
                </v-card>
                <!-- Tela 6 - Final -->
                <v-card v-show="tela == 6">
                    <v-toolbar color="blue darken-4" class="pa-2 align-center">
                        <img src="assets/img/logotipo_branco.png" width="200" alt="logotipo">
                    </v-toolbar>
                    <v-card-text>
                        <h3 class="blue--text text-h3">
                            {{_.toString(_.split(dados.inscribe.accountable, ' ', 1))}},<br>obrigado por se inscrever!
                        </h3>
                        <v-card outlined rounded class="mt-5">
                            <v-card-text>
                                <p class="text-body-1">Enviamos um e-mail com seu <span
                                        class="blue--text font-weight-bold">login</span> e <span
                                        class="blue--text font-weight-bold">senha</span> para acessar sua <span
                                        class="blue--text font-weight-bold">área de cliente</span></a>. Lá você poderá
                                    dar maiores informações sobre seu evento e preencher uma proposta de contrato dos
                                    serviços Toque Divino.</p>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn depressed large color="blue darken-4" class="white--text" @click="endApp()">
                                    <v-icon>mdi-web</v-icon> Recomeçar
                                </v-btn>
                                <v-btn depressed large color="blue darken-4" class="white--text"
                                    @click="redirect('https://toquedivino.com/divine/customers')">
                                    <v-icon color="white">mdi-link-box</v-icon> Acessar a Área de Cliente
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-card-text>
                </v-card>
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