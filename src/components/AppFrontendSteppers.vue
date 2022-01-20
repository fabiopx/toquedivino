<template>
    <v-stepper v-model="tela">
                    <v-stepper-header>
                        <v-stepper-step :complete="tela > 1" step="1">Evento</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 2" step="2">Instrumentos</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 3" step="3">Formação</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 4" step="4">Responsável</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step :complete="tela > 5" step="5">Dados complementares</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step step="6">Finalizando</v-stepper-step>
                    </v-stepper-header>
                    <v-stepper-items>
                        <v-stepper-content step="1">
                            <v-row>
                                <v-col>
                                    <v-img :src="require('../assets/undraw_selection_re_ycpo.svg')" max-width="500"></v-img>
                                </v-col>
                                <v-col>
                                    <v-form ref="firstScreen">
                                        <v-select v-model="selectedService" outlined class="mt-3"
                                            label="Selecione o evento" :items="services" item-text="name"
                                            :rules="serviceRules" return-object>
                                        </v-select>
                                    </v-form>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn depressed class="d-block mr-0 ml-auto" color="primary" @click="nextScreen()">
                                        Continuar <v-icon>mdi-menu-right</v-icon>
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
                                                                    <v-img :src="require('../assets/instrument/' + instrument.image)" width="40"
                                                                        class="ma-2">
                                                                    </v-img>
                                                                    <p class="grey--text text-left text-body-1">
                                                                        {{instrument.name}}</p>
                                                                </div>
                                                            </v-scroll-x-transition>
                                                            <v-scroll-x-transition>
                                                                <div v-if="active"
                                                                    class="text-h2 flex-grow-1 text-center">
                                                                    <v-img :src="require('../assets/instrument/' + instrument.image)" width="40"
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
                                    <v-btn depressed class="float-left" color="primary" @click="prevScreen()">
                                        <v-icon>mdi-menu-left</v-icon>Voltar
                                    </v-btn>
                                    <v-btn depressed class="float-right" color="primary" @click="nextScreen()">
                                        Continuar <v-icon>mdi-menu-right</v-icon>
                                    </v-btn>
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
                                    <v-btn depressed class="float-left" color="primary" @click="prevScreen()">
                                        <v-icon>mdi-menu-left</v-icon> Voltar
                                    </v-btn>
                                    <v-btn depressed class="float-right" color="primary" @click="nextScreen()">
                                        Continuar
                                        <v-icon>mdi-menu-right</v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content>

                        <!-- <v-stepper-content step="4">
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
                        </v-stepper-content> -->


                        <!-- <v-stepper-content step="6">
                            <v-row>
                                <v-col>
                                    <v-btn depressed color="primary" @click="prevScreen()">Voltar</v-btn>
                                    <v-btn depressed color="primary" >Finalizar</v-btn>
                                </v-col>
                            </v-row>
                        </v-stepper-content> -->
                    </v-stepper-items>
                </v-stepper>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'

export default {
    name:  'AppFrontendSteppers',

    data: () => ({
    
    active: false,
    maskPhone: '(##) ####-####',
    maskMobile: '(##) #####-####',
    maskCep: '#####-###',
    maskCpf: '###.###.###-##',
    maskCnpj: '##.###.###/####-##',
    setIP: {},
    selectedService: '',
    serviceRules:[v => !!v || "Por favor diga qual o tipo do evento"],
    selectedInstruments: [],
    alertSelectedInstruments: false,
    selectedFormation: null,
    alertSelectedFormation: false,
    inscribeID: '',
    inscribeAccountable: '',
    inscribeAccountableRules:[v => !!v || 'O campo NOME é requerido'],
    inscribeEmail: '',
    inscribeEmailRules: [
        v => !!v || 'O campo EMAIL é requerido',
        v => /.+@.+/.test(v) || 'Insira um E-mail válido'
    ],
    inscribePhone: '',
    inscribePhoneRules: [v => !!v || 'O campo TELEFONE é requerido'],
    inscribeMobile: '',
    inscribeMobileRules: [v => !!v || 'O campo CELULAR é requerido'],
    inscribeAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
    inscribeCpf: '',
    inscribeRg: '',
    endInscribe: false,
    eventName: '',
    eventDate: '',
    eventTime: '',
    eventAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
    eventAddressRules: [v => !!v || 'Este campo é obrigatório!'],
    pickDateEvent: false,
    pickTimeEvent: false,
    dialogVideoFormation: false,
    formation: '',
    dialogTooltipFormation: false,
    }),

     methods: {
         ...mapActions(['getServices']),
      nextScreen: function(){
        // if(this.tela == 1){
        //     if(this.$refs.firstScreen.validate()){
        //         this.tela = 2
        //         localStorage.removeItem('tela')
        //         localStorage.setItem('tela', this.tela)
        //         this.dados.service = this.selectedService 
        //         localStorage.setItem('dados', JSON.stringify(this.dados))
        //     }
        // } else if(this.tela == 2){
        //     if(this.selectedInstruments.length != 0){
        //         this.alertSelectedInstruments = false
        //         this.tela = 3
        //         localStorage.removeItem('tela')
        //         localStorage.setItem('tela', this.tela)
        //         this.getFormationByInstruments()
        //     } else{
        //         this.alertSelectedInstruments = true
        //     }
        // } else if(this.tela == 3){
        //     if(this.selectedFormation){
        //         this.tela = 4
        //         localStorage.removeItem('tela')
        //         localStorage.setItem('tela', this.tela)
        //         this.dados.formation = this.selectedFormation
        //         localStorage.setItem('dados', JSON.stringify(this.dados))
        //     } else {
        //         this.alertSelectedFormation = true
        //     }
        // } else if(this.tela == 4){
        //     if(this.$refs.formInscribePartOne.validate()){
        //         this.tela = 5
        //         localStorage.removeItem('tela')
        //         localStorage.setItem('tela', this.tela)
        //         this.dados.inscribe = {accountable: this.inscribeAccountable, email: this.inscribeEmail, phone: this.inscribePhone, mobile: this.inscribeMobile}
        //         localStorage.setItem('dados', JSON.stringify(this.dados))
        //     }
        // } else if (this.tela == 5){
        //     if(this.$refs.formInscribePartTwo.validate()){
        //         this.tela = 6
        //         localStorage.removeItem('tela')
        //         localStorage.setItem('tela', this.tela)
        //         this.dados.address = this.inscribeAddress
        //         localStorage.setItem('dados', JSON.stringify(this.dados))
        //         this.saveLead()
        //     }
        // }
    },
    prevScreen: function(){
        // if(this.tela >= 1){
        //     this.tela = this.tela - 1
        //     localStorage.removeItem('tela')
        //     localStorage.setItem('tela', this.tela)
        // }
        // if(this.tela == 4){
        //     this.inscribeAccountable = this.dados.inscribe.accountable
        //     this.inscribeEmail = this.dados.inscribe.email
        //     this.inscribePhone = this.dados.inscribe.phone
        //     this.inscribeMobile = this.dados.inscribe.mobile
        // }
    },
    redirect: function(url){
        window.open(url, '_blank')
    },
    isSelected: function(selected, alert){
        if(this[selected]){
            this[alert] = false
        }
    },
    accessVideo: function(video){
        window.open(video, '_blank')
    },
    openDialogFormationVideo: function(formation){
        this.dialogVideoFormation = true
        this.formation = formation
    },
    closeDialogFormationVideo: function(){
        this.formation = ''
        this.dialogVideoFormation = false
    },
    openDialogFormationTooltip: function(formation){
        this.dialogTooltipFormation = true
        this.formation = formation
    },
    closeDialogFormationTooltip: function(){
        this.formation = ''
        this.dialogTooltipFormation = false
    },
  },

  beforeCreated(){
    
  },

  mounted() {

    
   },

   computed: {
       ...mapGetters(['tela, start'])
   },
   
}
</script>