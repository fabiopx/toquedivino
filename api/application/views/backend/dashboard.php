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
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">
    <title><?php echo $title; ?></title>
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
    <div id="page">
        <v-app id="dashboard">
            <!-- NAVEGAÇÃO -->
            <v-navigation-drawer v-model="drawer" color="blue-grey" app>
                <v-list>
                    <v-list-item class="px-2" dark>
                        <v-list-item-avatar>
                            <v-img :src="userNow.photo"></v-img>
                        </v-list-item-avatar>
                        <v-list-item-title>{{userNow.name}}</v-list-item-title>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list nav dense dark v-for="item in pages" :key="item.id">
                    <v-list-item v-if="!item.subgroup" v-model="selectedPage" link>
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title class="text-uppercase" v-text="item.text"
                                @click.stop="showActive(item.link)">
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-group v-else :prepend-icon="item.icon" color="white" no-action>
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title class="text-uppercase" dark v-text="item.text"></v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item v-for="(sub) in item.subgroups" :key="sub.title" v-model="selectedPage" link>
                            <v-list-item-content>
                                <v-list-item-title v-text="sub.title" @click.stop="showActive(sub.link)">
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </v-list>
            </v-navigation-drawer>
            <!-- FIM NAVEGAÇÃO -->

            <v-app-bar color="blue-grey" app>
                <v-app-bar-nav-icon dark @click="drawer = !drawer"></v-app-bar-nav-icon>
                <v-toolbar-title class="white--text">
                    <v-img max-height="61" max-width="180"
                        src="<?php echo base_url('assets/img/logotipo_branco.png'); ?>"></v-img>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon color="white" @click="showActive('home'), logout()">mdi-logout</v-icon>
                </v-btn>
            </v-app-bar>

            <v-main>
                <loading :active.sync="loadingVisible" :can-cancel="true"></loading>
                <v-dialog v-model="userNow.login" persistent max-width="500">
                    <v-card>
                        <v-card>
                            <v-card-title class="text-h5 blue-grey white--text">Login</v-card-title>
                            <v-card-text class="pa-5">
                                <v-form ref="formLogin">
                                    <v-text-field v-model="loginEmail" :rules="loginEmailRules"
                                        label="Entre com o e-mail" outlined></v-text-field>

                                    <v-text-field v-model="loginPassword" :rules="loginPasswordRules"
                                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        :type="showPassword ? 'text' : 'password'" label="Entre com a senha"
                                        @click:append="showPassword = !showPassword" outlined>
                                    </v-text-field>
                                </v-form>
                                <v-alert v-show="alertLogin.status" type="error">{{alertLogin.msg}}</v-alert>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue-grey" class="white--text" depressed large @click="enterLogin()">
                                    Entrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-card>
                </v-dialog>
                <!-- HOME -->
                <v-container class="home" v-show="isActive('home')">
                    <v-row class="justify-space-around mt-5">
                        <v-col cols="6" v-for="(card, i) in cards" :key="i">
                            <v-card elevation="5">
                                <v-card-title class="blue-grey--text" v-text="card.title"></v-card-title>
                                <v-card-text>
                                    <v-sheet max-width="calc(100% - 32px)">
                                        <GChart v-bind:type="card.typeChart" v-bind:data="card.dataChart"></GChart>
                                    </v-sheet>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn pill depressed color="blue-grey" class="white--text"
                                        @click="showActive(card.link)">
                                        mais...</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <!-- FIM HOME -->

                <!-- USUÁRIOS -->
                <v-container class="usuarios" v-show="isActive('usuarios')">
                    <v-row class="justify-space-between pa-5">
                        <v-col>
                            <h2 class="blue-grey--text">
                                <v-icon color="blue-grey">mdi-account-circle</v-icon> Usuários
                            </h2>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar dark color="blue-grey">
                                    <h3>Usuários</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add user -->
                                    <v-dialog v-model="dialogUsers" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon class="mr-2">mdi-account-plus</v-icon> novo usuário
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark @click="closeDialog('dialogUsers'), clearFormUsers()">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>novo usuário</v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveUser()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-form ref="formAccount" id="formAccount">
                                                <v-container>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="accountName"
                                                                :rules="accountNameRules" label="Nome do usuário"
                                                                required></v-text-field>
                                                        </v-col>
                                                        <v-col>
                                                            <v-text-field v-model="accountEmail"
                                                                :rules="accountEmailRules" label="E-mail do usuário"
                                                                required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="accountPhone"
                                                                v-mask="maskTel(accountPhone)"
                                                                :rules="accountPhoneRules" label="Telefone do Usuário"
                                                                required>
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col>
                                                            <v-select v-model="accountAccessType"
                                                                :rule="accountAccessTypeRules" :items="accessType"
                                                                item-text="name" item-value="value"
                                                                label="Tipo de Acesso" required>
                                                            </v-select>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="accountPassword" label="Senha"
                                                                :rules="accountPasswordRules"
                                                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                                                :type="showPassword ? 'text' : 'password'"
                                                                @click:append="showPassword = !showPassword">
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col>
                                                            <v-switch label="Status" v-model="accountStatus"></v-switch>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-file-input v-model="currentFile" label="Foto" chips
                                                                @change="readAccountPhoto"></v-file-input>
                                                            <v-progress-linear v-show="progressShow"
                                                                v-model="progressUpload" class="grey--text" height="20">
                                                                {{progressUpload}} %</v-progress-linear>
                                                            <v-alert v-show="uploadSuccess" type="success"
                                                                transition="fade-transition">{{uploadMsg}}</v-alert>
                                                            <v-alert v-show="uploadError" type="error"
                                                                transition="fade-transition">{{uploadMsg}}</v-alert>
                                                        </v-col>
                                                        <v-col>
                                                            <v-avatar>
                                                                <v-img :src="accountPhoto"></v-img>
                                                            </v-avatar>
                                                            <span class="mr-3">{{accountName}}</span>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <v-card-text>
                                    <!-- users table -->
                                    <v-text-field v-model="searchUsers" label="Busca" append-icon="mdi-magnify">
                                    </v-text-field>
                                    <v-skeleton-loader v-if="firstLoad" :contentLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerUsers" :search="searchUsers"
                                        :items="users" :items-per-page="5">
                                        <template v-slot:item.access="{item}">
                                            <span v-show="item.access == 0">Admin</span>
                                            <span v-show="item.access == 1">Usuário</span>
                                            <span v-show="item.access == 2">Cliente</span>
                                            <span v-show="item.access == 3">Músico</span>
                                        </template>
                                        <template v-slot:item.status="{item}">
                                            <v-chip v-show="item.status == 1" color="primary"> Ativo</v-chip>
                                            <v-chip v-show="item.status == 0"> Inativo</v-chip>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="blue" icon @click="editUsers(item)">
                                                <v-icon>mdi-account-edit</v-icon>
                                            </v-btn>
                                            <v-btn v-show="item.access != 0" color="red" icon
                                                @click="deleteUser(item.idaccount)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </template>

                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                </v-container>
                <!-- FIM USUÁRIOS -->

                <!-- SERVIÇOS -->
                <v-container class="servicos" v-show="isActive('servicos')">
                    <v-row class="justify-space-between pa-5">
                        <v-col>
                            <h3 class="blue-grey--text">
                                <v-icon color="blue-grey">mdi-bookmark-music</v-icon> Serviços
                            </h3>
                        </v-col>
                    </v-row>
                    <!-- Services Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar color="blue-grey">
                                    <h3 class="white--text">Serviços</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add service -->
                                    <v-dialog v-model="dialogService" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon class="mr-2">mdi-plus</v-icon>novo serviço
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark @click="closeDialog('dialogService', 'formService')">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    Cadastrar serviço
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveService()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-form ref="formService">
                                                <v-container>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="serviceName"
                                                                :rules="serviceNameRules" label="Nome do serviço"
                                                                required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-textarea v-model="serviceDescription"
                                                                :rules="serviceDescriptionRules"
                                                                label="Descrição do serviço" required>
                                                            </v-textarea>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-select v-model="serviceHasTax" label="Taxas"
                                                                :items="serviceTax" item-text="name" item-value="idtax"
                                                                multiple chips></v-select>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                    <!-- end add service -->
                                </v-toolbar>
                                <!-- services table -->
                                <v-card-text>
                                    <v-text-field label="Busca" v-model="searchService" append-icon="mdi-magnify">
                                    </v-text-field>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerServices" :search="searchService"
                                        :items="services" :items-per-page="5">
                                        <template v-slot:item.taxas="{item}">
                                            <v-chip v-for="(tax, i) in item.taxas" :key="i" class="ma-2">
                                                <b>{{tax.name}}:</b> R$ {{printMoeda(tax.value)}} <span
                                                    v-show="tax.type == 2">{{tax.multiplied}}</span>
                                            </v-chip>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="blue" title="editar" icon @click="editServices(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn color="red" title="deletar" icon
                                                @click="deleteService(item.idservice)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn icon>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                                <!-- end services table -->
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- End Services Card -->
                </v-container>
                <!-- FIM SERVIÇOS -->
                <!-- TAXAS -->
                <v-container class="taxas" v-show="isActive('taxas')">
                    <v-row class="justify-space-between pa-5">
                        <v-col>
                            <h3 class="blue-grey--text">
                                <v-icon color="blue-grey">mdi-bookmark-music</v-icon> Serviços
                            </h3>
                        </v-col>
                    </v-row>
                    <!-- Tax Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar color="blue-grey" dark>
                                    <h3>Taxas</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add tax -->
                                    <v-dialog v-model="dialogTaxService" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon class="mr-2">mdi-plus</v-icon>nova taxa
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark
                                                    @click="closeDialog('dialogTaxService', 'formServiceTax')">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    Taxa de Serviço
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveServiceTax()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-form ref="formServiceTax">
                                                <v-container>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="serviceTaxName"
                                                                :rules="serviceTaxNameRules" label="Nome da Taxa"
                                                                required>
                                                            </v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-textarea v-model="serviceTaxDescription"
                                                                :rules="serviceTaxDescriptionRules"
                                                                label="Descrição da taxa" required>
                                                            </v-textarea>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-select v-model="serviceTaxType" label="Tipo de Taxa"
                                                                :items="serviceTaxTypeSelect" item-text="name"
                                                                item-value="value" :rules="serviceTaxTypeRules"
                                                                required>
                                                            </v-select>
                                                        </v-col>
                                                        <v-col v-show="serviceTaxType == 2">
                                                            <v-select v-model="variantTaxName" label="Multiplicar"
                                                                :items="variantTax"></v-select>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="serviceTaxValue"
                                                                :rules="serviceTaxValueRules" label="Valor"
                                                                append-icon="mdi-currency-usd"
                                                                v-currency="{locale:'pt-BR', currency: 'BRL' , distractionFree: true, precision: 2, autoDecimalMode: true}">
                                                            </v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <!-- tax table -->
                                <v-card-text>
                                    <v-text-field v-model="searchServiceTax" label="Busca" append-icon="mdi-magnify">
                                    </v-text-field>
                                    <v-skeleton-loader v-if="firstLoad" :contentLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerServiceTax"
                                        :search="searchServiceTax" :items="serviceTax" :items-per-page="5">
                                        <template v-slot:item.value="{item}">
                                            <span>R$ {{ printMoeda(item.value) }}</span>
                                        </template>
                                        <template v-slot:item.type="{item}">
                                            <span v-show="item.type == 1">Fixa</span>
                                            <span v-show="item.type == 2">Multiplicada</span>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="blue" icon @click="editServiceTax(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn v-if="!item.servicehastax" color="red" icon
                                                @click="deleteTax(item.idtax)" title="deletar service">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                            <v-btn v-else color="grey" icon title="taxa associada a um serviço">
                                                <v-icon>mdi-delete-off</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                                <!-- end add tax -->
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- End Tax Card -->
                </v-container>
                <!-- FIM TAXAS -->

                <!-- FORMAÇÕES -->
                <v-container class="formacoes" v-show="isActive('formacoes')">
                    <v-row class="justify-space-between pa-5">
                        <v-col>
                            <h3 class="blue-grey--text">
                                <v-icon color="blue-grey">mdi-shape-plus</v-icon> Formações
                            </h3>
                        </v-col>
                    </v-row>
                    <!-- Formation Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar color="blue-grey">
                                    <h3 class="white--text">Formações</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add formation -->
                                    <v-dialog v-model="dialogFormation" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon>mdi-plus</v-icon> nova formação
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark
                                                    @click="closeDialog('dialogFormation', 'formFormation')">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    <v-icon>mdi-shape-plus</v-icon> Cadastrar Nova Formação
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveFormation()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-form ref="formFormation">
                                                <v-container>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="formationName"
                                                                :rules="formationNameRules" label="Nome da formação"
                                                                required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-textarea v-model="formationDescription"
                                                                :rules="formationDescriptionRules"
                                                                label="Descrição da formação" required>
                                                            </v-textarea>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="formationPrice"
                                                                :rules="formationPriceRules" label="Preço da formação"
                                                                v-currency="{locale:'pt-BR', currency: 'BRL' , distractionFree: true, precision: 2, autoDecimalMode: true}"
                                                                required>
                                                            </v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-select v-model="formationHasInstrument"
                                                                label="Instrumentos" :items="instrument"
                                                                item-text="name" item-value="idinstrument" multiple
                                                                chips></v-select>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="formationVideo"
                                                                label="Vídeo da Formação"></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <!-- formation table -->
                                <v-card-text>
                                    <v-text-field v-model="searchFormation" label="Busca" append-icon="mdi-magnify">
                                    </v-text-field>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerFormation"
                                        :search="searchFormation" :items="formation" :items-per-page="5">
                                        <template v-slot:item.instruments="{item}">
                                            <v-chip v-for="(inst, i) in item.instruments" :key="i" class="ma-2">
                                                <v-avatar color="white" class="mr-2">
                                                    <v-img :src="inst.image"></v-img>
                                                </v-avatar>
                                                {{inst.name}}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="blue" icon @click="editFormation(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn color="red" icon @click="deleteFormation(item.idformation)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- End Formation Card -->
                </v-container>
                <!-- FIM FORMAÇÕES -->
                <!-- INSTRUMENTOS -->
                <v-container class="instrumentos" v-show="isActive('instrumentos')">
                    <v-row class="justify-space-between pa-5">
                        <v-col>
                            <h3 class="blue-grey--text">
                                <v-icon color="blue-grey">mdi-shape-plus</v-icon> Formações
                            </h3>
                        </v-col>
                    </v-row>
                    <!-- Instrument Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <!-- add instrument -->
                                <v-toolbar color="blue-grey" dark>
                                    <h3>Instrumentos</h3>
                                    <v-spacer></v-spacer>

                                    <v-dialog v-model="dialogInstrument" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon>mdi-plus</v-icon> novo instrumento
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark
                                                    @click="closeDialog('dialogInstrument'), clearFormInstrument()">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    <v-icon>mdi-piano</v-icon> Instrumento musical
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveInstrument()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-form ref="formInstrument">
                                                <v-container>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="instrumentName"
                                                                :rules="instrumentNameRules" label="Nome do instrumento"
                                                                required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-select v-model="instrumentIcon" :items="instruments"
                                                                item-text="text" item-value="icon"
                                                                label="Imagem do Instrumento">
                                                            </v-select>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-img max-height="150" max-width="150"
                                                                :src="instrumentIcon">
                                                            </v-img>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <!-- end add instrument -->
                                <!-- table instrument -->
                                <v-card-text>
                                    <v-text-field v-model="searchInstrument" label="Busca" append-icon="mdi-magnify">
                                    </v-text-field>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerInstrument"
                                        :search="searchInstrument" :items="instrument" :items-per-page="5">
                                        <template v-slot:item.sound="{item}">

                                        </template>
                                        <template v-slot:item.image="{item}">
                                            <v-avatar>
                                                <v-img :src="item.image"></v-img>
                                            </v-avatar>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="blue" icon @click="editInstrument(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn v-if="!item.formationhasinstrument" color="red" icon
                                                @click="deleteInstrument(item.idinstrument)"
                                                title="deletar instrumento">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                            <v-btn v-else color="grey" icon
                                                title="instrumento associado a uma formação">
                                                <v-icon>mdi-delete-off</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                                <!-- end table instrument -->
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- Fim Instrumento Card -->
                </v-container>
                <!-- FIM INSTRUMENTOS -->

                <!-- GERENCIAL -->
                <!-- Sub Leads -->
                <v-container class="leads" v-show="isActive('leads')">
                    <v-row class="justify-space-between pa-5">
                        <h3 class="blue-grey--text">
                            <v-icon color="blue-grey">mdi-file-document-edit</v-icon> Gerencial
                        </h3>
                    </v-row>
                    <!-- Leads Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar dark color="blue-grey">
                                    <h3>Leads</h3>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialogLeads" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on"
                                                    @click="setCrud('c'), setDataPreInscribe()">
                                                    <v-icon>mdi-plus</v-icon> novo lead
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark
                                                    @click="closeDialog('dialogLeads'), clearFormPreInscribe()">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    Cadastrar Lead
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveLead()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-form ref="formPreInscribe">
                                                    <v-container>
                                                        <v-row>
                                                            <v-col>
                                                                <v-menu v-model="pickDatePreInscribe"
                                                                    :close-on-content-click="false" :nudge-right="40"
                                                                    transition="scale-transition" offset-y
                                                                    min-width="auto">
                                                                    <template v-slot:activator="{ on, attrs }">
                                                                        <v-text-field v-model="preInscribeDate"
                                                                            label="Selecione a data"
                                                                            prepend-icon="mdi-calendar" readonly
                                                                            v-bind="attrs" v-on="on"></v-text-field>
                                                                    </template>
                                                                    <v-date-picker v-model="preInscribeDate"
                                                                        @input="pickDatePreInscribe = false">
                                                                    </v-date-picker>
                                                                </v-menu>
                                                            </v-col>
                                                            <v-col>
                                                                <v-menu v-model="pickTimePreInscribe"
                                                                    :close-on-content-click="false" :nudge-right="40"
                                                                    transition="scale-transition" offset-y
                                                                    min-width="200px" min-height="200px">
                                                                    <template v-slot:activator="{ on, attrs }">
                                                                        <v-text-field v-model="preInscribeTime"
                                                                            label="Selecione o horário"
                                                                            prepend-icon="mdi-clock-time-four-outline"
                                                                            readonly v-bind="attrs" v-on="on">
                                                                        </v-text-field>
                                                                    </template>
                                                                    <v-time-picker v-if="pickTimePreInscribe"
                                                                        v-model="preInscribeTime" full-width
                                                                        @input="pickTimePreInscribe = false">
                                                                    </v-time-picker>
                                                                </v-menu>
                                                            </v-col>
                                                            <v-col>
                                                                <v-text-field v-model="preInscribeOrigin"
                                                                    label="Origem da lead"></v-text-field>
                                                            </v-col>

                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-menu offset-x :close-on-content-click="false"
                                                                    class="pa-3">
                                                                    <template v-slot:activator="{on, attrs}">
                                                                        <v-text-field v-model="preInscribeIp"
                                                                            label="IP do acesso (se houver)"
                                                                            v-bind="attrs" v-on="on"></v-text-field>
                                                                    </template>
                                                                    <v-card>
                                                                        <v-card-text>
                                                                            <div>Digite o IP</div>
                                                                            <vue-ip :ip="ip" :on-change="onChangeIP"
                                                                                theme="material"></vue-ip>
                                                                        </v-card-text>
                                                                    </v-card>
                                                                </v-menu>

                                                            </v-col>
                                                            <v-col>
                                                                <v-text-field v-model="preInscribeAccountable"
                                                                    label="Nome do responsável"
                                                                    :rules="preInscribeAccountableRule"></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field v-model="preInscribePhone"
                                                                    label="Telefone" v-mask="maskTel(preInscribePhone)"
                                                                    :rules="preInscribePhoneRule"></v-text-field>
                                                            </v-col>
                                                            <v-col>
                                                                <v-text-field v-model="preInscribeMobile"
                                                                    label="Celular" v-mask="maskTel(preInscribeMobile)"
                                                                    :rules="preInscribeMobileRule"></v-text-field>
                                                            </v-col>
                                                            <v-col>
                                                                <v-text-field v-model="preInscribeEmail" label="E-mail"
                                                                    :rules="preInscribeEmailRule"></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-form>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <v-card-text>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-text-field v-model="searchPreInscribe" label="Busca" append-icon="mdi-magnify"
                                        class="mt-4">
                                    </v-text-field>
                                    <v-data-table v-show="!firstLoad" :headers="headersPreInscribe" :items="preInscribe"
                                        :search="searchPreInscribe" :items-per-page="5">
                                        <template v-slot:item.flag="{item}">
                                            <v-icon v-if="item.flag == 0" color="red">mdi-checkbox-blank-circle
                                            </v-icon>
                                            <v-icon v-else="item.flag == 1" color="green">
                                                mdi-checkbox-marked-circle</v-icon>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-menu offset-y>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn v-show="item.flag == 0" color="primary" icon v-bind="attrs"
                                                        v-on="on">
                                                        <v-icon>mdi-briefcase-account</v-icon>
                                                    </v-btn>
                                                </template>
                                                <v-list>
                                                    <v-list-item v-for="(listItem, i) in preInscribeMenuAction" :key="i"
                                                        @click="openBottomSheet('bsPreInscribe', listItem.content)">
                                                        <v-list-item-icon>
                                                            <v-icon v-text="listItem.icon"></v-icon>
                                                        </v-list-item-icon>
                                                        <v-list-item-content>
                                                            <v-list-item-title v-text="listItem.title">
                                                            </v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                            <v-bottom-sheet v-model="bsPreInscribe">
                                                <v-sheet class="text-center">
                                                    <v-btn class="mt-6" text color="blue-grey"
                                                        @click="bsPreInscribe = !bsPreInscribe">
                                                        <v-icon>mdi-close</v-icon> close
                                                    </v-btn>
                                                    <div v-show="bsContent == 'whatsapp'" class="py-3">
                                                        <v-row>
                                                            <v-col md="6" offset-md="3">
                                                                <p>Enviar mensagem por whatsApp para:
                                                                    {{item.mobile}}</p>
                                                                <v-textarea outlined rows="1" v-model="sendWhatsapp">
                                                                </v-textarea>
                                                                <v-btn depressed class="white--text" color="blue-grey"
                                                                    @click="sendWhatsappPreInscribe(item.idpre_inscribe, item.mobile)">
                                                                    <v-icon>mdi-whatsapp</v-icon>Abrir Whatsapp
                                                                </v-btn>
                                                            </v-col>
                                                        </v-row>
                                                    </div>
                                                    <div v-show="bsContent == 'email'" class="py-3">
                                                        <v-row>
                                                            <v-col md="6" offset-md="3">
                                                                <p>Enviar e-mail para <b>{{item.email}}</b></p>
                                                                <ckeditor :editor="editor" tag-name="textarea"
                                                                    v-model="sendEmail"></ckeditor>
                                                                <!-- <v-textarea outlined rows="1" v-model="sendEmail" required></v-textarea> -->
                                                                <v-btn depressed class="white--text mt-2"
                                                                    color="blue-grey"
                                                                    @click="sendEmailPreInscribe(item.idpre_inscribe, item.email)">
                                                                    <v-icon>mdi-email-send</v-icon> Enviar
                                                                </v-btn>
                                                            </v-col>
                                                        </v-row>

                                                    </div>
                                                </v-sheet>
                                            </v-bottom-sheet>

                                            <v-btn v-show="item.flag == 0" color="blue-grey" icon
                                                @click="addInscribe(item)">
                                                <v-icon>mdi-folder-plus</v-icon>
                                            </v-btn>
                                            <v-btn color="red" icon @click="getLogMarketing(item.idpre_inscribe)">
                                                <v-icon>mdi-file-chart</v-icon>
                                            </v-btn>
                                            <v-dialog v-model="dialogPreInscribeReport"
                                                transition="dialog-bottom-transition" max-width="600">
                                                <v-card>
                                                    <v-toolbar color="blue-grey" dark>Estatística de interações
                                                        de marketing</v-toolbar>
                                                    <v-card-text>
                                                        <v-skeleton-loader v-if="firstLoad"
                                                            type="list-item-avatar-three-line">
                                                        </v-skeleton-loader>
                                                        <GChart v-show="!firstLoad"
                                                            v-bind:type="chartPreInscribeReport.typeChart"
                                                            v-bind:data="chartPreInscribeReport.dataChart">
                                                        </GChart>

                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-btn depressed @click="closeDialogPreInscribeReport()">
                                                            <v-icon>mdi-close</v-icon> Fechar
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- End Leads Card -->
                </v-container>
                <!-- End Sub Leads -->

                <!-- Sub Cadastros -->
                <v-container class="cadastros" v-show="isActive('cadastros')">
                    <v-row class="justify-space-between pa-5">
                        <v-col>
                            <h3 class="blue-grey--text">
                                <v-icon color="blue-grey">mdi-file-document-edit</v-icon> Gerencial
                            </h3>
                        </v-col>
                    </v-row>
                    <!-- Stepper Cadastro -->
                    <v-row v-show="contractFinish">
                        <v-col>
                            <v-sheet color="blue-grey" elevation="1" class="pa-3">
                                <v-toolbar color="blue-grey" elevation="0">
                                    <h3 class="white--text">
                                        <v-icon class="mr-2" color="white">mdi-file-plus</v-icon>Finalizar Contrato
                                    </h3>
                                    <v-spacer></v-spacer>
                                    <v-btn color="white" icon @click="hideContractFinish()">
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                </v-toolbar>

                                <v-stepper v-model="stepContract">
                                    <v-stepper-header>

                                        <v-stepper-step :complete="stepContract > 1" step="1">
                                            Dados do cadastro
                                        </v-stepper-step>
                                        <v-divider></v-divider>
                                        <v-stepper-step :complete="stepContract > 2" step="2">
                                            Dados complementares
                                        </v-stepper-step>
                                        <v-divider></v-divider>
                                        <v-stepper-step :complete="stepContract > 3" step="3">
                                            Dados do evento
                                        </v-stepper-step>
                                        <v-stepper-step step="4">
                                            Valores do contrato
                                        </v-stepper-step>
                                    </v-stepper-header>
                                    <v-stepper-items>
                                        <v-stepper-content step="1">
                                            <v-text-field class="mt-2" label="Responsável" :value="inscribeAccountable"
                                                outlined disabled>
                                            </v-text-field>
                                            <v-skeleton-loader v-show="headingLoader" class="mb-5" type="heading">
                                            </v-skeleton-loader>
                                            <v-text-field v-model="contractService.name"
                                                label="Serviço" outlined disabled>
                                            </v-text-field>
                                            <v-skeleton-loader v-show="headingLoader" class="mb-5" type="heading">
                                            </v-skeleton-loader>
                                            <v-text-field v-model="contractFormation.name"
                                                label="Formação"
                                                outlined disabled></v-text-field>
                                            <v-divider></v-divider>
                                            <v-btn color="primary" @click="nextStep()">Continuar</v-btn>
                                        </v-stepper-content>
                                        <v-stepper-content step="2">
                                            <v-btn depressed large color="primary"
                                                @click="selectComplementData('casamento')">Dados dos noivos</v-btn>
                                            <v-btn depressed dark large color="green"
                                                @click="selectComplementData('formatura')">Comissão de formatura</v-btn>

                                            <v-form v-show="selectEngaged" ref="formEngaged">
                                                <v-divider></v-divider>
                                                <v-row>
                                                    <v-col>
                                                        <h4>Dados da noiva</h4>
                                                    </v-col>
                                                    <v-col>
                                                        <v-checkbox v-model="engagedBrideAccountable"
                                                            label="Responsável pelo contrato"
                                                            @click="selectAccountableBride()">
                                                        </v-checkbox>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            label="Nome da noiva" v-model="engagedBrideName"
                                                            :rules="engagedBrideNameRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            v-mask="maskTel(engagedBridePhone)"
                                                            label="Telefone da Noiva" v-model="engagedBridePhone"
                                                            :rules="engagedBridePhoneRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            v-mask="maskTel(engagedBrideMobile)"
                                                            label="Celular da noiva" v-model="engagedBrideMobile"
                                                            :rules="engagedBrideMobileRules" required></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col cols="2">
                                                        <v-text-field prepend-inner-icon="mdi-human-female" label="CEP"
                                                            v-model="engagedBrideAddress.zipcode"
                                                            @blur="getCEP('engagedBrideAddress')"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            :loading="inputLoading" label="Logradouro"
                                                            v-model="engagedBrideAddress.street"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            label="Número" v-model="engagedBrideAddress.number"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            label="Complemento"
                                                            v-model="engagedBrideAddress.complement">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            :loading="inputLoading" label="Bairro"
                                                            v-model="engagedBrideAddress.neighborhood"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            :loading="inputLoading" label="Cidade"
                                                            v-model="engagedBrideAddress.city"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            :loading="inputLoading" label="Estado"
                                                            v-model="engagedBrideAddress.state"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            :loading="inputLoading" label="País"
                                                            v-model="engagedBrideAddress.country"
                                                            :rules="engagedBrideAddressRules" required></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            v-model="engagedBrideCpf" label="CPF" v-mask="maskCpf">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            v-model="engagedBrideRg" label="RG"></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            v-model="engagedBrideBirthdate" label="Data de nascimento"
                                                            v-mask="maskBirthdate">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-female"
                                                            v-model="engagedBrideEmail" label="E-mail"></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <h4>Dados do noivo</h4>
                                                    </v-col>
                                                    <v-col>
                                                        <v-checkbox v-model="engagedGroomAccountable"
                                                            label="Responsável pelo contrato"
                                                            @click="selectAccountableGroom()">
                                                        </v-checkbox>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            label="Nome do noivo" v-model="engagedGroomName"
                                                            :rules="engagedGroomNameRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            label="Telefone do noivo" v-model="engagedGroomPhone"
                                                            :rules="engagedGroomPhoneRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            label="Celular do noivo" v-model="engagedGroomMobile"
                                                            :rules="engagedGroomMobileRules" required></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col cols="2">
                                                        <v-text-field prepend-inner-icon="mdi-human-male" label="CEP"
                                                            v-model="engagedGroomAddress.zipcode"
                                                            @blur="getCEP('engagedGroomAddress')">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            :loading="inputLoading" label="Logradouro"
                                                            v-model="engagedGroomAddress.street"
                                                            :rules="engagedGroomAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-text-field prepend-inner-icon="mdi-human-male" label="Número"
                                                            v-model="engagedGroomAddress.number"
                                                            :rules="engagedGroomAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            label="Complemento"
                                                            v-model="engagedGroomAddress.complement">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            :loading="inputLoading" label="Bairro"
                                                            v-model="engagedGroomAddress.neighborhood"
                                                            :rules="engagedGroomAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            :loading="inputLoading" label="Cidade"
                                                            v-model="engagedGroomAddress.city"
                                                            :rules="engagedGroomAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            :loading="inputLoading" label="Estado"
                                                            v-model="engagedGroomAddress.state"
                                                            :rules="engagedGroomAddressRules" required></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            :loading="inputLoading" label="País"
                                                            v-model="engagedGroomAddress.country"
                                                            :rules="engagedGroomAddressRules" required></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            v-model="engagedGroomCpf" label="CPF" v-mask="maskCpf">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            v-model="engagedGroomRg" label="RG"></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            v-model="engagedGroomBirthdate" label="Data de nascimento"
                                                            v-mask="maskBirthdate">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field prepend-inner-icon="mdi-human-male"
                                                            v-model="engagedGroomEmail" label="E-mail"></v-text-field>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                            <v-form v-show="selectGraduationCommitte" ref="formGraduationCommitte">
                                                <v-divider></v-divider>
                                                <v-row>
                                                    <v-col>
                                                        <h4>Comissão de Formatura</h4>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field v-model="graduationCommitteName"
                                                            label="Membro da comissão"
                                                            :rules="graduationCommitteNameRules" required>
                                                        </v-text-field>
                                                        <v-btn color="blue-grey" class="white--text" depressed
                                                            @click="addMemberGraduationCommitte()">
                                                            <v-icon>mdi-plus</v-icon>
                                                            Acrescentar
                                                        </v-btn>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-chip v-for="(member, i) in graduationCommitteMember" :key="i"
                                                            class="ma-1" close
                                                            @click:close="removeMemberGraduationCommitte(i, 1)">
                                                            {{member}}</v-chip>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-alert v-show="alert" type="info" dismissible>{{alertMsg}}
                                                        </v-alert>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                            <v-divider></v-divider>
                                            <v-btn color="primary" @click="prevStep(), unselectComplementDatas()">Voltar
                                            </v-btn>
                                            <v-btn color="primary" @click="nextStep()">Continuar</v-btn>
                                        </v-stepper-content>
                                        <v-stepper-content step="3">
                                            <v-form ref="formEvent">
                                                <v-row>
                                                    <v-col>
                                                        <v-menu v-model="pickDateEvent" :close-on-content-click="false"
                                                            :nudge-right="40" transition="scale-transition" offset-y
                                                            min-width="auto">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="eventDate" label="Data do evento"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                    :rules="eventDateRules" v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="eventDate"
                                                                @input="pickDateEvent = false">
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>
                                                    <v-col>
                                                        <v-menu ref="menu" v-model="pickTimeEvent"
                                                            :close-on-content-click="false" :nudge-right="40"
                                                            :return-value.sync="eventTime" transition="scale-transition"
                                                            offset-y max-width="290px" min-width="290px">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="eventTime"
                                                                    label="Horário do Evento"
                                                                    prepend-icon="mdi-calendar-clock" readonly
                                                                    v-bind="attrs" :rules="eventTimeRules" v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-time-picker v-if="pickTimeEvent" v-model="eventTime"
                                                                full-width @click:minute="$refs.menu.save(eventTime)">
                                                            </v-time-picker>
                                                        </v-menu>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col cols="2">
                                                        <v-text-field label="CEP" v-model="eventAddress.zipcode"
                                                            @blur="getCEP('eventAddress')">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <v-text-field :loading="inputLoading" label="Logradouro"
                                                            v-model="eventAddress.street" :rules="eventAddressRules">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-text-field label="Número" v-model="eventAddress.number"
                                                            :rules="eventAddressRules">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-text-field label="Complemento"
                                                            v-model="eventAddress.complement">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field :loading="inputLoading" label="Bairro"
                                                            v-model="eventAddress.neighborhood"
                                                            :rules="eventAddressRules">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field :loading="inputLoading" label="Cidade"
                                                            v-model="eventAddress.city" :rules="eventAddressRules">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field :loading="inputLoading" label="Estado"
                                                            v-model="eventAddress.state" :rules="eventAddressRules">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field :loading="inputLoading" label="País"
                                                            v-model="eventAddress.country" :rules="eventAddressRules">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                            <v-divider></v-divider>
                                            <v-btn color="primary" @click="prevStep()">Voltar
                                            </v-btn>
                                            <v-btn color="primary" @click="nextStep()">Continuar</v-btn>
                                        </v-stepper-content>
                                        <v-stepper-content step="4">
                                            <v-sheet outlined rounded class="pa-5">
                                                <v-row>
                                                    <v-col>
                                                        <b>Serviço:</b> {{contractService.name}}
                                                    </v-col>
                                                    <v-col v-if="contractService.taxas.length">
                                                        <v-row>
                                                            <v-col><b>Taxas:</b></v-col>
                                                        </v-row>
                                                        <v-row v-for="(tax, t) in contractService.taxas" :key="t">
                                                            <v-col>
                                                                <b>{{tax.name}}:</b><br>R$ {{printMoeda(tax.value)}}
                                                            </v-col>
                                                            <v-col v-show="tax.type == 2">X</v-col>
                                                            <v-col v-show="tax.type == 2">
                                                                <v-text-field v-model="tax.vValue" height="20" outlined
                                                                    @blur="addMultipliedContractValue(), calculateContractValue(), calculateContractValueTotal()">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col v-show="tax.type == 2">
                                                                {{tax.multiplied}}
                                                            </v-col>
                                                        </v-row>
                                                    </v-col>
                                                </v-row>
                                                <v-divider></v-divider>
                                                <v-row>
                                                    <v-col>
                                                        <b>Formação:</b> {{contractFormation.name}}
                                                    </v-col>
                                                    <v-col>
                                                        <b>Valor:</b>
                                                        R$ {{printMoeda(contractFormation.price)}}
                                                    </v-col>
                                                </v-row>
                                            </v-sheet>
                                            <v-divider></v-divider>
                                            <v-form ref="formContract">
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field label="Valor do contrato" v-model="contractValue"
                                                            v-currency="{currency: 'BRL', locale: 'pt-BR', autoDecimalMode: true}"
                                                            disabled>
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field label="Desconto ao valor do contrato"
                                                            v-model="contractDiscount" ref="inputContractDiscount"
                                                            v-currency="{currency: null, locale: 'pt-BR', autoDecimalMode: true}"
                                                            @blur="calculateContractValueTotal()"></v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field label="Adição ao valor do contrato"
                                                            v-model="contractAddition" ref="inputContractAddition"
                                                            v-currency="{currency: null, locale: 'pt-BR', autoDecimalMode: true}"
                                                            @blur="calculateContractValueTotal()"></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-chip color="primary" class="pa2" large>
                                                    <span><b>TOTAL:</b></span><span> R$
                                                        {{printMoeda(contractValueTotal)}}</span>
                                                </v-chip>
                                                <v-divider></v-divider>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field label="Valor de entrada"
                                                            v-model="contractDownPayment" ref="inputContractDownPayment"
                                                            v-currency="{currency: null, locale: 'pt-BR', autoDecimalMode: true}">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col>
                                                        <v-menu v-model="pickDateDownPayment"
                                                            :close-on-content-click="false" :nudge-right="40"
                                                            transition="scale-transition" offset-y min-width="auto">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="contractDownPaymentDate"
                                                                    label="Data da entrada" prepend-icon="mdi-calendar"
                                                                    readonly v-bind="attrs"
                                                                    :rules="contractDownPaymentDateRules" v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="contractDownPaymentDate"
                                                                @input="pickDateDownPayment = false">
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                            <v-divider></v-divider>
                                            <v-btn color="primary" @click="prevStep(), unselectComplementDatas()">voltar
                                            </v-btn>
                                            <v-btn color="primary" @click="saveContract()">
                                                <v-icon>mdi-content-save</v-icon>Salvar
                                            </v-btn>
                                        </v-stepper-content>
                                    </v-stepper-items>
                                </v-stepper>
                            </v-sheet>
                        </v-col>
                    </v-row>
                    <!-- Fim Stepper Cadastro -->
                    <!-- Inscribe Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar color="blue-grey">
                                    <h3 class="white--text">Cadastros</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add inscribe -->
                                    <v-dialog v-model="dialogInscribe" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on"
                                                    @click="setCrud('c'), setDataInscribe()">
                                                    <v-icon>mdi-plus</v-icon> novo cadastro
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark @click="closeDialog('dialogInscribe', 'formInscribe')">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    <v-icon>mdi-text-box-plus</v-icon> Cadastrar cliente
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn dark text @click="saveInscribe()">
                                                        <v-icon class="ma-2">mdi-content-save</v-icon>
                                                        Salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-form ref="formInscribe" lazy-validation>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="3">
                                                            <v-menu v-model="pickDateInscribe"
                                                                :close-on-content-click="false" :nudge-right="40"
                                                                transition="scale-transition" offset-y min-width="auto">
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-text-field v-model="inscribeDatetime"
                                                                        label="Selecione a data"
                                                                        prepend-icon="mdi-calendar" readonly
                                                                        v-bind="attrs" v-on="on"></v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="inscribeDatetime"
                                                                    @input="pickDateInscribe = false"></v-date-picker>
                                                            </v-menu>
                                                        </v-col>
                                                        <v-col cols="9">
                                                            <v-text-field v-model="inscribeAccountable"
                                                                :rules="inscribeAccountableRules"
                                                                label="Nome do responsável" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="4">
                                                            <v-text-field v-model="inscribePhone"
                                                                :rules="inscribePhoneRules"
                                                                v-mask="maskTel(inscribePhone)"
                                                                label="Telefone do responsável" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="4">
                                                            <v-text-field v-model="inscribeMobile"
                                                                :rules="inscribeMobileRules"
                                                                v-mask="maskTel(inscribeMobile)"
                                                                label="Celular do responsável" required></v-text-field>
                                                        </v-col>
                                                        <v-col>
                                                            <v-text-field v-model="inscribeEmail"
                                                                :rules="inscribeEmailRules"
                                                                label="E-mail do responsável" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="2">
                                                            <v-text-field v-model="inscribeAddress.zipcode"
                                                                :rules="inscribeAddressRules" label="CEP"
                                                                v-mask="maskCep" @blur="getCEP('inscribeAddress')"
                                                                required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="6">
                                                            <v-text-field :loading="inputLoading"
                                                                v-model="inscribeAddress.street"
                                                                :rules="inscribeAddressRules" label="Logradouro"
                                                                required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="2">
                                                            <v-text-field v-model="inscribeAddress.number"
                                                                :rules="inscribeAddressRules" label="Número" required>
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col cols="2">
                                                            <v-text-field v-model="inscribeAddress.complement"
                                                                label="Complemento" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="3">
                                                            <v-text-field :loading="inputLoading"
                                                                v-model="inscribeAddress.neighborhood"
                                                                :rules="inscribeAddressRules" label="Bairro" required>
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col cols="3">
                                                            <v-text-field :loading="inputLoading"
                                                                v-model="inscribeAddress.city" label="Cidade"
                                                                :rules="inscribeAddressRules" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="3">
                                                            <v-text-field :loading="inputLoading"
                                                                v-model="inscribeAddress.state" label="Estado"
                                                                :rules="inscribeAddressRules" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="3">
                                                            <v-text-field :loading="inputLoading"
                                                                v-model="inscribeAddress.country" label="País"
                                                                :rules="inscribeAddressRules" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="6">
                                                            <v-text-field v-model="inscribeCpf" label="CPF"
                                                                v-mask="maskCpf"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="6">
                                                            <v-text-field v-model="inscribeRg" label="RG">
                                                            </v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="6">
                                                            <v-select v-model="inscribeIdService" label="Serviço"
                                                                :rules="inscribeIdServiceRules" :items="services"
                                                                item-text="name" item-value="idservice" required>
                                                            </v-select>
                                                        </v-col>
                                                        <v-col cols="6">
                                                            <v-select v-model="inscribeIdFormation" label="Formação"
                                                                :rules="inscribeIdFormationRules" :items="formation"
                                                                item-text="name" item-value="idformation" required>
                                                            </v-select>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <!-- inscribe table -->
                                <v-card-text>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-text-field v-model="searchInscribe" label="Busca" append-icon="mdi-magnify"
                                        class="mt-4">
                                    </v-text-field>
                                    <v-data-table v-show="!firstLoad" :headers="headersInscribe" :items="inscribe"
                                        :search="searchInscribe" :items-per-page="5">
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="blue" icon @click="editInscribe(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn color="red" icon @click="deleteInscribe(item.idinscribe)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                            <v-btn color="purple" icon @click="showContractFinish(item)">
                                                <v-icon>mdi-file-plus</v-icon>
                                            </v-btn>
                                            <v-dialog v-model="dialogContract" fullscreen hide-overlay
                                                transition="dialog-bottom-transition">
                                                <v-card>
                                                    <v-toolbar dark color="blue-grey">
                                                        <v-btn icon dark @click="closeDialogContract()">
                                                            <v-icon>mdi-close</v-icon>
                                                        </v-btn>
                                                        <v-toolbar-title>Settings</v-toolbar-title>
                                                        <v-spacer></v-spacer>
                                                        <v-toolbar-items>
                                                            <v-btn dark text @click="dialog = false">
                                                                Salvar
                                                            </v-btn>
                                                        </v-toolbar-items>
                                                    </v-toolbar>
                                                </v-card>
                                            </v-dialog>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- End Inscribe Card -->
                </v-container>
                <!-- End Sub Cadastros -->

                <!-- Sub Contratos -->
                <v-container class="contratos" v-show="isActive('contratos')">
                    <v-row class="justify-space-between pa-5">
                        <h3 class="blue-grey--text">
                            <v-icon color="blue-grey">mdi-file-document-edit</v-icon> Gerencial
                        </h3>
                    </v-row>
                    <!-- Contracts Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar color="blue-grey">
                                    <h3 class="white--text">Contratos</h3>
                                    <v-spacer></v-spacer>
                                    <v-toolbar-items>
                                        <v-dialog v-model="dialogContractTrash" persistent
                                            transition="dialog-bottom-transition" max-width="900">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn text dark v-bind="attrs" v-on="on" @click="getContractsTrash()">
                                                    <v-icon>mdi-delete-circle</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-card>
                                                <v-toolbar dark color="blue-grey">
                                                    <v-icon class="mr-3">mdi-delete-circle</v-icon>Lixeira
                                                    <v-spacer></v-spacer>
                                                    <v-btn icon @click.stop="closeDialogContractTrash()">
                                                        <v-icon>mdi-close</v-icon>
                                                    </v-btn>
                                                </v-toolbar>
                                                <v-card-text>
                                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true"
                                                        type="table">
                                                    </v-skeleton-loader>
                                                    <v-data-table v-show="!firstLoad" :headers="headersContractsTrash"
                                                        :items="itemsContractTrash" :search="searchContract"
                                                        :items-per-page="5">
                                                        <template v-slot:item.actions="{item}">
                                                            <v-btn color="red" icon
                                                                @click="deleteContract(item.idinscribe)">
                                                                <v-icon>mdi-delete</v-icon>
                                                            </v-btn>
                                                            <v-btn color="purple" icon
                                                                @click="removeContract(item.idinscribe)">
                                                                <v-icon>mdi-file-remove</v-icon>
                                                            </v-btn>
                                                            <v-btn color="primary" icon
                                                                @click="undoContract(item.idinscribe)">
                                                                <v-icon>mdi-file-undo</v-icon>
                                                            </v-btn>
                                                        </template>
                                                    </v-data-table>
                                                </v-card-text>
                                            </v-card>
                                        </v-dialog>

                                    </v-toolbar-items>
                                </v-toolbar>
                                <v-card-text>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-text-field v-model="searchContract" label="Busca" append-icon="mdi-magnify"
                                        class="mt-4">
                                    </v-text-field>
                                    <v-data-table v-show="!firstLoad" :headers="headersContracts" :items="itemsContract"
                                        :search="searchContract" :items-per-page="5">

                                        <template v-slot:item.status="{item}">
                                            <v-chip v-show="item.status == 1" dark color="green">Em análise</v-chip>
                                            <v-chip v-show="item.status == 2" dark color="primary">Contrato analisado
                                            </v-chip>
                                        </template>

                                        <template v-slot:item.actions="{item}">
                                            <v-btn v-show="item.agreement.sign == 0, item.status == 2" color="blue-grey"
                                                icon dark @click="openDialogContractSign(item)">
                                                <v-icon>mdi-draw</v-icon>
                                            </v-btn>
                                            <v-dialog v-model="dialogContractSign" scrollable persistent
                                                max-width="700">
                                                <v-card>
                                                    <v-card-title class="blue-grey white--text">Assinar contrato
                                                    </v-card-title>
                                                    <v-spacer></v-spacer>
                                                    <v-card-text><?php $this->load->view('backend/contrato'); ?>
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-menu offset-y>
                                                            <template v-slot:activator="{on, attrs}">
                                                                <v-btn depressed color="blue-grey"
                                                                    class="white--text mr-2" v-bind="attrs" v-on="on">
                                                                    <v-icon>mdi-draw</v-icon> Assinar
                                                                </v-btn>
                                                            </template>
                                                            <v-list>
                                                                <v-subheader>ASSINANTES DESTE CONTRATO</v-subheader>
                                                                <v-list-item-group>
                                                                    <v-list-item v-for="(sign, i) in contractSignatures"
                                                                        :key="i">
                                                                        <v-list-item-content>
                                                                            <v-list-item-title
                                                                                @click="signWithPassword(sign)">
                                                                                {{sign.name}}</v-list-item-title>
                                                                        </v-list-item-content>
                                                                    </v-list-item>
                                                                </v-list-item-group>
                                                            </v-list>
                                                        </v-menu>
                                                        <v-dialog v-model="dialogSignWithPassword" max-width="400">
                                                            <v-card>
                                                                <v-card-title class="blue-grey white--text">Assinatura
                                                                    com senha</v-card-title>
                                                                <v-card-text v-show="formSignWithPassword">
                                                                    <div class="mt-4 mb-4">
                                                                        {{upperCase(signatureWithPassword.name)}}</div>
                                                                    <v-text-field type="password"
                                                                        v-model="signaturePassword"
                                                                        label="Digite a senha" outlined>
                                                                    </v-text-field>
                                                                </v-card-text>
                                                                <v-card-text v-show="loadingSignWithPassword">
                                                                    <v-card color="blue-grey">
                                                                        <v-card-text>
                                                                            <v-progress-linear indeterminate
                                                                                color="white" class="mb-0">
                                                                            </v-progress-linear>
                                                                        </v-card-text>
                                                                    </v-card>
                                                                </v-card-text>
                                                                <v-card-text v-show="alertSignWithPassword">
                                                                    <v-alert type="error">Senha incorreta</v-alert>
                                                                </v-card-text>
                                                                <v-card-actions>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn color="blue-grey white--text" depressed
                                                                        @click="doSignWithPassword(signatureWithPassword.idaccount)">
                                                                        <v-icon>mdi-draw</v-icon>Assinar
                                                                    </v-btn>
                                                                </v-card-actions>
                                                            </v-card>
                                                        </v-dialog>
                                                        <v-btn depressed @click="closeDialogContractSign()">
                                                            <v-icon>mdi-close</v-icon> Fechar
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <!-- <v-btn v-show="item.status == 2" color="primary" icon dark>
                                                <v-icon>mdi-file-link</v-icon>
                                            </v-btn> -->

                                            <v-btn v-show="item.status == 2" color="red" icon dark
                                                @click="cancelContract(item.idinscribe)">
                                                <v-icon>mdi-file-cancel</v-icon>
                                            </v-btn>

                                            <v-btn v-show="item.status == 1" color="blue" icon dark
                                                @click="openAnalyzeContract(item)">
                                                <v-icon>mdi-file-search</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                    <v-dialog v-model="dialogAnalyzeContract" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <v-card>
                                            <v-toolbar color="blue-grey" dark>
                                                <v-btn icon dark @click="closeDialogAnalyzeContract()">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>Analisar contrato</v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn text @click="validateContract(idInscribe)"><v-icon>mdi-check</v-icon> Validar</v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col>
                                                            <v-list subheader three-line>
                                                                <v-subheader>
                                                                    <h3>Resumo</h3>
                                                                </v-subheader>
                                                                <v-list-item>
                                                                    <v-list-item-content>
                                                                        <v-list-item-title>Responsável pelo contrato
                                                                        </v-list-item-title>
                                                                        <v-list-item-subtitle><b>Nome:</b>
                                                                            {{inscribeAccountable}} | <b>Telefone:</b>
                                                                            {{inscribePhone}} | <b>Celular:</b>
                                                                            {{inscribeMobile}}</v-list-item-subtitle>
                                                                    </v-list-item-content>
                                                                </v-list-item>
                                                                <v-list-item>
                                                                    <v-list-item-content>
                                                                        <v-list-item-title>Endereço do responsável
                                                                        </v-list-item-title>
                                                                        <v-list-item-subtitle>
                                                                            {{inscribeAddress.street}},
                                                                            {{inscribeAddress.number}}
                                                                            {{inscribeAddress.complement}},
                                                                            {{inscribeAddress.neighborhood}},
                                                                            {{inscribeAddress.district}} -
                                                                            {{inscribeAddress.city}},
                                                                            {{inscribeAddress.state}} -
                                                                            {{inscribeAddress.country}}
                                                                        </v-list-item-subtitle>
                                                                    </v-list-item-content>
                                                                </v-list-item>
                                                                <v-list-item>
                                                                    <v-list-item-content>
                                                                        <v-list-item-title>Dados pessoais
                                                                        </v-list-item-title>
                                                                        <v-list-item-subtitle><b>CPF:
                                                                            </b>{{inscribeCpf}} | <b>RG:
                                                                            </b>{{inscribeRg}}</v-list-item-subtitle>
                                                                    </v-list-item-content>
                                                                </v-list-item>
                                                            </v-list>
                                                        </v-col>
                                                    </v-row>
                                                    <v-sheet outlined rounded class="pa-5">
                                                        <v-row>
                                                            <v-col cols="12" lg="6">
                                                                <b>Serviço:</b> {{contractService.name}}
                                                            </v-col>
                                                            <v-col cols="12" lg="6" v-if="contractService.taxas.length">
                                                                <v-row>
                                                                    <v-col><b>Taxas:</b></v-col>
                                                                </v-row>
                                                                <v-row v-for="tax in contractService.taxas" :key="tax.name">
                                                                    <v-col cols="5">
                                                                        <b>{{tax.name}}:</b><br>R$
                                                                        {{printMoeda(tax.value)}}
                                                                    </v-col>
                                                                    <v-col cols="2" v-show="tax.type == 2">X</v-col>
                                                                    <v-col cols="5" v-show="tax.type == 2">
                                                                        <v-text-field v-model="tax.vValue" height="20"
                                                                            outlined
                                                                            @blur="addMultipliedContractValue(), calculateContractValue(), calculateContractValueTotal()">
                                                                        </v-text-field>
                                                                    </v-col>
                                                                    <v-col v-show="tax.type == 2">
                                                                        {{tax.multiplied}}
                                                                    </v-col>
                                                                </v-row>
                                                            </v-col>
                                                        </v-row>
                                                        <v-divider></v-divider>
                                                        <v-row>
                                                            <v-col cols="12" lg="6">
                                                                <b>Formação:</b> {{contractFormation.name}}
                                                            </v-col>
                                                            <v-col cols="12" lg="6">
                                                                <b>Valor:</b>
                                                                R$ {{printMoeda(contractFormation.price)}}
                                                            </v-col>
                                                        </v-row>
                                                    </v-sheet>
                                                    <v-form ref="formContract">
                                                        <v-row>
                                                            <v-col cols="12" md="4">
                                                                <v-text-field label="Valor do contrato"
                                                                    v-model="contractValue"
                                                                    v-currency="{currency: 'BRL', locale: 'pt-BR', autoDecimalMode: true}"
                                                                    disabled>
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col cols="12" md="4">
                                                                <v-text-field label="Desconto ao valor do contrato"
                                                                    v-model="contractDiscount"
                                                                    ref="inputContractDiscount"
                                                                    v-currency="{currency: null, locale: 'pt-BR', autoDecimalMode: true}"
                                                                    @blur="calculateContractValueTotal()">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col cols="12" md="4">
                                                                <v-text-field label="Adição ao valor do contrato"
                                                                    v-model="contractAddition"
                                                                    ref="inputContractAddition"
                                                                    v-currency="{currency: null, locale: 'pt-BR', autoDecimalMode: true}"
                                                                    @blur="calculateContractValueTotal()">
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-chip color="primary" class="pa2" large>
                                                            <span><b>TOTAL:</b></span><span> R$
                                                                {{printMoeda(contractValueTotal)}}</span>
                                                        </v-chip>
                                                        <v-divider></v-divider>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field label="Valor de entrada"
                                                                    v-model="contractDownPayment"
                                                                    ref="inputContractDownPayment"
                                                                    v-currency="{currency: null, locale: 'pt-BR', autoDecimalMode: true}">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col>
                                                                <v-menu v-model="pickDateDownPayment"
                                                                    :close-on-content-click="false" :nudge-right="40"
                                                                    transition="scale-transition" offset-y
                                                                    min-width="auto">
                                                                    <template v-slot:activator="{ on, attrs }">
                                                                        <v-text-field v-model="contractDownPaymentDate"
                                                                            label="Data da entrada"
                                                                            prepend-icon="mdi-calendar" readonly
                                                                            v-bind="attrs"
                                                                            :rules="contractDownPaymentDateRules"
                                                                            v-on="on">
                                                                        </v-text-field>
                                                                    </template>
                                                                    <v-date-picker v-model="contractDownPaymentDate"
                                                                        @input="pickDateDownPayment = false">
                                                                    </v-date-picker>
                                                                </v-menu>
                                                            </v-col>
                                                        </v-row>
                                                    </v-form>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <!-- End Sub Contratos -->

                <!-- Sub Assinaturas -->
                <v-container class="assinaturas" v-show="isActive('assinaturas')">
                    <v-row class="justify-space-between pa-5">
                        <h3 class="blue-grey--text">
                            <v-icon color="blue-grey">mdi-file-document-edit</v-icon> Gerencial
                        </h3>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar dark color="blue-grey">
                                    <h3>Assinaturas</h3>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialogSignature" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{ on,attrs }">
                                            <v-toolbar-items>
                                                <v-btn dark text v-on="on" v-bind="attrs"
                                                    @click="setCrud('c'), getUsers()">
                                                    <v-icon class="mr-2">mdi-plus</v-icon>nova assinatura
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark @click="closeDialogSignature()">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    Cadastrar Assinatura
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn depressed color="blue-grey" @click="saveSignature()">
                                                        <v-icon>mdi-content-save</v-icon> salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-form ref="formSignature">
                                                    <v-row>
                                                        <v-col v-show="crud == 'c'">
                                                            <v-select v-model="signatureUserName" :items="users"
                                                                label="Nome do signatário" item-text="name"
                                                                return-object></v-select>
                                                        </v-col>
                                                        <v-col v-show="crud == 'u'">
                                                            <v-text-field v-model="signatureUserName.name" label="Nome"
                                                                readonly></v-text-field>
                                                        </v-col>
                                                        <v-col>
                                                            <v-select v-model="signatureType" :items="signatureTypes"
                                                                label="Tipo de assinatura" item-text="text"
                                                                item-value="type"></v-select>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-select v-model="signatureFont" :items="signaturesFonts"
                                                                label="Fonte da assinatura" item-text="name"
                                                                item-value="signClass"></v-select>
                                                        </v-col>
                                                        <v-col>
                                                            <v-switch label="Status" v-model="signatureStatus">
                                                            </v-switch>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-sheet elevation="1" rounded outlined class="pa-5">
                                                                <h3 :class="signatureFont">{{ signatureUserName.name }}
                                                                </h3>
                                                            </v-sheet>
                                                        </v-col>
                                                        <v-col></v-col>
                                                    </v-row>
                                                </v-form>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <v-card-text>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerSignatures" :items="signatures"
                                        :items-per-page="5" :search="searchSignatures">
                                        <template v-slot:item.type="{item}">
                                            <v-chip v-show="item.type == 1">Contratante</v-chip>
                                            <v-chip v-show="item.type == 2">Contratado</v-chip>
                                            <v-chip v-show="item.type == 3">Testemunha</v-chip>
                                        </template>
                                        <template v-slot:item.status="{item}">
                                            <v-chip v-if="item.status == 1" color="primary">Ativo</v-chip>
                                            <v-chip v-else>Inativo</v-chip>
                                        </template>
                                        <template v-slot:item.inuse="{item}">
                                            <v-chip v-show="item.inuse == 0" color="red" dark>Não</v-chip>
                                            <v-chip v-show="item.inuse == 1" color="green" dark>Sim</v-chip>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="primary" icon @click="editSignature(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn v-show="item.inuse == 0" color="red" icon
                                                @click="deleteSignature(item.idsignature)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <!-- Fim Sub Assinaturas -->

                <!-- MOMENTOS -->
                <v-container class="repertorio" v-show="isActive('momentos')">
                    <v-row class="justify-space-between pa-5">
                        <h3 class="blue-grey--text">
                            <v-icon color="blue-grey">mdi-music</v-icon> Repertório
                        </h3>
                    </v-row>
                    <!-- Moments Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar dark color="blue-grey">
                                    <h3>Momentos</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add moments -->
                                    <v-dialog v-model="dialogMoments" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{on, attrs}">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon>mdi-plus</v-icon> novo momento
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark @click="closeDialog('dialogMoments', 'formMoments')">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    Cadastrar Momento
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn depressed color="blue-grey" @click="saveMoments()">
                                                        <v-icon>mdi-content-save</v-icon> salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-form ref="formMoments">
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="momentsName" label="Nome do Momento"
                                                                :rules="momentsNameRules" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="momentsDescription"
                                                                label="Descrição do momento"
                                                                :rules="momentsDescriptionRules" required>
                                                            </v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-switch label="Status" v-model="momentsStatus"></v-switch>
                                                        </v-col>
                                                    </v-row>
                                                </v-form>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <v-card-text>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table v-show="!firstLoad" :headers="headerMoments" :items="moments"
                                        :items-per-page="5" :search="searchMoments">
                                        <template v-slot:item.status="{item}">
                                            <v-chip color="primary" v-if="item.status">Ativo</v-chip>
                                            <v-chip v-else>Inativo</v-chip>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="primary" icon @click="editMoments(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn v-if="!item.musichasmoments" color="red" icon
                                                @click="deleteMoments(item.idmoments)" title="deletar momento">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                            <v-btn v-else color="grey" icon title="momento associado à uma musica">
                                                <v-icon>mdi-delete-off</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- Fim Moments Card -->
                </v-container>
                <!-- FIM MOMENTOS -->
                <!-- MUSICAS -->
                <v-container class="musicas" v-show="isActive('musicas')">
                    <v-row class="justify-space-between pa-5">
                        <h3 class="blue-grey--text">
                            <v-icon color="blue-grey">mdi-music</v-icon> Repertório
                        </h3>
                    </v-row>
                    <!-- Music Card -->
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-toolbar dark color="blue-grey">
                                    <h3>Músicas</h3>
                                    <v-spacer></v-spacer>
                                    <!-- add music -->
                                    <v-dialog v-model="dialogMusic" fullscreen hide-overlay
                                        transition="dialog-bottom-transition">
                                        <template v-slot:activator="{on, attrs}">
                                            <v-toolbar-items>
                                                <v-btn dark text v-bind="attrs" v-on="on" @click="setCrud('c')">
                                                    <v-icon>mdi-plus</v-icon> nova música
                                                </v-btn>
                                            </v-toolbar-items>
                                        </template>
                                        <v-card>
                                            <v-toolbar dark color="blue-grey">
                                                <v-btn icon dark @click="closeDialog('dialogMusic', 'formMusic')">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                                <v-toolbar-title>
                                                    Cadastrar Música
                                                </v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-toolbar-items>
                                                    <v-btn depressed color="blue-grey" @click="saveMusic()">
                                                        <v-icon>mdi-content-save</v-icon> salvar
                                                    </v-btn>
                                                </v-toolbar-items>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-form ref="formMusic">
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="musicName" label="Nome da música"
                                                                :rules="musicNameRules" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-text-field v-model="musicUrl" label="URL da música"
                                                                :rules="musicUrlRules" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-select v-model="musicHasMoments" label="Momentos"
                                                                :items="moments" item-text="name" item-value="idmoments"
                                                                multiple chips></v-select>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col>
                                                            <v-switch label="Status" v-model="musicStatus"></v-switch>
                                                        </v-col>
                                                    </v-row>
                                                </v-form>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                                <v-card-text>
                                    <v-skeleton-loader v-if="firstLoad" :tableLoading="true" type="table">
                                    </v-skeleton-loader>
                                    <v-data-table :headers="headerMusic" :items="musics" :items-per-page="5"
                                        :search="searchMusic">
                                        <template v-slot:item.url="{item}">
                                            <audio :src="item.url" controls="controls"></audio>
                                            <div class="small">{{item.url}}</div>
                                        </template>
                                        <template v-slot:item.moments="{item}">
                                            <v-chip v-for="(m, i) in item.moments" :key="i" class="mr-1 mb-1 ">
                                                {{m.name}}</v-chip>
                                        </template>
                                        <template v-slot:item.status="{item}">
                                            <v-chip color="primary" v-if="item.status">Ativa</v-chip>
                                            <v-chip v-else>Inativa</v-chip>
                                        </template>
                                        <template v-slot:item.actions="{item}">
                                            <v-btn color="primary" icon @click="editMusic(item)">
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn color="red" icon @click="deleteMusic(item.idmusic)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- Fim Music Card -->
                </v-container>
                <!-- FIM MUSICAS -->

                <!-- FINANCEIRO -->
                <v-container class="financeiro" v-show="isActive('financeiro')">
                    <v-row class="justify-space-between pa-5">
                        <h3 class="blue-grey--text">
                            <v-icon color="blue-grey">mdi-cash</v-icon> Financeiro
                        </h3>
                    </v-row>
                </v-container>

            </v-main>
        </v-app>
    </div>

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
    </script>
    <script type="module" src="<?php echo base_url('assets/js/dashboard.js'); ?>"></script>
</body>

</html>