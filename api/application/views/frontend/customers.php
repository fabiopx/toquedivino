<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Allura&family=Caveat&family=Dawning+of+a+New+Day&family=Fuggles&family=Kristi&family=League+Script&family=Mrs+Saint+Delafield&family=Parisienne&family=Pinyon+Script&family=Reenie+Beanie&family=Sacramento&family=Shadows+Into+Light&family=Zeyada&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/fonts.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/vue-loading.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/customers.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">

    <title><?php echo $title; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>

<body>
    <!-- início do preloader -->
    <div id="preloader">
        <div class="inner">
            <!-- PRELOADER -->
            <div class="text-center">
                <img src="assets/img/loading-1.svg" width="150" alt="loading"><br>
                <img class="img-fluid" width="200" src="<?php echo base_url('assets/img/logotipo_branco.png'); ?>" alt="Logotipo Toque Divino">
            </div>
        </div>
    </div>
    <!-- fim do preloader -->
    <v-app id="customers">
        <v-dialog v-model="userNow.login" fullscreen persistent transition="dialog-transition">
            <div id="telaLogin" class="d-flex justify-center align-center deep-purple bg-app">
                <v-card width="400" height="300" color="blue darken-4">
                    <v-toolbar elevation="0" color="blue darken-4">
                        <v-toolbar-title>
                            <v-img src="assets/img/logotipo_branco.png" width="150"></v-img>
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="formLogin">
                            <v-text-field v-model="loginEmail" label="Login" dark :rules="loginEmailRules">
                            </v-text-field>
                            <v-text-field v-model="loginPassword" label="Senha" dark :rules="loginPasswordRules" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :type="showPassword ? 'text' : 'password'" @click:append="showPassword = !showPassword">
                            </v-text-field>
                        </v-form>

                    </v-card-text>
                    <v-card-actions>
                        <v-btn depressed large block color="orange white--text" @click="enterLogin()">Entrar
                        </v-btn>
                    </v-card-actions>
                </v-card>
                <v-alert id="alert" border="left" v-show="alertLogin.status" type="error">{{alertLogin.msg}}</v-alert>
            </div>
        </v-dialog>
        <v-navigation-drawer dark v-model="drawer" app :color="colorToolbar">
            <v-list dense>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img :src="accountPhoto"></v-img>
                    </v-list-item-avatar>
                </v-list-item>
                <v-menu offset-y>
                    <template v-slot:activator="{on, attrs}">
                        <v-list-item link v-bind="attrs" v-on="on">
                            <v-list-item-content>
                                <v-list-item-title class="text-h6">
                                    {{accountName}}
                                </v-list-item-title>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-icon>mdi-menu-down</v-icon>
                            </v-list-item-action>
                        </v-list-item>
                    </template>
                    <v-list>
                        <v-list-item link @click="logout()">
                            <v-icon>mdi-logout</v-icon> Sair
                        </v-list-item>
                    </v-list>
                </v-menu>

            </v-list>
            <v-divider></v-divider>
            <v-list nav dense>
                <v-list-item-group>
                    <v-list-item v-for="(nav, i) in navegador" :key="nav.link" link>
                        <v-list-item-icon>
                            <v-icon v-text="nav.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="nav.text" @click.stop="showActive(nav.link)">
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app :color="colorToolbar">
            <v-app-bar-nav-icon @click="drawer = !drawer" color="white"></v-app-bar-nav-icon>

            <v-toolbar-title>
                <v-img src="assets/img/logotipo_branco.png" width="120"></v-img>
            </v-toolbar-title>
        </v-app-bar>

        <v-main>
            <v-container v-show="isActive('home')" fluid>
                <v-row>
                    <v-col>

                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="6" lg="3">
                        <v-card>
                            <v-card-title>
                                <v-img src="assets/img/undraw_Account_re_o7id.svg" height="250"></v-img>
                            </v-card-title>

                            <v-card-text class="blue--text text-h6">
                                <v-icon color="blue">mdi-folder-information</v-icon> Dados de Acesso
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn depressed large color="blue" class="white--text" @click="showActive('account')">
                                    Gerenciar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="6" lg="3">
                        <v-card>
                            <v-card-title>
                                <v-img src="assets/img/undraw_Annotation_re_h774.svg" height="250"></v-img>
                            </v-card-title>
                            <v-card-text class="teal--text text-h6">
                                <v-icon color="teal">mdi-account</v-icon> Cadastro
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn depressed large color="teal" class="white--text" @click="showActive('inscribe')">
                                    Gerenciar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="6" lg="3">
                        <v-card>
                            <v-card-title>
                                <v-img src="assets/img/undraw_compose_music_ovo2.svg" height="250"></v-img>
                            </v-card-title>
                            <v-card-text class="amber--text text--darken-3 text-h6">
                                <v-icon color="amber darken-3">mdi-file-document-edit</v-icon> Repertório
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn depressed large color="amber darken-3" class="white--text" @click="showActive('repertory')">
                                    Gerenciar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="6" lg="3">
                        <v-card>
                            <v-card-title>
                                <v-img src="assets/img/undraw_Agreement_re_d4dv.svg" height="250"></v-img>
                            </v-card-title>
                            <v-card-text class="red--text text--darken-3 text-h6">
                                <v-icon color="red darken-3">mdi-file-document-edit</v-icon> Contrato
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn depressed large color="red darken-3" class="white--text" @click="showActive('agreement')">
                                    Gerenciar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-container v-show="isActive('account')">
                <v-row>
                    <v-col>
                        <p class="text-h4 blue--text">
                            <v-icon color="blue">mdi-account</v-icon> Dados de acesso
                        </p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-toolbar color="blue" dark>
                                <v-toolbar-title>Gerenciar dados</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn text @click="saveAccount()">
                                        <v-icon>mdi-content-save</v-icon> Salvar
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-card-text>
                                <v-skeleton-loader v-if="loadingAccountFields" type="paragraph" loading>
                                </v-skeleton-loader>
                                <v-form v-show="!loadingAccountFields" ref="formAccount">
                                    <v-row>
                                        <v-col>
                                            <v-text-field v-model="accountName" label="Nome"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-text-field v-model="accountEmail" label="E-mail"></v-text-field>
                                        </v-col>
                                        <v-col>
                                            <v-text-field v-model="accountPassword" label="Senha"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-file-input v-model="currentFile" label="Foto" chips @change="readAccountPhoto"></v-file-input>
                                            <v-progress-linear v-show="progressShow" v-model="progressUpload" class="grey--text" height="20">
                                                {{progressUpload}} %
                                            </v-progress-linear>
                                            <v-alert v-show="uploadSuccess" type="success" transition="fade-transition">
                                                {{uploadMsg}}
                                            </v-alert>
                                            <v-alert v-show="uploadError" type="error" transition="fade-transition">
                                                {{uploadMsg}}
                                            </v-alert>
                                        </v-col>
                                        <v-col>
                                            <v-avatar>
                                                <v-img :src="accountPhoto"></v-img>
                                            </v-avatar>
                                            <span>{{accountName}}</span>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-container v-show="isActive('inscribe')" fluid>
                <v-row>
                    <v-col>
                        <p class="text-h4 teal--text">
                            <v-icon color="teal">mdi-folder-information</v-icon> Cadastro
                        </p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-toolbar color="teal" dark>
                                <v-toolbar-title>Gerenciar cadastro - <span class="small">{{tabInscribeTitle}}</span>
                                </v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{on, attrs}">
                                            <v-btn text @click="activeTabInscribe('inscribe')" v-bind="attrs" v-on="on">
                                                <v-icon>mdi-folder-text</v-icon>
                                            </v-btn>

                                        </template>
                                        <span>Dados do cadastro</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{on, attrs}">
                                            <v-btn text @click="activeTabInscribe('events')" v-bind="attrs" v-on="on">
                                                <v-icon>mdi-folder-clock</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Dados do evento</span>
                                    </v-tooltip>


                                    <v-menu offset-y>
                                        <template v-slot:activator="{on, attrs}">
                                            <v-btn text v-on="on" v-bind="attrs">
                                                <v-icon>
                                                    mdi-dots-vertical</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item link @click="activeTabInscribe('engaged')">
                                                <v-list-item-title>Dados dos noivos</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item link @click="activeTabInscribe('committe')">
                                                <v-list-item-title>Comissão de formatura</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>

                                </v-toolbar-items>
                            </v-toolbar>
                            <v-card-text v-show="showTabInscribe == 'inscribe'">
                                <v-skeleton-loader v-if="loadingInscribeFields" type="text@5" loading>
                                </v-skeleton-loader>
                                <v-form v-show="!loadingInscribeFields" ref="formInscribe">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="Nome do Responsável" v-model="inscribeAccountable">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="Telefone" v-model="inscribePhone" v-mask="maskTel(inscribePhone)"></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="4" lg="2">
                                                <v-text-field label="CEP" v-model="inscribeAddress.zipcode" v-mask="maskCep" @blur="getCEP('inscribeAddress')"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="8" lg="6">
                                                <v-text-field label="Logradouro" v-model="inscribeAddress.street">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="2">
                                                <v-text-field label="Número" v-model="inscribeAddress.number">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="2">
                                                <v-text-field label="Complemento" v-model="inscribeAddress.complement">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="Bairro" v-model="inscribeAddress.neighborhood">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="Cidade" v-model="inscribeAddress.city">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="Estado" v-model="inscribeAddress.state">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="País" v-model="inscribeAddress.country">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="CPF" v-model="inscribeCpf"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field label="RG" v-model="inscribeRg"></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-select v-model="inscribeService.idservice" label="Serviço" :items="services" item-text="name" item-value="idservice" required>
                                                </v-select>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-select v-model="inscribeFormation.idformation" label="Formação" :items="formation" item-text="name" item-value="idformation" required>
                                                </v-select>
                                            </v-col>
                                        </v-row>
                                    </v-container>

                                </v-form>
                            </v-card-text>
                            <v-card-text v-show="showTabInscribe == 'events'">
                                <v-skeleton-loader v-if="loadingEventFields" type="text@4" loading>
                                </v-skeleton-loader>
                                <v-form v-show="!loadingEventFields" ref="formEvents">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" lg="4">
                                                <v-text-field label="Evento" v-model="eventName"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="4">
                                                <v-menu v-model="pickDateEvent" :close-on-content-click="false" :nudge-right="40" transition="scale-transition" offset-y min-width="auto">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field v-model="eventDate" label="Data do evento" prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                        </v-text-field>
                                                    </template>
                                                    <v-date-picker v-model="eventDate" @input="pickDateEvent = false">
                                                    </v-date-picker>
                                                </v-menu>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="4">
                                                <v-menu ref="menu" v-model="pickTimeEvent" :close-on-content-click="false" :nudge-right="40" :return-value.sync="eventTime" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field v-model="eventTime" label="Horário do Evento" prepend-icon="mdi-calendar-clock" readonly v-bind="attrs" v-on="on">
                                                        </v-text-field>
                                                    </template>
                                                    <v-time-picker v-if="pickTimeEvent" v-model="eventTime" full-width @click:minute="$refs.menu.save(eventTime)">
                                                    </v-time-picker>
                                                </v-menu>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="4" lg="2">
                                                <v-text-field label="CEP" v-model="eventAddress.zipcode" v-mask="maskCep" @blur="getCEP('eventAddress')"></v-text-field>
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
                                                <v-text-field label="Cidade" v-model="eventAddress.city"></v-text-field>
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
                            </v-card-text>
                            <v-card-text v-show="showTabInscribe == 'engaged'">

                                <v-skeleton-loader v-if="loadingEngagedFields" type="heading, avatar, text@5" loading>
                                </v-skeleton-loader>
                                <v-form v-show="!loadingEngagedFields" ref="formEngaged">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <h4>Dados da noiva</h4>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-avatar size="80" color="grey lighten-4" @click="openUploadPhoto('engagedBrideUploadPhoto')">
                                                    <v-img :src="engagedBridePhoto"></v-img>
                                                </v-avatar>
                                            </v-col>
                                            <v-col v-show="crud == 'c'" cols="12" md="6">
                                                <v-checkbox v-model="engagedBrideAccountable" label="Responsável pelo contrato" @click="selectAccountableBride()">
                                                </v-checkbox>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" lg="4">
                                                <v-text-field prepend-inner-icon="mdi-human-female" label="Nome" v-model="engagedBrideName" :rules="engagedBrideNameRules" required>
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="4">
                                                <v-text-field prepend-inner-icon="mdi-human-female" v-mask="maskTel(engagedBridePhone)" label="Telefone" v-model="engagedBridePhone" :rules="engagedBridePhoneRules" required>
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="4">
                                                <v-text-field prepend-inner-icon="mdi-human-female" v-mask="maskTel(engagedBrideMobile)" label="Celular" v-model="engagedBrideMobile" :rules="engagedBrideMobileRules" required>
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="4" lg="2">
                                                <v-text-field prepend-inner-icon="mdi-human-female" label="CEP" v-model="engagedBrideAddress.zipcode" @blur="getCEP('engagedBrideAddress')" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="8" lg="6">
                                                <v-text-field prepend-inner-icon="mdi-human-female" :loading="inputLoading" label="Logradouro" v-model="engagedBrideAddress.street" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="2">
                                                <v-text-field prepend-inner-icon="mdi-human-female" label="Número" v-model="engagedBrideAddress.number" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="2">
                                                <v-text-field prepend-inner-icon="mdi-human-female" label="Complemento" v-model="engagedBrideAddress.complement">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" :loading="inputLoading" label="Bairro" v-model="engagedBrideAddress.neighborhood" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" :loading="inputLoading" label="Cidade" v-model="engagedBrideAddress.city" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" :loading="inputLoading" label="Estado" v-model="engagedBrideAddress.state" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" :loading="inputLoading" label="País" v-model="engagedBrideAddress.country" :rules="engagedBrideAddressRules" required></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" v-model="engagedBrideCpf" label="CPF" v-mask="maskCpf">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" v-model="engagedBrideRg" label="RG"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" v-model="engagedBrideBirthdate" label="Data de nascimento" v-mask="maskBirthdate">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-female" v-model="engagedBrideEmail" label="E-mail"></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <h4>Dados do noivo</h4>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-avatar size="80" color="grey lighten-4" @click="openUploadPhoto('engagedGroomUploadPhoto')">
                                                    <v-img :src="engagedGroomPhoto"></v-img>
                                                </v-avatar>
                                            </v-col>
                                            <v-col v-show="crud == 'c'" cols="12" md="6">
                                                <v-checkbox v-model="engagedGroomAccountable" label="Responsável pelo contrato" @click="selectAccountableGroom()">
                                                </v-checkbox>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" lg="4">
                                                <v-text-field prepend-inner-icon="mdi-human-male" label="Nome" v-model="engagedGroomName" :rules="engagedGroomNameRules" required>
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="4">
                                                <v-text-field prepend-inner-icon="mdi-human-male" label="Telefone" v-model="engagedGroomPhone" :rules="engagedGroomPhoneRules" required>
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="4">
                                                <v-text-field prepend-inner-icon="mdi-human-male" label="Celular" v-model="engagedGroomMobile" :rules="engagedGroomMobileRules" required>
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="4" lg="2">
                                                <v-text-field prepend-inner-icon="mdi-human-male" label="CEP" v-model="engagedGroomAddress.zipcode" @blur="getCEP('engagedGroomAddress')">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="8" lg="6">
                                                <v-text-field prepend-inner-icon="mdi-human-male" :loading="inputLoading" label="Logradouro" v-model="engagedGroomAddress.street" :rules="engagedGroomAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="2">
                                                <v-text-field prepend-inner-icon="mdi-human-male" label="Número" v-model="engagedGroomAddress.number" :rules="engagedGroomAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="2">
                                                <v-text-field prepend-inner-icon="mdi-human-male" label="Complemento" v-model="engagedGroomAddress.complement">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" :loading="inputLoading" label="Bairro" v-model="engagedGroomAddress.neighborhood" :rules="engagedGroomAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" :loading="inputLoading" label="Cidade" v-model="engagedGroomAddress.city" :rules="engagedGroomAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" :loading="inputLoading" label="Estado" v-model="engagedGroomAddress.state" :rules="engagedGroomAddressRules" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" :loading="inputLoading" label="País" v-model="engagedGroomAddress.country" :rules="engagedGroomAddressRules" required></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" v-model="engagedGroomCpf" label="CPF" v-mask="maskCpf">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" v-model="engagedGroomRg" label="RG"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" v-model="engagedGroomBirthdate" label="Data de nascimento" v-mask="maskBirthdate">
                                                </v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" lg="3">
                                                <v-text-field prepend-inner-icon="mdi-human-male" v-model="engagedGroomEmail" label="E-mail"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                    <v-container v-if="selectEngaged">
                                        <v-row>
                                            <v-col>
                                                <h4>Serviços do casamento</h4>
                                                Saber os demais serviços do casamento nos ajuda a alinhar horários, posição da banda etc.
                                                <v-btn text color="teal" dark>
                                                    <v-icon>mdi-plus-circle</v-icon> Cadastrar
                                                </v-btn>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-form>
                                <v-dialog v-model="engagedBrideUploadPhoto" transition="dialog-bottom-transition" max-width="600" persistent>
                                    <v-card>
                                        <v-toolbar color="teal" dark>
                                            Upload de foto da noiva
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-file-input v-model="currentFile" label="Foto" chips @change="readEngagedPhoto(currentFile, 'engagedBridePhoto')">
                                            </v-file-input>
                                            <v-progress-linear v-show="progressShow" v-model="progressUpload" class="grey--text" height="20">
                                                {{progressUpload}} %
                                            </v-progress-linear>
                                            <v-alert v-show="uploadSuccess" type="success" transition="fade-transition">
                                                {{uploadMsg}}
                                            </v-alert>
                                            <v-alert v-show="uploadError" type="error" transition="fade-transition">
                                                {{uploadMsg}}
                                            </v-alert>
                                        </v-card-text>
                                        <v-card-actions class="justify-end">
                                            <v-btn depressed dark color="teal" @click="engagedBrideUploadPhoto = false">
                                                Fechar</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                                <v-dialog v-model="engagedGroomUploadPhoto" transition="dialog-bottom-transition" max-width="600" persistent>
                                    <v-card>
                                        <v-toolbar color="teal" dark>
                                            Upload de foto do noivo
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-file-input v-model="currentFile" label="Foto" chips @change="readEngagedPhoto(currentFile, 'engagedGroomPhoto')">
                                            </v-file-input>
                                            <v-progress-linear v-show="progressShow" v-model="progressUpload" class="grey--text" height="20">
                                                {{progressUpload}} %
                                            </v-progress-linear>
                                            <v-alert v-show="uploadSuccess" type="success" transition="fade-transition">
                                                {{uploadMsg}}
                                            </v-alert>
                                            <v-alert v-show="uploadError" type="error" transition="fade-transition">
                                                {{uploadMsg}}
                                            </v-alert>
                                        </v-card-text>
                                        <v-card-actions class="justify-end">
                                            <v-btn depressed dark color="teal" @click="engagedGroomUploadPhoto = false">
                                                Fechar</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                                <v-dialog v-model="formWeddingServices">
                                    <v-card>

                                    </v-card>
                                </v-dialog>
                            </v-card-text>
                            <v-card-text v-show="showTabInscribe == 'committe'">
                                <v-skeleton-loader v-if="loadingCommitteFields" type="text, button"></v-skeleton-loader>
                                <v-form v-show="!loadingCommitteFields" ref="formGraduationCommitte">
                                    <v-row>
                                        <v-col>
                                            <h4>Comissão de Formatura</h4>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>

                                            <v-text-field v-model="graduationCommitteName" label="Membro da comissão" :rules="graduationCommitteNameRules" required>
                                            </v-text-field>
                                            <v-btn color="teal" class="white--text" depressed @click="addMemberGraduationCommitte()">
                                                <v-icon>mdi-plus</v-icon>
                                                Acrescentar
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-chip v-for="(member, i) in graduationCommitteMember" :key="i" class="ma-1" close @click:close="removeMemberGraduationCommitte(i, 1)">
                                                {{member}}
                                            </v-chip>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-alert v-show="alert" type="info" dismissible>{{alertMsg}}
                                            </v-alert>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>
                            <v-card-actions v-show="showTabInscribe == 'inscribe'">
                                <v-spacer></v-spacer>
                                <v-btn depressed dark large color="teal" @click="saveInscribe()">
                                    <v-icon>mdi-content-save</v-icon> Salvar
                                </v-btn>
                            </v-card-actions>
                            <v-card-actions v-show="showTabInscribe == 'events'">
                                <v-spacer></v-spacer>
                                <v-btn depressed dark large color="teal" @click="saveEvent()">
                                    <v-icon>mdi-content-save</v-icon> Salvar
                                </v-btn>
                            </v-card-actions>
                            <v-card-actions v-show="showTabInscribe == 'engaged'">
                                <v-spacer></v-spacer>
                                <v-btn depressed dark large color="teal" @click="saveEngaged()">
                                    <v-icon>mdi-content-save</v-icon> Salvar
                                </v-btn>
                            </v-card-actions>
                            <v-card-actions v-show="showTabInscribe == 'committe'">
                                <v-spacer></v-spacer>
                                <v-btn depressed dark large color="teal" @click="saveCommitte()">
                                    <v-icon>mdi-content-save</v-icon> Salvar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-container v-show="isActive('repertory')" fluid>
                <v-row>
                    <v-col>
                        <p class="text-h4 amber--text text--darken-3">
                            <v-icon color="amber darken-3">mdi-music-circle</v-icon> Repertório
                        </p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-toolbar color="amber darken-3" dark>
                                <v-toolbar-title>Gerenciar repertório</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn text v-show="!startedRepertory" @click="startRepertory()">
                                        <v-icon>mdi-play</v-icon> iniciar
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-card-text v-show="!startedRepertory">
                                <v-container>
                                    <v-row>
                                        <v-col></v-col>
                                        <v-col class="align-center">
                                            <v-img src="assets/img/undraw_media_player_ylg8.svg" max-width="600">
                                            </v-img>
                                            <span class="text-h4 ma-4">Ainda não há repertório inicializado</span>
                                        </v-col>
                                        <v-col></v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-text v-show="startedRepertory">
                                <v-container>
                                    <v-row>
                                        <v-col>
                                            <v-form ref="formAddRepertoryItem">
                                                <v-skeleton-loader v-if="loadingSelectFields" type="text@2, button">
                                                </v-skeleton-loader>
                                                <v-select v-show="!loadingSelectFields" v-model="repertoryMoments" label="Momento" :items="moments" item-text="name" item-value="idmoments" @change="getMusic(repertoryMoments)">
                                                </v-select>
                                                <v-select v-show="!loadingSelectFields" v-model="repertoryMusic" label="Música" :items="music" item-text="name" item-value="idmusic" return-object></v-select>
                                                <p v-show="repertoryMusic">Ouvir {{repertoryMusic.name}}</p>
                                                <audio v-show="repertoryMusic" :src="repertoryMusic.url" controls="controls"></audio>
                                                <div v-show="repertoryMusic" class="small">{{repertoryMusic.url}}</div>
                                                <v-btn v-show="!loadingSelectFields" depressed class="amber darken-3 my-3" dark @click="addRepertoryItem()">
                                                    <v-icon>mdi-plus</v-icon> Adicionar Música
                                                </v-btn>
                                            </v-form>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-card>
                                                <v-card-title>Músicas Escolhidas</v-card-title>
                                                <v-card-text>
                                                    <v-skeleton-loader v-if="loadingListRepertory" type="text@2, button"></v-skeleton-loader>
                                                    <v-list v-show="!loadingListRepertory">
                                                        <v-list-item v-for="(item, i) in repertory" :key="i">
                                                            <v-list-item-icon>
                                                                <v-icon outlined>mdi-bookmark-music</v-icon>
                                                            </v-list-item-icon>
                                                            <v-list-item-content>
                                                                <v-list-item-title>
                                                                    <b>{{item.moments.name}}:</b> {{item.music.name}}
                                                                </v-list-item-title>
                                                                <v-list-item-subtitle>
                                                                    <audio :src="item.music.url" controls="controls" width="200"></audio>
                                                                </v-list-item-subtitle>
                                                                <v-list-item-action>
                                                                    <v-btn v-show="item.music.idmusic != 0" depressed color="amber darken-4" dark @click="delRepertoryItem(item.music.idmusic, item.moments.idmoments)">
                                                                        <v-icon>mdi-delete</v-icon>
                                                                    </v-btn>
                                                                </v-list-item-action>
                                                                <v-divider></v-divider>
                                                            </v-list-item-content>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-container v-show="isActive('agreement')">
                <v-row>
                    <v-col>
                        <p class="text-h4 red--text text--darken-3">
                            <v-icon color="red darken-3">mdi-file-document-edit</v-icon> Contrato
                        </p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <vue-countdown :time="countDownTime" :interval="100" v-slot="{ days, hours, minutes, seconds }">
                            <v-sheet rounded="xl" outlined class="pa-5 white--text red darken-3">
                                <v-icon class="white--text">mdi-clock-check</v-icon>
                                Faltam para o evento：{{ days }} dia(s)
                            </v-sheet>
                        </vue-countdown>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-toolbar color="red darken-3" dark>
                                <v-toolbar-title>Gerenciar contrato</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn text v-show="inscribeStatus == 2" @click="openDialogContractSign()">
                                        <v-icon>mdi-file-eye</v-icon>
                                    </v-btn>
                                    <v-btn text v-show="hasSignature" @click="dialogSignWithPassword = true">
                                        <v-icon>mdi-file-edit</v-icon>
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-dialog v-model="dialogContractSign" scrollable persistent max-width="700">
                                <v-card>
                                    <v-card-title class="red darken-3 white--text">Assinar contrato
                                    </v-card-title>
                                    <v-spacer></v-spacer>
                                    <v-card-text><?php $this->load->view('backend/contrato'); ?>
                                    </v-card-text>
                                    <v-card-actions class="red darken-3">
                                        <v-btn text @click="closeDialogContractSign()" class="white--text">
                                            <v-icon>mdi-close</v-icon> Fechar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-dialog v-model="dialogSignWithPassword" max-width="400">
                                <v-card>
                                    <v-card-title class="red darken-3 white--text">Assinatura
                                        com senha</v-card-title>
                                    <v-card-text v-show="formSignWithPassword">
                                        <div class="mt-4 mb-4">
                                            {{inscribeAccountable}}
                                        </div>
                                        <v-text-field type="password" v-model="signaturePassword" outlined>
                                        </v-text-field>
                                    </v-card-text>
                                    <v-card-text v-show="loadingSignWithPassword">
                                        <v-card color="red darken-3">
                                            <v-card-text>
                                                <v-progress-linear indeterminate color="white" class="mb-0">
                                                </v-progress-linear>
                                            </v-card-text>
                                        </v-card>
                                    </v-card-text>
                                    <v-card-text v-show="alertSignWithPassword">
                                        <v-alert type="error">Senha incorreta</v-alert>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn class="red darken-3 white--text" depressed @click="doSignWithPassword(userNow.id)">
                                            <v-icon>mdi-draw</v-icon>Assinar
                                        </v-btn>
                                        <v-btn depressed @click="dialogSignWithPassword = false">
                                            <v-icon>mdi-close</v-icon> Fechar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col>
                                            <v-card elevation="0">
                                                <v-card-text>
                                                    <v-alert v-show="hasSignature" border="left" class="justify-right" color="red lighten-2" dark>Contrato assinado</v-alert>
                                                    <p class="text-h4 red--text text--darken-3">Resumo do contrato</p>
                                                    <v-skeleton-loader v-if="loadingAgreementFormation" type="article@3">
                                                    </v-skeleton-loader>
                                                    <div v-show="!loadingAgreementService">
                                                        <p class="text-h6">Serviço: {{inscribeService.name}}
                                                            <span v-show="inscribeStatus == 0">
                                                                <v-btn color="red darken-3" text @click="showActive('inscribe')">
                                                                    <v-icon>mdi-pencil</v-icon>alterar serviço
                                                                </v-btn>
                                                            </span>
                                                        </p>
                                                        <p v-show="inscribeServiceTax != null"><b>Taxas:</b></p>
                                                        <p v-show="inscribeServiceTax != null" v-for="(tax, i) in inscribeServiceTax">
                                                            <i>{{tax.name}}</i> - R$ {{printMoeda(tax.value)}} <span v-show="tax.type == 2">{{tax.multiplied}}</span>
                                                        </p>
                                                    </div>
                                                    <v-divider></v-divider>
                                                    <v-skeleton-loader v-if="loadingAgreementFormation" type="article@3">
                                                    </v-skeleton-loader>
                                                    <div v-show="!loadingAgreementFormation">
                                                        <p class="text-h6">Formação: {{inscribeFormation.name}}
                                                            <span v-show="inscribeStatus == 0">
                                                                <v-btn color="red darken-3" text @click="showActive('inscribe')">
                                                                    <v-icon>mdi-pencil</v-icon>alterar formação
                                                                </v-btn>
                                                            </span>
                                                        </p>
                                                        <p><b>Valor:</b> R$ {{printMoeda(inscribeFormation.price)}}</p>
                                                    </div>
                                                    <v-divider></v-divider>
                                                    <v-skeleton-loader v-if="loadingAgreementEvent" type="article@3">
                                                    </v-skeleton-loader>
                                                    <div v-show="!loadingAgreementEvent">
                                                        <div v-show="!eventEmpty">
                                                            <p class="text-h6">Dados do evento
                                                                <span v-show="inscribeStatus == 0">
                                                                    <v-btn color="red darken-3" text @click="showActive('inscribe'), activeTabInscribe('events')">
                                                                        <v-icon>mdi-pencil</v-icon>editar evento
                                                                    </v-btn>
                                                                </span>
                                                            </p>
                                                            <p><b>Data: </b> {{eventDate}}</p>
                                                            <p><b>Horário: </b> {{eventTime}}</p>
                                                            <p><b>Endereço: </b> {{eventAddress.street}},
                                                                {{eventAddress.number}} {{eventAddress.complement}},
                                                                {{eventAddress.neighborhood}}, {{eventAddress.state}}
                                                            </p>
                                                        </div>
                                                        <div v-show="eventEmpty">
                                                            <p>Ainda não há dados de evento cadastrado.</p>
                                                            <p>
                                                                <v-btn depressed dark color="red darken-3" @click="showActive('inscribe'), activeTabInscribe('events')">
                                                                    <v-icon>mdi-folder-clock</v-icon> Cadastrar dados de
                                                                    evento
                                                                </v-btn>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <v-divider></v-divider>
                                                    <div v-if="inscribeStatus == 2">
                                                        <v-skeleton-loader v-if="loadingAgreementValues" type="article@3">
                                                        </v-skeleton-loader>
                                                        <div v-show="!loadingAgreementValues">
                                                            <p class="text-h6">Dados do contrato</p>
                                                            <p><b>Código do contrato:</b>{{contractCode}}</p>
                                                            <p><b>Data do contrato: </b>{{contractDate}}</p>
                                                            <p><b>Valor do contrato: </b>
                                                                R${{printMoeda(contractValue)}}
                                                            </p>
                                                            <p><b>Desconto: </b>R${{printMoeda(contractDiscount)}} (-)
                                                            </p>
                                                            <p><b>Adição: </b>R${{printMoeda(contractAddition)}} (+)</p>
                                                            <p>
                                                                <v-chip class="pa-2 ma-2" color="red darken-3" dark>
                                                                    <v-icon class="ma-2">mdi-currency-usd</v-icon>Valor
                                                                    Total: R${{printMoeda(contractValueTotal)}}
                                                                </v-chip>
                                                                <v-chip class="pa-2 ma-2" color="red darken-3" dark>
                                                                    <v-icon class="ma-2">mdi-cash</v-icon>Valor da
                                                                    entrada: R${{printMoeda(contractDownPayment)}}
                                                                </v-chip>
                                                                <v-chip class="pa-2 ma-2" color="red darken-3" dark>
                                                                    <v-icon class="ma-2">mdi-calendar-month</v-icon>
                                                                    Data da entrada: {{contractDownPaymentDate}}
                                                                </v-chip>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </v-card-text>
                                                <v-card-actions v-show="inscribeStatus == 0">
                                                    <v-checkbox v-show="!eventEmpty" v-model="inscribeAgree" color="red darken-3" label="Os dados acima estão corretos">
                                                    </v-checkbox>
                                                    <v-spacer></v-spacer>
                                                    <v-btn v-show="inscribeAgree" depressed large dark color="red darken-3" @click="generateContract()">Gerar contrato
                                                    </v-btn>
                                                </v-card-actions>
                                                <v-card-actions v-show="inscribeStatus == 1">
                                                    <v-alert border="left" color="red darken-3" dark>Seu contrato será
                                                        analisado pela equipe Toque Divino. Assim que for autenticado
                                                        será possível assinar o contrato.</v-alert>
                                                </v-card-actions>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
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
    <script type="text/javascript" src="<?php echo base_url('node_modules/vuetify-upload-button/dist/vuetify-upload-button.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue-loading-overlay.min.js'); ?>"></script>

    <script src="<?php echo base_url('node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/@ckeditor/ckeditor5-vue2/dist/ckeditor.js'); ?>"></script>

    <script src="<?php echo base_url('assets/js/jquery-2.1.3.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/bootstrap/dist/js/bootstrap.bundle.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/@chenfengyuan/vue-countdown/dist/vue-countdown.js'); ?>"></script>

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
    </script>
    <script type="module" src="<?php echo base_url('assets/js/customers.js'); ?>"></script>
</body>