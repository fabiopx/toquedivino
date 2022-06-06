    Vue.use(VueGoogleCharts)
    Vue.use(VueMask)
    Vue.use(VueLoading)
    Vue.use(VuetifyUploadButton)
    Vue.use(CKEditor)
    Vue.use(VueIp)

    Vue.directive('mask', VueMask.VueMaskDirective)
    Vue.component('gchart', VueGoogleCharts.GChart)
    Vue.component('loading', VueLoading)
    Vue.component('VueIp', VueIp)

    function twoDigits(d) {
        if (0 <= d && d < 10) return "0" + d.toString();
        if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
        return d.toString();
    }

    function toDateFormat(input){
        var datePart = input.match(/\d+/g),
        year = datePart[0].substring(2), // get only two digits
        month = datePart[1], day = datePart[2];

        return day+'/'+month+'/'+year;
    }

    String.prototype.extenso = function(c){
        var ex = [
            ["zero", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"],
            ["dez", "vinte", "trinta", "quarenta", "cinqüenta", "sessenta", "setenta", "oitenta", "noventa"],
            ["cem", "cento", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"],
            ["mil", "milhão", "bilhão", "trilhão", "quadrilhão", "quintilhão", "sextilhão", "setilhão", "octilhão", "nonilhão", "decilhão", "undecilhão", "dodecilhão", "tredecilhão", "quatrodecilhão", "quindecilhão", "sedecilhão", "septendecilhão", "octencilhão", "nonencilhão"]
        ];
        var a, n, v, i, n = this.replace(c ? /[^,\d]/g : /\D/g, "").split(","), e = " e ", $ = "real", d = "centavo", sl;
        for(var f = n.length - 1, l, j = -1, r = [], s = [], t = ""; ++j <= f; s = []){
            j && (n[j] = (("." + n[j]) * 1).toFixed(2).slice(2));
            if(!(a = (v = n[j]).slice((l = v.length) % 3).match(/\d{3}/g), v = l % 3 ? [v.slice(0, l % 3)] : [], v = a ? v.concat(a) : v).length) continue;
            for(a = -1, l = v.length; ++a < l; t = ""){
                if(!(i = v[a] * 1)) continue;
                i % 100 < 20 && (t += ex[0][i % 100]) ||
                i % 100 + 1 && (t += ex[1][(i % 100 / 10 >> 0) - 1] + (i % 10 ? e + ex[0][i % 10] : ""));
                s.push((i < 100 ? t : !(i % 100) ? ex[2][i == 100 ? 0 : i / 100 >> 0] : (ex[2][i / 100 >> 0] + e + t)) +
                ((t = l - a - 2) > -1 ? " " + (i > 1 && t > 0 ? ex[3][t].replace("ão", "ões") : ex[3][t]) : ""));
            }
            a = ((sl = s.length) > 1 ? (a = s.pop(), s.join(" ") + e + a) : s.join("") || ((!j && (n[j + 1] * 1 > 0) || r.length) ? "" : ex[0][0]));
            a && r.push(a + (c ? (" " + (v.join("") * 1 > 1 ? j ? d + "s" : (/0{6,}$/.test(n[0]) ? "de " : "") + $.replace("l", "is") : j ? d : $)) : ""));
        }
        return r.join(e);
    }

    String.prototype.hashCode = function() {
        var hash = 0, i, chr;
        if (this.length === 0) return hash;
        for (i = 0; i < this.length; i++) {
          chr   = this.charCodeAt(i);
          hash  = ((hash << 5) - hash) + chr;
          hash |= 0; // Convert to 32bit integer
        }
        return hash;
    };

    Date.prototype.toMysqlFormat = function() {
        return this.getFullYear() + "-" + twoDigits(1 + this.getMonth()) + "-" + twoDigits(this
        .getDate()) + " " + twoDigits(this.getHours()) + ":" + twoDigits(this.getMinutes()) + ":" +
            twoDigits(this.getSeconds());
    };

    

    var instrumentPath = 'assets/img/instrument/'
    var imgPath = 'assets/img/'
    var address = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
    var arrMulti = []
    

    new Vue({
        el: '#dashboard',
        vuetify: new Vuetify(),
        components: {
            Loading: VueLoading,
        },
        //DADOS
        data: () => ({
            //Init
            setIP: {},
            alertLogin: {status: false, msg: ''},
            userNow: {},
            loginEmail: '',
            loginEmailRules: [v => !!v || 'Digite o e-mail para realizar o login'],
            loginPassword: '',
            loginPasswordRules: [v => !!v || 'Digite a senha para realizar o login'],
            drawer: null,
            crud: '',
            mini: true,
            ip: '000.000.000.000', 
            loadingVisible: false,
            contentLoading: false,
            inputLoading: false,
            firstLoad: false,
            headingLoader: false,
            nTax: true,
            nInstrument: true,
            urlApi: 'http://localhost/toquedivino/api/',
            // urlApi: 'https://app.cerimonialtoquedivino.com.br/api/',
            selectedPage: 0,
            show: "home",
            alert: false,
            alertMsg: '',
            currentFile: undefined,
            progressUpload: 0,
            progressShow: false,
            uploadSuccess: false,
            uploadError: false,
            uploadMsg: '',
            //CKEditor
            editor: ClassicEditor,
            editorData: '<p></p>',
            editorConfig: {},
            //mascaras
            maskPhone: '(##) ####-####',
            maskMobile: '(##) #####-####',
            maskCep: '#####-###',
            maskCpf: '###.###.###-##',
            maskCnpj: '##.###.###/####-##',
            maskMoney: '######.##',
            maskBirthdate: '##/##/####',
            // navegador
            pages: [{
                    id: 1,
                    text: "Home",
                    icon: "mdi-home",
                    link: "home",
                    subgroup: false
                },
                {
                    id: 2,
                    text: 'Usuários',
                    icon: 'mdi-account-circle',
                    link: "usuarios",
                    subgroup: false
                },
                {
                    id: 3,
                    text: 'Serviços',
                    icon: 'mdi-bookmark-music',
                    link: null,
                    subgroup: true,
                    subgroups: [
                        {
                            title: 'Taxas',
                            link: 'taxas'
                        },
                        {
                            title: 'Serviços',
                            link: 'servicos'
                        }
                    ]
                },
                {
                    id: 4,
                    text: 'Formações',
                    icon: 'mdi-shape-plus',
                    link: null,
                    subgroup: true,
                    subgroups:[
                        {
                            title: 'Instrumentos',
                            link: 'instrumentos'
                        },
                        {
                            title: 'Formações',
                            link: 'formacoes'
                        }
                    ]
                },
                {
                    id: 5,
                    text: 'Repertório',
                    icon: 'mdi-music',
                    link: null,
                    subgroup: true,
                    subgroups: [
                        {
                            title: 'Momentos',
                            link: 'momentos'
                        },
                        {
                            title: 'Músicas',
                            link: 'musicas'
                        }
                    ]
                },
                {
                    id: 6,
                    text: 'Gerencial',
                    icon: 'mdi-file-document-edit',
                    link: null,
                    subgroup: true,
                    subgroups: [
                        {
                            title: 'Leads',
                            link: 'leads'
                        },
                        {
                            title: 'Cadastros',
                            link: 'cadastros'
                        },
                        {
                            title: 'Contratos',
                            link: 'contratos'
                        },
                        {
                            title: 'Assinaturas',
                            link: 'assinaturas'
                        }
                    ]
                }
            ],
            // cards home
            cards: [{
                    title: 'Contratos',
                    link: 'contratos',
                    typeChart: 'ColumnChart',
                    dataChart: [
                        ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out',
                            'nov', 'dez'
                        ],
                        [10, 15, 25, 19, 30, 24, 36, 20, 26, 30, 32, 35]
                    ]
                },
                {
                    title: 'Financeiro',
                    link: 'financeiro',
                    typeChart: 'PieChart',
                    dataChart: [
                        ['Tipo de Lançamento', 'Valor'],
                        ['Receita', 15000.00],
                        ['Despesa', 10000.00]
                    ]
                },
                {
                    title: 'Contratos',
                    link: 'contratos',
                    typeChart: 'ColumnChart',
                    dataChart: [
                        ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out',
                            'nov', 'dez'
                        ],
                        [10, 15, 25, 19, 30, 24, 36, 20, 26, 30, 32, 35]
                    ]
                },
                {
                    title: 'Financeiro',
                    link: 'financeiro',
                    typeChart: 'PieChart',
                    dataChart: [
                        ['Tipo de Lançamento', 'Valor'],
                        ['Receita', 15000.00],
                        ['Despesa', 10000.00]
                    ]
                }
            ],
            //charts
            chartPreInscribeReport:
                {
                    typeChart: 'PieChart',
                    dataChart: []
                },
            //dialog and forms
            showPassword: false,
            dialogUsers: false,
            dialogFormation: false,
            dialogInstrument: false,
            dialogFormationHasInstrument: false,
            dialogService: false,
            dialogTaxService: false,
            dialogServiceHasTax: false,
            dialogVariantTax: false,
            dialogUploadSound: false,
            dialogInscribe: false,
            dialogLeads: false,
            dialogPreInscribeReport: false,
            dialogContract: false,
            dialogContractSign: false,
            dialogContractTrash: false,
            dialogMoments: false,
            dialogMusic: false,
            dialogSignature: false,
            dialogSignWithPassword: false,
            dialogAnalyzeContract: false,
            pickDateInscribe: false,
            pickDateEvent: false,
            pickTimeEvent: false,
            bsPreInscribe: false,
            bsContent: '',
            sendWhatsapp: '',
            sendEmail: '',
            //form account
            accountId: '',
            accountName: '',
            accountNameRules: [v => !!v || 'O campo NOME DO USUÁRIO é requerido'],
            accountPhoto: imgPath + 'profile.svg',
            accountEmail: '',
            accountEmailRules: [
                v => !!v || 'O campo EMAIL é requerido',
                v => /.+@.+/.test(v) || 'Insira um E-mail válido'
            ],
            accountPhone: '',
            accountPhoneRules: [v => !!v || 'O campo CELULAR é requerido'],
            accountAccessType: '',
            accountAccessTypeRules: [
                v => !!v || 'Por favor selecione um TIPO DE ACESSO'
            ],
            accessType: [
                {
                    name: 'User',
                    value: '1'
                },
                {
                    name: 'Cliente',
                    value: '2'
                },
                {
                    name: 'Músicos',
                    value: '3'
                }
            ],
            accountPassword: '',
            accountPasswordRules: [v => !!v || 'O campo SENHA é requerido'],
            accountStatus: false,
            //users
            searchUsers: '',
            headerUsers: [{
                    text: 'Nome',
                    align: 'start',
                    sortable: false,
                    value: 'name'
                },
                {
                    text: 'E-mail',
                    value: 'email'
                },
                {
                    text: 'Telefone',
                    value: 'phone'
                },
                {
                    text: 'Acesso',
                    value: 'access'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Ações',
                    value: 'actions'
                }
            ],
            users: [],
            //service
            serviceId: '',
            serviceName: '',
            serviceNameRules: [v => !!v || "O campo NOME DO SERVIÇO é requerido"],
            serviceDescription: '',
            serviceDescriptionRules: [v => !!v || 'O campo DESCRIÇÃO DO SERVIÇO é requerido'],
            taxId: '',
            serviceTaxName: '',
            serviceTaxNameRules: [v => !!v || 'O campo TAXA é requerido'],
            serviceTaxDescription: '',
            serviceTaxDescriptionRules: [v => !!v || 'O campo DESCRIÇÃO DA TAXA é requerido'],
            serviceTaxType: '',
            serviceTaxTypeSelect: [{
                    name: 'Fixa',
                    value: '1'
                },
                {
                    name: 'Multiplicada',
                    value: '2'

                }
            ],
            serviceTaxTypeRules: [v => !!v || 'O campo TIPO DE TAXA é requerido'],
            serviceTaxValue: '',
            serviceTaxValueRules: [v => !!v || 'O campo VALOR é requerido'],
            searchService: '',
            headerServices: [{
                    text: 'Serviço',
                    value: 'name'
                },
                {
                    text: 'Taxas',
                    value: 'taxas'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }

            ],
            services: [],
            searchServiceTax: '',
            headerServiceTax: [{
                    text: 'Taxa',
                    value: 'name'
                },
                {
                    text: 'Descrição',
                    value: 'description'
                },
                {
                    text: 'Valor',
                    value: 'value'
                },
                {
                    text: 'Tipo',
                    value: 'type'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            serviceTax: [],
            searchServiceHasTax: '',
            headerServiceHasTax:[
                {
                    text: 'Serviço',
                    value: 'nameService'
                },
                {
                    text: 'Taxa',
                    value: 'nameTax'
                },
                {
                    text: 'Ações',
                    value: 'actions'
                }
            ],
            serviceHasTax: [],
            variantTax: ['por km', 'por pessoa', 'por instrumento', 'por música'],
            variantTaxName: '',
            variantTaxValue: '',
            variantTaxTag: '',
            //formation
            formationId: '',
            formationName: '',
            formationNameRules: [v => !!v || 'O campo NOME DA FORMAÇÃO é requerido'],
            formationDescription: '',
            formationDescriptionRules: [v => !!v || 'O campo DESCRIÇÃO é requerido'],
            formationPrice: '',
            formationPriceRules: [v => !!v || 'O campo PREÇO é requerido'],
            formationVideo: '',
            instrumentId: '',
            instrumentName: '',
            instrumentNameRules: [v => !!v || 'O campo NOME DO INSTRUMENTO é requerido'],
            instrumentIcon: instrumentPath + 'picture.svg',
            instrumentIconRules: [v => !!v || 'O campo IMAGEM DO INSTRUMENTO é requerido'],
            instrumentSound: null,
            instrumentSoundRules: [v => v > 5000000 || 'Arquivo deve ser menor que 5MB'],
            instruments: [{
                    text: 'Cantor solo',
                    icon: instrumentPath + 'singer.svg'
                },
                {
                    text: 'Clarim',
                    icon: instrumentPath + 'trumpet.svg'
                },
                {
                    text: 'Coral de 4 vozes',
                    icon: instrumentPath + 'choir_4voices.svg'
                },
                {
                    text: 'Coral de 8 Vozes',
                    icon: instrumentPath + 'choir.svg'
                },
                {
                    text: 'Dueto Vocal',
                    icon: instrumentPath + 'duet.svg'
                },
                {
                    text: 'Flauta',
                    icon: instrumentPath + 'flute.svg'
                },
                {
                    text: 'Harpa',
                    icon: instrumentPath + 'harp.svg'
                },
                {
                    text: 'Sax',
                    icon: instrumentPath + 'sax.svg'
                },
                {
                    text: 'Percussão',
                    icon: instrumentPath + 'drums.svg'
                },
                {
                    text: 'Piano Digital',
                    icon: instrumentPath + 'piano.svg'
                },
                {
                    text: 'Piano 1/4 de cauda',
                    icon: instrumentPath + 'grand-piano.svg'
                },
                {
                    text: 'Tímpano',
                    icon: instrumentPath + 'drum.svg'
                },
                {
                    text: 'Viola',
                    icon: instrumentPath + 'viola.svg'
                },
                {
                    text: 'Violino',
                    icon: instrumentPath + 'violin.svg'
                },
                {
                    text: 'Violoncelo',
                    icon: instrumentPath + 'cello.svg'
                }
            ],
            searchFormation: '',
            headerFormation: [{
                    text: 'Formação',
                    align: 'start',
                    value: 'name'
                },
                {
                    text: 'Preço',
                    value: 'price'
                },
                {
                    text: 'Instrumentos',
                    value: 'instruments',
                    sortable: false
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            formation: [],
            searchInstrument: '',
            headerInstrument: [{
                    text: 'Instrumento',
                    align: 'start',
                    value: 'name'
                },
                {
                    text: 'Imagem',
                    sortable: false,
                    value: 'image'
                },
                // {
                //     text: 'Audio',
                //     sortable: false,
                //     value: 'sound'
                // },
                {
                    text: 'Ações',
                    sortable: false,
                    value: 'actions'
                }
            ],
            instrument: [],
            searchFormationHasInstrument: '',
            headerFormationHasInstrument:[
                {
                    text: 'Formação',
                    value: 'nameFormation'
                },
                {
                    text: 'Instrumento',
                    value: 'nameInstrument'
                },
                {
                    text: 'Ações',
                    value: 'actions'
                }
            ],
            formationHasInstrument:[],
            //Pre-Inscribe
            idPreInscribe: '',
            pickDatePreInscribe: false,
            pickTimePreInscribe: false,
            preInscribeDatetime: null,
            preInscribeDate: new Date().toISOString().substr(0, 10),
            preInscribeTime: new Date().toLocaleString().substr(11, 8),
            pickIpPreInscribe: false,
            preInscribeIp: '',
            preInscribeIpRule: [v => !V || 'IP inválido'],
            preInscribeAccountable: '',
            preInscribeAccountableRule: [v => !!v || 'O campo NOME DO RESPONSÁVEL é requerido'],
            preInscribeOrigin: '',
            preInscribeOriginRule: [v => !!v || 'O campo ORIGEM pe requerido'],
            preInscribePhone: '',
            preInscribePhoneRule: [v => !!v || 'O campo TELEFONE é requerido'],
            preInscribeMobile: '',
            preInscribeMobileRule: [v => !!v || 'O campo CELULAR  é requerido'],
            preInscribeEmail: '',
            preInscribeEmailRule: [v => !!v || 'O campo E-MAIL é requerido', v => /.+@.+/.test(v) || 'Insira um E-mail válido'],
            preInscribeFlag: null,
            searchPreInscribe: '',
            headersPreInscribe:[
                {
                    text: 'Data',
                    value: 'datetime'
                },
                {
                    text: 'Origem',
                    value: 'origin'
                },
                {
                    text: 'Nome',
                    value: 'accountable'
                },
                {
                    text: 'Telefone',
                    value: 'phone'
                },
                {
                    text: 'Celular',
                    value: 'mobile'
                },
                {
                    text: 'E-mail',
                    value: 'email'
                },
                {
                    text: 'Flag',
                    value: 'flag'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            preInscribe: [],
            preInscribeMenuAction: [{title: 'Enviar E-mail', icon: 'mdi-email-newsletter', content: 'email'}, {title: 'Abrir WhatsApp', icon: 'mdi-whatsapp', content: 'whatsapp'}],
            preInscribeAction: '',
            //Inscribe
            inscribeDatetime: '',
            inscribeAccountable: '',
            inscribeAccountableRules:[v => !!v || 'O campo NOME DO RESPONSÁVEL é requerido'],
            inscribePhone: '',
            inscribePhoneRules: [v => !!v || 'O campo TELEFONE DO RESPONSÁVEL é requerido'],
            inscribeMobile: '',
            inscribeMobileRules: [v => !!v || 'O campo CELULAR DO RESPONSÁVEL é requerido'],
            inscribeEmail: '',
            inscribeEmailRules: [v => !!v || 'O campo E-MAIL é requerido', v => /.+@.+/.test(v) || 'Insira um E-mail válido'],
            inscribeAddress: address,
            inscribeAddressRules: [v => !!v || 'Preencha corretamente o endereço'],
            inscribeCpf: '',
            inscribeRg: '',
            inscribeCnpf: '',
            inscribeIdAccount: '',
            inscribeIdService: '',
            inscribeIdServiceRules:[v => !!v || 'Selecione um serviço'],
            inscribeIdFormation: '',
            inscribeIdFormationRules: [v => !!v || 'Selecione uma formação'],
            inscribeStatus: '',
            searchInscribe: '',
            headersInscribe: [{
                    text: 'Nome',
                    value: 'accountable'
                },
                {
                    text: 'Data',
                    value: 'datetime'
                },
                {
                    text: 'Telefone',
                    value: 'phone'
                },
                {
                    text: 'Celular',
                    value: 'mobile'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            inscribe: [],
            idInscribe: '',
            //Contracts
            contractService: { taxas: []},
            contractFormation: {},
            contractFinish: false,
            contractTaxValue:  [],
            contractFixTaxValue: 0,
            contractTaxVariantValue: 0,
            contractFormationValue: 0,
            contractValue: 0,
            contractValueExtenso: '',
            contractValueTotal: 0,
            contractDiscount: 0,
            contractAddition: 0,
            contractDownPayment: 0,
            contractDownPaymentExtenso: '',
            contractDownPaymentDate: '',
            contractDownPaymentDateRules: [v => !!v || 'O campo DATA DO EVENTO é requerido'],
            contractSignatures: [],
            pickDateDownPayment: false,
            tabContract: null,
            selectEngaged: false,
            selectGraduationCommitte: false,
            selectEventData: false,
            stepContract: 1,
            engagedBrideAccountable: false,
            engagedGroomAccountable: false,
            engagedBrideName: '',
            engagedBrideNameRules: [v => !!v || 'O campo NOME DA NOIVA é requerido'],
            engagedBrideAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
            engagedBrideAddressRules: [v => !!v || 'Este campo é requerido'],
            engagedBridePhone: '',
            engagedBridePhoneRules:[v => !!v || 'O campo TELEFONE DA NOIVA é requerido'],
            engagedBrideMobile: '',
            engagedBrideMobileRules:[v => !!v || 'O campo CELULAR DA NOIVA é requerido'],
            engagedBrideCpf: '',
            engagedBrideRg: '',
            engagedBrideBirthdate: '',
            engagedBrideEmail: '',
            engagedGroomName: '',
            engagedGroomNameRules: [v => !!v || 'O campo NOME DO NOIVO é requerido'],
            engagedGroomAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
            engagedGroomAddressRules: [v => !!v || 'Este campo é requerido'],
            engagedGroomPhone: '',
            engagedGroomPhoneRules: [v => !!v || 'O campo TELEFONE DO NOIVO é requerido'],
            engagedGroomMobile: '',
            engagedGroomMobileRules: [v => !!v || 'O campo CELULAR DO NOIVO é requerido'],
            engagedGroomCpf: '',
            engagedGroomRg: '',
            engagedGroomBirthdate: '',
            engagedGroomEmail: '',
            searchContracts: '',
            graduationCommitteName: '',
            graduationCommitteNameRules: [v => !!v || 'Por favor digite um nome'],
            graduationCommitteMember: [],
            eventName:'',
            eventDate: '',
            eventDateRules: [v => !!v || 'O campo DATA DO EVENTO é requerido'],
            eventTime: '',
            eventTimeRules: [v => !!v || 'O campo HORÁRIO DO EVENTO é requerido'],
            eventAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
            eventAddressRules: [v => !!v || 'Este campo é requerido'],
            searchContract: '',
            headersContracts: [{
                    text: 'Nome do responsável',
                    value: 'accountable'
                },
                {
                    text: 'Código do contrato',
                    value: 'agreement.code'
                },
                {
                    text: 'Evento',
                    value: 'event.name'
                },
                {
                    text: 'Data do contrato',
                    value: 'agreement.date'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            itemsContract: [],
            headersContractsTrash: [
                {
                    text: 'Nome do responsável',
                    value: 'accountable'
                },
                {
                    text: 'Código do contrato',
                    value: 'agreement.code'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            itemsContractTrash: [],
            //Signatures
            signatureId: '',
            signatureUserName: {id: '', name: ''},
            signatureType: '',
            signatureTypes: [
                {
                    text: 'Contratante',
                    type: '1'
                },
                {
                    text: 'Contratado',
                    type: '2'
                },
                {
                    text: 'Testemunha',
                    type: '3'
                }
            ],
            signatureFont: '',
            signaturesFonts: [
                {
                    name: 'Fuggles',
                    signClass: 'gf-fuggles'
                },
                {
                    name: 'Mrs Saint Delafield',
                    signClass:"gf-mrs-saint-delafield"
                },
                {
                    name: 'Sacramento',
                    signClass: 'gf-sacramento'
                },
                {
                    name: 'Shadows Into Light',
                    signClass: 'gf-shadows-into-light'
                },
                {
                    name: 'Caveat',
                    signClass: 'gf-caveat'
                },
                {
                    name: 'Parisienne',
                    signClass: 'gf-parisienne'
                },
                {
                    name: 'Allura',
                    signClass: 'gf-allura'
                },
                {
                    name: 'Alex Brush',
                    signClass: 'gf-alex-brush'
                },
                {
                    name: 'Dawning of a New Day',
                    signClass: 'gf-dawnning-of-a-new-day'
                },
                {
                    name: 'Kristi',
                    signClass: 'gf-kristi'
                },
                {
                    name: 'League Script',
                    signClass: 'gf-league-script'
                },
                {
                    name: 'Pinyon Script',
                    signClass: 'gf-pinyon-script'
                },
                {
                    name: 'Reenie Beanie',
                    signClass: 'gf-reenie-beanie'
                },
                {
                    name: 'Zeyada',
                    signClass: 'gf-zeyada'
                }
            ],
            signatureStatus: false,
            signaturePassword: '',
            signatureWithPassword: {},
            formSignWithPassword: true,
            loadingSignWithPassword: false,
            alertSignWithPassword: false,
            searchSignatures: '',
            headerSignatures:[
                {
                    text: 'Nome do signatário',
                    value: 'name'
                },
                {
                    text: 'Tipo de Assinatura',
                    value: 'type'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Em Uso?',
                    value: 'inuse'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            signatures: [],
            //Moments
            momentsId: '',
            momentsName: '',
            momentsNameRules: [v => !!v || 'O campo NOME DO MOMENTO é requerido'],
            momentsDescription: '',
            momentsDescriptionRules: [v => !!v || 'O campo DESCRIÇÃO é requerido'],
            momentsStatus: true,
            searchMoments: '',
            headerMoments:[
                {
                    text: 'Momentos',
                    value: 'name'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Ações',
                    value: 'actions',
                    sortable: false
                }
            ],
            moments: [],
            //Music
            musicId: '',
            musicName: '',
            musicNameRules: [v => !!v || 'O campo NOME DA MÚSICA é requerido'],
            musicUrl: '',
            musicUrlRules: [v => !!v || 'O campo URL DA MÚSICA é requerido'],
            musicStatus: true,
            searchMusic: '',
            headerMusic: [
                {
                    text: "Música",
                    value: 'name'
                },
                {
                    text: 'URL',
                    value: 'url'
                },
                {
                    text: 'Momentos',
                    value: 'moments'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Ações',
                    value: 'actions'
                }
            ],
            musics: [],
            musicHasMoments: [],
        }),
        // MOUNTED
        mounted() {
            if(localStorage.getItem('userNow')){
                try{
                    this.userNow = JSON.parse(localStorage.getItem('userNow'))
                } catch(e){
                    localStorage.removeItem('userNow')
                    this.userNow = {logged: false, login: true, name: 'Usuário', id: '', photo: 'assets/img/profile.svg'}
                }
            } else{
                this.userNow = {logged: false, login: true, name: 'Usuário', id: '', photo: 'assets/img/profile.svg'}
            }
        },
        //CREATED
        created() {
            
        },
        //METHODS
        methods: {
            //configs
            isActive: function(val) {
                return this.show === val
            },
            maskTel: function(phone){
                if(!!phone) {
                    return phone.length == 15 ? this.maskMobile : this.maskPhone
                } else {
                    return this.maskMobile
                }
            },
            printMoeda: function(value){
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            onChangeIP: function(ip, port, valid){
                this.preInscribeIp = ip
                console.log(ip, port, valid)
            },
            resetAllDatas: function(){
                this.users = []
                this.services = []
                this.serviceTax = []
                this.serviceHasTax = []
                this.formation = []
                this.formationHasInstrument = []
                this.instrument = []
                this.inscribe = []
                this.preInscribe = []
                this.itemsContract = []
                this.chartPreInscribeReport.dataChart = []
                this.moments = []
                this.musics = []
                this.musicHasMoments = []
                this.preInscribeFlag = null
                this.signatures = []
                this.contractTaxValue = []
                this.crud = ''
            },
            showActive: function(val) {
                this.show = val
                this.resetAllDatas()
                switch (val) {
                    case 'usuarios':
                        this.getUsers()
                        break
                    case 'servicos':
                        this.getServices()
                        this.getTax()
                        break
                    case 'taxas':
                        this.getTax()
                        break
                    case 'formacoes':
                        this.getFormations()
                        this.getInstrument()
                        break
                    case 'instrumentos':
                        this.getInstrument()
                        break
                    case 'contratos':
                        this.getContracts()
                        this.getIP()
                        break
                    case 'cadastros':
                        this.getInscribe()
                        this.getServices()
                        this.getFormations()
                        break
                    case 'leads':
                        this.getPreInscribe()
                        break
                    case 'momentos':
                        this.getMoments()
                        break
                    case 'musicas':
                        this.getMusic()
                        this.getMoments()
                        break
                    case 'assinaturas':
                        this.getSignature()
                }
            },
            openBottomSheet: function(bs, content=null){
                this[bs] = !this[bs]
                this.bsContent = content
            },
            openWhatsapp: function(phone){
                let msg = encodeURI(this.sendWhatsapp)
                let number = phone.replace(/\D/g, '')
                window.open('https://wa.me/55' + number + '/?text=' + msg)
            },
            showContractFinish: function(item){
                this.crud = 'c'
                this.contractFinish = true
                this.idInscribe = item.idinscribe
                this.inscribeAccountable = item.accountable
                this.inscribePhone = item.phone
                this.inscribeMobile = item.mobile
                this.eventName = item.service.name
                this.contractFormation = item.formation
                this.contractService = item.service
            },
            hideContractFinish: function(){
                this.crud = ''
                this.contractFinish = false
                this.stepContract = 1
                this.idInscribe = ''
                this.inscribeAccountable = ''
                this.inscribePhone = ''
                this.inscribeMobile = ''
                this.contractService = { taxas: []}
                this.contractFormation = {}
                this.contractTaxValue = []
                this.$ci.setValue(this.$refs.inputContractDiscount, 0)
                this.$ci.setValue(this.$refs.inputContractAddition, 0) 
                this.contractFormationValue = 0
                this.contractTaxVariantValue = 0
                this.contractFixTaxValue = 0
                this.contractValue = 0
                this.contractValueTotal = 0
                this.$ci.setValue(this.$refs.inputContractDownPayment, 0)
                this.contractDownPaymentDate = ''
                this.selectEngaged = false
                this.selectGraduationCommitte = false
                this.$refs.formEvent.reset()
            },
            openAnalyzeContract: function(item){
                this.dialogAnalyzeContract = true
                this.idInscribe = item.idinscribe
                this.inscribeAccountable = item.accountable
                this.inscribePhone = item.phone
                this.inscribeMobile = item.mobile
                this.inscribeAddress = item.address
                this.inscribeCpf = item.cpf
                this.inscribeRg = item.rg
                this.contractFormation = item.formation
                this.contractService = item.service
                this.calculateContractTaxValue()
                this.calculateContractFormationValue()
                this.calculateContractValue()
            },
            closeDialogAnalyzeContract: function(){
                this.dialogAnalyzeContract = false
                this.idInscribe = ''
                this.inscribeAccountable = ''
                this.inscribePhone = ''
                this.inscribeMobile = ''
                this.inscribeAddress = ''
                this.inscribeCpf = ''
                this.inscribeRg = ''
                this.formationName = ''
                this.serviceName = ''
                this.contractService = {taxas:[]}
                this.contractFormation = {}
                this.contractTaxValue = []
                this.$ci.setValue(this.$refs.inputContractDiscount, 0)
                this.$ci.setValue(this.$refs.inputContractAddition, 0) 
                this.contractFormationValue = 0
                this.contractTaxVariantValue = 0
                this.contractFixTaxValue = 0
                this.contractValue = 0
                this.contractValueTotal = 0
                this.$ci.setValue(this.$refs.inputContractDownPayment, 0)
                this.contractDownPaymentDate = ''
            },
            closeDialog: function(d, form=null) {
                this[d] = false
                if (form != null) {
                   this.$refs[form].reset()
                }
                this.serviceTaxId = ''
                this.serviceHasTax = []
            },
            closeDialogSignature: function(){
                this.dialogSignature = false
                this.signatureUserName = {id: '', name: ''}
                this.signatureType = ''
                this.signatureFont = ''
                this.signatureStatus = true
                this.users = []
            },
            closeDialogContract: function(){
                this.dialogContract = false
                // this.clearFormEngaged()
                // this.clearFormGraduationCommitte()
                // this.clearFormEvent()
                this.selectEngaged = false
                this.selectGraduationCommitte = false
                this.selectEventData = false
                this.idInscribe = ''
                this.inscribeAccountable = ''
                this.contractEvent = ''
                this.contractFormation = {}
                this.stepContract = 1
            },
            closeDialogContractTrash: function(){
                this.dialogContractTrash = false
                this.itemsContractsTrash = []
            },
            closeDialogPreInscribeReport: function(){
                this.dialogPreInscribeReport = false
                this.chartPreInscribeReport.dataChart = []
            },
            openDialogContractSign: function(item){
                this.dialogContractSign = true
                this.inscribeAccountable = item.accountable
                this.inscribeCpf = item.cpf
                this.inscribeRg = item.rg
                this.inscribePhone = item.phone
                this.inscribeMobile = item.mobile
                this.selectEngaged = (item.engaged != null) ? true : false
                if(item.engaged != null){
                    this.engagedGroomName = item.engaged.groom_name
                    this.engagedGroomAddress = JSON.parse(item.engaged.groom_address)
                    this.engagedGroomPhone = item.engaged.groom_phone
                    this.engagedGroomMobile = item.engaged.groom_mobile
                    this.engagedGroomCpf = item.engaged.groo_cpf
                    this.engagedGroomRg = item.engaged.groom_rg
                    this.engagedGroomBirthdate = toDateFormat(item.engaged.groom_birthdate)
                    this.engagedGroomEmail = item.engaged.groom_email
                    this.engagedBrideName = item.engaged.bride_name
                    this.engagedBrideAddress = JSON.parse(item.engaged.bride_address)
                    this.engagedBridePhone = item.engaged.bride_phone
                    this.engagedBrideMobile = item.engaged.bride_mobile
                    this.engagedBrideCpf = item.engaged.bride_cpf
                    this.engagedBrideRg = item.engaged.bride_rg
                    this.engagedBrideBirthdate = toDateFormat(item.engaged.bride_birthdate)
                    this.engagedBrideEmail = item.engaged.bride_email
                }
                this.contractValueTotal = item.agreement.totalValue
                this.contractValueExtenso = item.agreement.totalValue.extenso()
                this.contractDownPayment = item.agreement.down_payment
                this.contractDownPaymentExtenso = item.agreement.down_payment.extenso()
                this.contractDownPaymentDate = toDateFormat(item.agreement.down_payment_date)
                this.contractSignatures = item.signatures
            },
            closeDialogContractSign: function(){
                this.dialogContractSign = false
                this.inscribeAccountable = ''
                this.inscribeCpf = ''
                this.inscribeRg = ''
                this.inscribePhone = ''
                this.inscribeMobile = ''
                this.selectEngaged = false
                this.engagedGroomName = ''
                this.engagedGroomAddress = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
                this.engagedGroomPhone = ''
                this.engagedGroomMobile = ''
                this.engagedBrideName = ''
                this.engagedBrideAddress = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
                this.engagedBridePhone = ''
                this.engagedBrideMobile = ''
                this.contractValueTotal = ''
                this.contractValueExtenso = ''
                this.contractDownPayment = ''
                this.contractDownPaymentExtenso = ''
                this.contractDownPaymentDate = ''
                this.contractSignatures = []
            },
            selectFile: function(file) {
                this.currentFile = file.name
            },
            readAccountPhoto: function(file){
                if(file){
                    this.accountPhoto = URL.createObjectURL(file)
                    this.progressShow = true
                    

                    let data = new FormData()
                    data.append('file', file)

                    axios.post(this.urlApi + 'uploadPhoto', data, {
                        onUploadProgress: (event) => {
                            const totalLength = event.lengthComputable ? event.total : event.target.getResponseHeader('content-length') || event.target.getResponseHeader('x-decompressed-content-length')
                            console.log("onUploadProgress", totalLength)
                            if(totalLength !== null){
                                this.progressUpload = Math.round( (event.loaded * 100) / totalLength )
                            }
                        }
                    })
                        .then(response => {
                            (response.data.type == 'success') ? this.uploadSuccess = true : this.uploadError = true
                            this.uploadMsg = response.data.msg
                            this.uploadShow = false
                        })
                        .catch(error => {
                            this.uploadError = true
                            this.uploadMsg = error
                        })
                } else{
                    this.accountPhoto = imgPath + 'profile.svg'
                    this.uploadShow = false
                    this.uploadMsg = ''
                    this.uploadSuccess = false
                    this.uploadError = false
                }
            },
            addMemberGraduationCommitte: function(){
                if(this.$refs.formGraduationCommitte.validate()){
                    this.graduationCommitteMember.push(this.graduationCommitteName)
                    this.clearFormGraduationCommitte()
                    this.alert = false
                    this.alertMsg = ''
                }
            },
            removeMemberGraduationCommitte: function(i, n){
                this.graduationCommitteMember.splice(i, n)
            },
            cancelContract: function(id){
                Swal.fire({
                    title: 'Deseja cancelar o contrato',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Cancelar Contrato',
                    cancelButtonText: 'Não cancelar'
                }).then((result) => {
                    if(result.isConfirmed) {
                        fetch(this.urlApi + 'contractCancel/' + id)
                        .then(response => response.json())
                        .then(json => {
                            Swal.fire(json.msg, '', json.icon)
                            this.getContracts()
                        })
                    }
                })
            },
            undoContract: function(id){
                Swal.fire({
                    title: 'Deseja restaurar o contrato',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Restaurar Contrato',
                    cancelButtonText: 'Não restaurar'
                }).then((result) => {
                    if(result.isConfirmed) {
                        fetch(this.urlApi + 'contractUndo/' + id)
                        .then(response => response.json())
                        .then(json => {
                            Swal.fire(json.msg, '', json.icon)
                            this.getContractsTrash()
                            this.getContracts()
                        })
                    }
                })
            },
            
            //clear
            clearFormUsers: function() {
                this.$refs.formAccount.reset()
                this.progressShow = false
                this.uploadSuccess = false
                this.uploadError = false
            },
            clearFormService: function() {
                this.$refs.formService.reset()
            },
            clearFormFormation: function() {
                this.$refs.formFormation.reset()
            },
            clearFormInstrument: function() {
                this.$refs.formInstrument.reset()
                this.instrumentIcon = instrumentPath + 'picture.svg'
            },
            clearFormServiceTax: function() {
                this.$refs.formServiceTax.reset()
            },
            clearFormServiceHasTax: function(){
                this.$refs.formServiceHasTax.reset()
            },
            clearFormFormationHasInstrument: function(){
                this.$refs.formFormationHasInstrument.reset()
            },
            clearFormInscribe: function(){
                this.$refs.formInscribe.reset()
            },
            clearFormPreInscribe: function(){
                this.$refs.formPreInscribe.reset()
                this.ip = ''
            },
            clearFormEngaged: function(){
                this.$refs.formEngaged.reset()
            },
            clearFormGraduationCommitte: function(){
                this.$refs.formGraduationCommitte.reset()
            },
            clearFormEvent: function(){
                this.$refs.formEvent.reset()
            },
            clearFormMoments: function(){
                this.$refs.formMoments.reset()
            },
            clearFormMusic: function(){
                this.$refs.formMusic.reset()
            },
            //Actions e controls
            selectComplementData: function(data){
                switch(data){
                    case 'casamento':
                        this.selectEngaged = (this.selectEngaged) ? false : true
                        this.selectGraduationCommitte = false
                        this.clearFormEngaged()
                        break
                    case 'formatura':
                        this.selectEngaged = false
                        this.selectGraduationCommitte = (this.selectGraduationCommitte) ? false : true
                        this.clearFormEngaged()
                        if(this.selectGraduationCommitte == false){
                            this.graduationCommitteMember.pop()
                        }
                        break
                    default:
                        this.selectEngaged = false
                        this.selectGraduationCommitte = false
                        break
                }
            },
            unselectComplementDatas: function(){
                this.selectEngaged = false
                this.selectGraduationCommitte = false
            },
            nextStep: function(){
                if(this.selectEngaged){
                    if(this.$refs.formEngaged.validate()){
                        this.stepContract = this.stepContract + 1
                    }
                } else if(this.selectGraduationCommitte){
                    if(this.graduationCommitteMember.length > 0){
                        this.alert = false
                        this.alertMsg = ''
                        this.stepContract = this.stepContract + 1
                    } else{
                        this.alert = true
                        this.alertMsg = 'Por favor acrescente pelo menos uma membro ao comitê de formatura'
                    }
                } else if(this.stepContract == 3){
                    if(this.$refs.formEvent.validate()){
                        this.calculateContractTaxValue()
                        this.calculateContractFormationValue()
                        this.calculateContractValue()
                        this.stepContract = this.stepContract + 1
                    }
                }
                else{
                    this.stepContract = this.stepContract + 1
                }
                
            },
            prevStep: function(){
                this.stepContract = this.stepContract - 1
            },
            selectAccountableBride: function(){
                if(this.engagedBrideAccountable){
                    this.engagedBrideName = this.inscribeAccountable
                    this.engagedBridePhone = this.inscribePhone
                    this.engagedBrideMobile = this.inscribeMobile
                    this.engagedGroomAccountable = false
                } else{
                    this.engagedBrideName = ''
                    this.engagedBridePhone = ''
                    this.engagedBrideMobile = ''
                }
            },
            selectAccountableGroom: function(){
                if(this.engagedGroomAccountable){
                    this.engagedGroomName = this.inscribeAccountable
                    this.engagedGroomPhone = this.inscribePhone
                    this.engagedGroomMobile = this.inscribeMobile
                    this.engagedBrideAccountable = false
                } else{
                    this.engagedGroomName = ''
                    this.engagedGroomPhone = ''
                    this.engagedGroomMobile = ''
                }
            },
            setDataPreInscribe: function(){
                this.preInscribeDatetime = new Date().toISOString().substr(0, 10)
                this.preInscribeOrigin = 'Toque Divino'
            },
            setDataInscribe: function(){
                this.inscribeDatetime = new Date().toISOString().substr(0, 10)
                this.inscribePhone = null
                this.inscribeMobile = null
                this.inscribeAddress.zipcode = null
                this.inscribeCpf = null
            },
            setCode: function(){
                let text = ''
                let numbers = '0123456789'
                
                for(var i = 0; i < 8; i++){
                    text += numbers.charAt(Math.floor(Math.random() * numbers.length))
                }

                let year = new Date().getFullYear()

                return year + text
            },
            calculateContractTaxValue: function(){
                let value = 0
                    if(this.contractService.taxas != null){
                        this.contractService.taxas.forEach(function(t){
                            if(t.type == 1){
                                value = parseFloat(value) + parseFloat(t.value)
                             }
                        })
                    }   
                this.contractFixTaxValue = value
            },
            calculateContractFormationValue: function(){
                this.contractFormationValue = this.contractFormation.price
            },
            addMultipliedContractValue: function(){
                let sMultiplied = 0
                if(this.contractService.taxas != null){
                    this.contractService.taxas.forEach(function(item){
                        sMultiplied += parseFloat(item.value) * parseFloat(item.vValue)
                    })
    
                    this.contractTaxVariantValue = sMultiplied
                } else{
                    this.contractTaxVariantValue = 0
                }
                
            },
            calculateContractValue: function(){
                let value = parseFloat(this.contractFormationValue) + parseFloat(this.contractFixTaxValue) + parseFloat(this.contractTaxVariantValue)
                this.contractValue = value
                this.contractValueTotal = value
            },
            calculateContractValueTotal: function(){
                this.contractValueTotal = parseFloat(this.contractValue) - this.$ci.parse(this.contractDiscount) + this.$ci.parse(this.contractAddition)
            },
            enterLogin: function(){
                if(this.$refs.formLogin.validate()){
                    let data = new FormData()
                    data.append('email', this.loginEmail)
                    data.append('password', this.loginPassword)
                    this.loadingVisible = true
                    axios(this.urlApi + 'login', {
                        method: 'POST',
                        data: data
                    })
                    .then(response =>{
                        this.userNow = response.data.userNow
                        const parsed = JSON.stringify(response.data.userNow)
                        localStorage.setItem('userNow', parsed)
                        this.alertLogin = response.data.alert
                        this.loadingVisible = false
                        this.loginEmail = ''
                        this.loginPassword = ''
                    })
                }
            },
            logout: function(){
                localStorage.removeItem('userNow')
                this.userNow.name = 'Usuário'
                this.userNow.photo = 'assets/img/profile.svg'
                this.userNow.logged = false
                this.userNow.login = true
            },
            sortContractSign: function(array){
                return _.orderBy(array, 'type', 'asc')
            },
            upperCase: function(text){
                return _.upperCase(text)
            },
            signWithPassword: function(item){
                this.dialogContractSignature = false
                this.dialogSignWithPassword = true
                this.signatureWithPassword = item
            },
            doSignWithPassword: function(id){
                this.formSignWithPassword = false
                this.loadingSignWithPassword = true
                let data = new FormData()
                data.append('password', this.signaturePassword)
                data.append('idaccount', id)
                data.append('date', new Date().toMysqlFormat())
                data.append('ip', this.setIP.ip)
                data.append('geolocation', this.setIP.latitude + ',' + this.setIP.longitude)
                data.append('hash', this.signaturePassword.hashCode())
                axios(this.urlApi + 'signWithPassword/', {
                    method: 'POST',
                    data: data
                })
                .then(response =>{
                    if(response.data){
                        this.loadingSignWithPassword = false
                        this.dialogSignWithPassword = false
                    } else{
                        this.loadingSignWithPassword = false
                        this.formSignWithPassword = true
                        this.alertSignWithPassword = true
                    }
                })
            },
            getIP: function(){
                axios.get('https://api.ipgeolocation.io/ipgeo?apiKey=641aff88cd584ec7815acacaff6dada7&fields=geo')
                .then(response => {
                    this.setIP = response.data
                })
            },
            //edits
            editServiceTax: function(item) {
                this.dialogTaxService = true
                this.serviceTaxName = item.name
                this.crud = 'u'
                this.taxId = item.idtax
                this.serviceTaxDescription = item.description
                this.serviceTaxType = item.type
                this.variantTaxName = item.multiplied
                this.serviceTaxValue = item.value
            },
            editUsers: function(item) {
                this.dialogUsers = true
                this.crud = 'u'
                this.accountId = item.idaccount
                this.accountName = item.name
                this.accountEmail = item.email
                this.accountPhone = item.phone
                this.accountAccessType = item.access
                this.accountPassword = item.password
                this.accountStatus = item.status
                this.accountPhoto = item.photo
            },
            editServices: function(item) {
                this.dialogService = true
                this.crud = 'u'
                this.serviceId = item.idservice
                this.serviceName = item.name
                this.serviceDescription = item.description
                this.serviceHasTax = (item.taxas) ? item.taxas.map(item => item.idtax) : []
            },
            editFormation: function(item) {
                this.dialogFormation = true
                this.crud = 'u'
                this.formationId = item.idformation
                this.formationName = item.name
                this.formationDescription = item.description
                this.formationPrice = item.price
                this.formationHasInstrument = item.instruments.map(item => item.idinstrument)
                this.formationVideo = item.video
            },
            editInstrument: function(item) {
                this.dialogInstrument = true
                this.crud = 'u'
                this.nInstrument = false
                this.instrumentId = item.idinstrument
                this.instrumentName = item.name
                this.instrumentIcon = item.image
                this.instrumentSound = item.sound
            },
            addInscribe: function(item){
                this.dialogInscribe = true
                this.crud = 'c'
                this.inscribeDatetime = item.datetime
                this.inscribeAccountable = item.accountable
                this.inscribePhone = item.phone
                this.inscribeMobile = item.mobile
                this.inscribeEmail = item.email
                this.preInscribeFlag = 1
                this.idPreInscribe = item.idpre_inscribe
                this.getFormations()
                this.getServices()
            },
            editInscribe: function(item){
                this.dialogInscribe = true
                this.idInscribe = item.idinscribe
                this.crud = 'u'
                this.inscribeDatetime = item.datetime
                this.inscribeAccountable = item.accountable
                this.inscribePhone = item.phone
                this.inscribeMobile = item.mobile
                this.inscribeIdService = item.service.idservice
                this.inscribeIdFormation = item.formation.idformation

                if(item.address){
                    this.inscribeAddress.street = item.address.street
                    this.inscribeAddress.number = item.address.number
                    this.inscribeAddress.complement = item.address.complement
                    this.inscribeAddress.neighborhood = item.address.neighborhood
                    this.inscribeAddress.city = item.address.city
                    this.inscribeAddress.zipcode = item.address.zipcode
                    this.inscribeAddress.state = item.address.state
                    this.inscribeAddress.country = item.address.country
                }
                
                this.inscribeCpf = item.cpf
                this.inscribeRg = item.rg
                this.inscribeIdAccount = item.account.idaccount
                this.inscribeEmail = item.account.email
                this.inscribeStatus = item.status
            },
            editMoments: function(item){
                this.dialogMoments = true,
                this.crud = 'u'
                this.momentsId = item.idmoments
                this.momentsName = item.name
                this.momentsDescription = item.description
                this.momentsStatus = item.status
            },
            editMusic: function(item){
                this.dialogMusic = true,
                this.crud = 'u'
                this.musicId = item.idmusic
                this.musicName = item.name
                this.musicUrl = item.url
                this.musicStatus = item.status
                this.musicHasMoments = item.moments.map(item => item.idmoments)
            },
            editSignature: function(item){
                this.dialogSignature = true
                this.crud = 'u'
                this.signatureUserName.name = item.name
                this.signatureUserName.idaccount = item.account_idaccount
                this.signatureType = item.type
                this.signatureFont = item.font
                this.signatureStatus = item.status
                this.signatureId = item.idsignature
            },
            //deletes
            deleteUser: function(id) {
                Swal.fire({
                    title: 'Deseja deletar usuário?',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteUser/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getUsers()
                        })
                    }
                })
            },
            deleteService: function(id) {
                Swal.fire({
                    title: 'Deseja deletar serviço?',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteService/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getServices()
                        })
                    }
                })
            },
            deleteTax: function(id) {
                Swal.fire({
                    title: 'Deseja deletar taxa?',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteTax/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getTax()
                        })
                    }
                })
            },
            deleteFormation: function(id) {
                Swal.fire({
                    title: 'Deseja deletar formação?',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteFormation/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getFormation()
                        })
                    }
                })
            },
            deleteInstrument: function(id) {
                Swal.fire({
                    title: 'Deseja deletar instrumento?',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteInstrument/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getInstrument()
                        })
                    }
                })
            },
            deleteSound: function(id){
                fetch(this.urlApi + 'deleteSound/' + id)
                    .then(response => response.json())
                    .then(json => {
                        Swal.fire(json.msg, '', json.icon)
                        this.getInstrument()
                    })
            },
            deleteInscribe: function(id){
                Swal.fire({
                    title: 'Deseja deletar cadastro?',
                    icon: 'question',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteInscribe/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            console.log(response.data)
                            this.getInscribe()
                        })
                    } 
                })
            },
            deleteMoments: function(id){
                Swal.fire({
                    title: 'Deseja deletar o momento?',
                    icon: 'question',
                    confirmButtonText: 'Deletar',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true
                }).then((result) => {
                    if(result.isConfirmed){
                        axios.get(this.urlApi + 'deleteMoments/' + id)
                        .then(response =>{
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getMoments()
                        })
                    }
                })
            },
            deleteMusic: function(id){
                Swal.fire({
                    title: 'Deseja deletar a música?',
                    icon: 'question',
                    confirmButtonText: 'Deletar',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true
                }).then((result) => {
                    if(result.isConfirmed){
                        axios.get(this.urlApi + 'deleteMusic/' + id)
                        .then(response =>{
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getMusic()
                        })
                    }
                })
            },
            deleteContract: function(id){
                Swal.fire({
                    title: 'Deseja deletar definitivamente o contrato?',
                    icon: 'question',
                    text: 'Isso irá remover todos os dados do contrato, incluindo os dados do cadastro do cliente.',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteContract/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            console.log(response.data)
                            this.getContractsTrash()
                        })
                    } 
                })
            },
            removeContract: function(id){
                Swal.fire({
                    title: 'Deseja remover o contrato?',
                    text: 'Isso irá remover os dados do contrato, mas irá manter os dados do cadastro do cliente.',
                    icon: 'question',
                    confirmButtonText: 'Remover',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'removeContract/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            console.log(response.data)
                            this.getContractsTrash()
                        })
                    } 
                })
            },
            deleteSignature: function(id){
                Swal.fire({
                    title: 'Deseja deletar assinatura?',
                    icon: 'question',
                    confirmButtonText: 'Deletar',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.urlApi + 'deleteSignature/' + id)
                        .then(response => {
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getSignature()
                        })
                    }
                })
            },
            hasVariantTax: function(item) {
                if (item.type == 2) {
                    return true
                } else {
                    return false
                }
            },
            stopContentLoading: function() {
                this.contentLoading = false
                this.firstLoad = false
            },
            //gets
            getUsers: function() {
                this.firstLoad = true
                axios.get(this.urlApi + 'getUsers')
                    .then(response => {
                        this.users = response.data
                        this.stopContentLoading()
                    })
            },
            getServices: function() {
                this.firstLoad = true
                axios.get(this.urlApi + 'getServices')
                    .then(response => {
                        this.services = response.data
                        this.stopContentLoading()
                    })
            },
            getService: function(id) {
                this.headingLoader = true
                axios.get(this.urlApi + 'getServices/' + id )
                    .then(response => {
                        this.contractService = response.data
                        this.headingLoader = false
                    })
            },
            getTax: function() {
                this.firstLoad = true
                axios.get(this.urlApi + 'getTax')
                    .then(response => {
                        this.serviceTax = response.data
                        this.stopContentLoading()
                    })
            },
            getTaxByService: function(id){
                axios.get(this.urlApi + 'getTaxByService/' + id)
                .then(response => {
                    const resp = response.data
                    if(resp){
                        this.contractTaxValue = resp
                    }
                })
            },
            getServiceHasTax: function() {
                this.firstLoad = true
                axios.get(this.urlApi + 'getServiceHasTax')
                    .then(response => {
                        this.serviceHasTax = response.data
                        this.firstLoad = false
                    })
            },
            getFormations: function() {
                this.firstLoad = true
                axios.get(this.urlApi + 'getFormation')
                    .then(response => {
                        this.formation = response.data
                        this.stopContentLoading()
                    })
            },
            getFormation: function(id){
                this.headingLoader = true
                axios.get(this.urlApi + 'getFormation/' + id)
                    .then(response => {
                        this.contractFormation = response.data
                        this.headingLoader = false
                    })
            },
            getInstrument: function() {
                this.firstLoad = true
                axios.get(this.urlApi + 'getInstrument')
                    .then(response => {
                        this.instrument = response.data
                        this.stopContentLoading()
                    })
            },
            getFormationHasInstrument: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getFormationHasInstrument')
                    .then(response => {
                        this.formationHasInstrument = response.data
                        this.firstLoad = false
                    })
            },
            getPreInscribe: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getPreInscribe')
                    .then(response => {
                        this.preInscribe = response.data
                        this.stopContentLoading()
                    })
            },
            getInscribe: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getInscribe')
                    .then(response => {
                        this.inscribe = response.data
                        this.stopContentLoading()
                    })
            },
            getContracts: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getContracts')
                    .then(response => {
                        this.itemsContract = response.data
                        this.stopContentLoading()
                    })
            },
            getContractsTrash: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getContracts/3')
                    .then(response => {
                        this.itemsContractTrash = response.data
                        this.stopContentLoading()
                    })
            },
            getMoments: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getMoments')
                .then(response => {
                    this.moments = response.data
                    this.stopContentLoading()
                })
            },
            getMusic: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getMusic')
                .then(response => {
                    this.musics = response.data
                    this.stopContentLoading()
                })
            },
            getCEP: function(obj){
                
                var cep = this[obj].zipcode.replace(/\D/g, '')

                this.inputLoading = true

                this[obj].street = 'Carregando...'
                this[obj].neighborhood = 'Carregando...'
                this[obj].city = 'Carregando...'
                this[obj].state = 'Carregando...'
                this[obj].country = 'Carregando...'

                if(cep != ""){
                    var validacep = /^[0-9]{8}$/
                    if(validacep.test(cep)){
                        axios.get('https://viacep.com.br/ws/' + cep + '/json/')
                        .then(response => {
                            this.inputLoading = false
                            this[obj].street = response.data.logradouro
                            this[obj].neighborhood = response.data.bairro
                            this[obj].city = response.data.localidade
                            this[obj].state = response.data.uf
                            this[obj].country = 'Brasil'
                        })
                        .catch((err) => {
                            this.inputLoading = false
                            Swal.fire(err.message, '', 'error')
                        })
                    } else{
                        this.inputLoading = false
                        Swal.fire('CEP inválido', '', 'error')
                        this[obj].zipcode = ''
                    }
                } else{
                    Swal.fire('Por favor digite CEP!', '', 'error')
                }
            },
            getLogMarketing: function(id){
                this.dialogPreInscribeReport = true
                this.firstLoad = true
                axios.get(this.urlApi + 'getLogMarketing/' + id)
                    .then(response => {
                        this.chartPreInscribeReport.dataChart = response.data
                        this.firstLoad = false
                    })
            },
            getSignature: function(){
                this.firstLoad = true
                axios.get(this.urlApi + 'getSignature')
                    .then(response => {
                        this.signatures = response.data
                        this.stopContentLoading()
                    })
            },
            getIp: function(){
                axios.get('http://ip-api.com/json')
                .then(response => {
                    this.preInscribeIp = response.data.query
                    console.log(response.data.query)
                })
            },
            //saves
            saveUser: function() {
                if(this.$refs.formAccount.validate()){
                    this.dialogUsers = false
                    this.loadingVisible = true
                    if(this.accountStatus){
                        this.accountStatus = 1
                    } else{
                        this.accountStatus = 0
                    }
                    let data = new FormData()
                    data.append('name', this.accountName)
                    data.append('email', this.accountEmail)
                    data.append('password', this.accountPassword)
                    data.append('status', this.accountStatus)
                    data.append('phone', this.accountPhone)
                    data.append('photo', (this.currentFile) ? 'assets/uploads/' + this.currentFile.name : '')
                    data.append('access', this.accountAccessType)
                    if (this.crud == 'u') {
                        axios(this.urlApi + 'updateUser/' + this.accountId, {
                                method: 'POST',
                                data: data
                            })
                            .then(response => {
                                this.loadingVisible = false
                                this.progressShow = false
                                this.uploadError = false
                                this.uploadSuccess = false
                                this.accountId = ''
                                this.accountName = ''
                                this.accountEmail = ''
                                this.accountPhone = ''
                                this.accountAccessType = ''
                                this.accountPassword = ''
                                this.accountPhoto = this.imgPath + 'profile.svg'
                                this.users = []
                                Swal.fire(response.data.msg, '', response.data.icon)
                                this.getUsers()
                            })
                    } else if (this.crud == 'c') {
                        axios(this.urlApi + 'createUser', {
                                method: 'POST',
                                data: data
                            })
                            .then(response => {
                                this.loadingVisible = false
                                this.progressShow = false
                                this.uploadError = false
                                this.uploadSuccess = false
                                this.accountId = ''
                                this.accountName = ''
                                this.accountEmail = ''
                                this.accountPhone = ''
                                this.accountAccessType = ''
                                this.accountPassword = ''
                                this.accountPhoto = this.imgPath + 'profile.svg'
                                this.users = []
                                Swal.fire(response.data.msg, '', response.data.icon)
                                this.getUsers()
                            })
                    }
                }
            },
            saveService: function() {
                this.dialogService = false
                this.loadingVisible = true
                let data = new FormData()
                data.append('name', this.serviceName)
                data.append('description', this.serviceDescription)
                data.append('taxas', JSON.stringify(this.serviceHasTax))
                if (this.crud == 'c') {
                    axios(this.urlApi + 'createService', {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormService()
                            this.serviceId = ''
                            this.serviceName = ''
                            this.serviceDescription = ''
                            this.service = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getServices()
                        })
                } else if (this.crud == 'u') {
                    axios(this.urlApi + 'updateService/' + this.serviceId, {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormService()
                            this.serviceId = ''
                            this.serviceName = ''
                            this.serviceDescription = ''
                            this.service = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getServices()
                        })
                }
            },
            newServiceTax: function() {
                this.nTax = false
                this.clearFormServiceTax()
                this.crud = 'c'
            },
            : function() {
                this.closeDialog('dialogTaxService' )
                this.loadingVisible = true
                let data = new FormData()
                let taxId = this.serviceTaxId
                data.append('name', this.serviceTaxName)
                data.append('description', this.serviceTaxDescription)
                data.append('value', this.serviceTaxValue)
                data.append('type', this.serviceTaxType)
                data.append('multiplied', this.variantTaxName)
                if (this.crud == 'c') {
                    axios(this.urlApi + 'createTax', {
                            method: 'POST',
                            data: data
                        })saveServiceTax
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormServiceTax()
                            this.taxId = ''
                            this.serviceTaxName = ''
                            this.serviceTaxDescription = ''
                            this.serviceTaxType = ''
                            this.serviceTaxValue = ''
                            this.serviceTax = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getTax()
                        })
                } else if (this.crud == 'u') {
                    axios(this.urlApi + 'updateTax/' + this.taxId, {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormServiceTax()
                            this.taxId = ''
                            this.serviceTaxName = ''
                            this.serviceTaxDescription = ''
                            this.serviceTaxType = ''
                            this.serviceTaxValue = ''
                            this.serviceTax = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getTax()
                        })
                }
                
            },
            saveFormation: function() {
                this.closeDialog('dialogFormation')
                this.loadingVisible = true
                let data = new FormData()
                data.append('name', this.formationName)
                data.append('description', this.formationDescription)
                data.append('price', this.formationPrice)
                data.append('instruments', JSON.stringify(this.formationHasInstrument))
                data.append('video', this.formationVideo)
                if (this.crud == 'c') {
                    axios(this.urlApi + 'createFormation', {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormFormation()
                            this.formationId = ''
                            this.formationName = ''
                            this.formationDescription = ''
                            this.formationPrice = ''
                            this.formation = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getFormations()
                        })
                } else if (this.crud == 'u') {
                    axios(this.urlApi + 'updateFormation/' + this.formationId, {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormFormation()
                            this.formationId = ''
                            this.formationName = ''
                            this.formationDescription = ''
                            this.formationPrice = ''
                            this.formation = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getFormations()
                        })
                }
            },
            selectFileSound: function(file) {
                this.instrumentSound = file
            },
            saveInstrument: function() {
                this.closeDialog('dialogInstrument')
                this.loadingVisible = true
                let data = new FormData()
                data.append('name', this.instrumentName)
                data.append('image', this.instrumentIcon)
                if (this.crud == 'c') {
                    axios(this.urlApi + 'createInstrument', {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormInstrument()
                            this.instrumentId = ''
                            this.instrumentName = ''
                            this.instrumentIcon = instrumentPath + 'picture.svg'
                            this.instrument = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getInstrument()
                        })
                } else if (this.crud == 'u') {
                    axios(this.urlApi + 'updateInstrument/' + this.instrumentId, {
                            method: 'POST',
                            data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.clearFormInstrument()
                            this.instrumentId = ''
                            this.instrumentName = ''
                            this.instrumentIcon = instrumentPath + 'picture.svg'
                            this.instrument = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getInstrument()
                        })
                }
            },
            saveLead: function(){
                this.closeDialog('dialogLeads')
                this.loadingVisible = true
                let data = new FormData()
                data.append('datetime', this.preInscribeDate + ' ' + this.preInscribeTime)
                data.append('ip', (this.preInscribeIp) ? this.preInscribeIp : '000.000.000.000')
                data.append('origin', this.preInscribe)
                data.append('accountable', this.preInscribeAccountable)
                data.append('phone', this.preInscribePhone)
                data.append('mobile', this.preInscribeMobile)
                data.append('email', this.preInscribeEmail)
                data.append('flag', (this.preInscribeFlag !== null) ? this.preInscribeFlag : 0)

                if(this.crud == 'c'){
                    axios(this.urlApi + 'createLead', {
                        method: 'POST',
                        data: data
                    })
                    .then(response =>{
                        this.loadingVisible = false
                        this.clearFormPreInscribe()
                        this.preInscribeDate = new Date().toISOString().substr(0, 10)
                        this.preInscribeTime = new Date().toLocaleString().substr(11, 8)
                        this.preInscribeIp = ''
                        this.preInscribeAccountable = ''
                        this.preInscribeOrigin = ''
                        this.preInscribePhone = ''
                        this.preInscribeMobile = ''
                        this.preInscribeEmail = ''
                        this.preInscribeFlag = null
                        this.preInscribe = []
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getPreInscribe()
                    })
                    .catch(err => {
                        this.loadingVisible = false
                        Swal.fire(err, '', 'error')
                    })
                } else if(this.crud == 'u'){
                    axios(this.urlApi + 'updateLead/' + this.idPreInscribe, {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        this.loadingVisible = false
                        this.clearFormPreInscribe()
                        this.preInscribeDate = new Date().toISOString().substr(0, 10)
                        this.preInscribeTime = new Date().toLocaleString().substr(11, 8)
                        this.preInscribeIp = ''
                        this.preInscribeAccountable = ''
                        this.preInscribeOrigin = ''
                        this.preInscribePhone = ''
                        this.preInscribeMobile = ''
                        this.preInscribeEmail = ''
                        this.preInscribeFlag = null
                        this.preInscribe = []
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getPreInscribe()
                    })
                    .catch( err => {
                        this.loadingVisible = false
                        Swal.fire(err, '', 'error')
                    })
                }
            },
            saveInscribe: function(){
                this.closeDialog('dialogInscribe')
                this.loadingVisible = true
                let data = new FormData()
                data.append('datetime', this.inscribeDatetime)
                data.append('email', this.inscribeEmail)
                data.append('accountable', this.inscribeAccountable)
                data.append('phone', this.inscribePhone)
                data.append('mobile', this.inscribeMobile)
                data.append('address', JSON.stringify(this.inscribeAddress))
                data.append('cpf', this.inscribeCpf)
                data.append('rg', this.inscribeRg)
                data.append('service_idservice', this.inscribeIdService)
                data.append('formation_idformation', this.inscribeIdFormation)
                
                if(this.crud == 'c'){

                    if(this.preInscribeFlag != null){
                        data.append('flag', this.preInscribeFlag)
                        data.append('idpre_inscribe', this.idPreInscribe)
                    }
                    
                    axios(this.urlApi + 'createInscribe', {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        this.loadingVisible = false
                        this.clearFormInscribe()
                        this.inscribeDateTime = ''
                        this.inscribeAccountable = ''
                        this.inscribePhone = ''
                        this.inscribeMobile = ''
                        this.inscribeEmail = ''
                        this.inscribeAddress = address
                        this.inscribeCpf = ''
                        this.inscribeRg = ''
                        this.inscribeIdAccount = ''
                        this.inscribeIdService = ''
                        this.inscribeIdFormation = ''
                        this.inscribeStatus = ''
                        this.inscribe = []
                        Swal.fire(response.data.msg, '', response.data.icon)
                        console.log(response.data)
                        this.getInscribe()
                        this.getPreInscribe()
                    })
                    .catch((err) => {
                        this.loadingVisible = false
                        Swal.fire(err, '', 'error')
                    })
                } else if(this.crud == 'u'){
                    data.append('account_idaccount', this.inscribeIdAccount)
                    data.append('status', this.inscribeStatus)
                    axios(this.urlApi + 'updateInscribe/' + this.idInscribe, {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        this.loadingVisible = false
                        this.clearFormInscribe()
                        this.idInscribe = ''
                        this.inscribeDateTime = ''
                        this.inscribeAccountable = ''
                        this.inscribePhone = ''
                        this.inscribeMobile = ''
                        this.inscribeEmail = ''
                        this.inscribeAddress = address
                        this.inscribeCpf = ''
                        this.inscribeRg = ''
                        this.inscribeIdAccount = ''
                        this.inscribeIdService = ''
                        this.inscribeIdFormation = ''
                        this.inscribeStatus = ''
                        this.inscribe = []
                        Swal.fire(response.data.msg, '', response.data.icon)
                        console.log(response.data)
                        this.getInscribe()
                    })
                    .catch((err) => {
                        this.loadingVisible = false
                        Swal.fire(err, '', 'error')
                    })
                }
            },
            saveContract: function(){
                    this.closeDialog('dialogContract')
                    this.loadingVisible = true
                    let data = new FormData()
                    data.append('idinscribe', this.idInscribe)
                    data.append('inscribeaccountable', this.inscribeAccountable)
                    data.append('eventname', this.eventName)
                    data.append('eventdate', this.eventDate)
                    data.append('eventtime', this.eventTime)
                    data.append('eventaddress', JSON.stringify(this.eventAddress))
                    data.append('selectengaged', this.selectEngaged.toString())
                    data.append('selectgraduationcommitte', this.selectGraduationCommitte.toString())
                    data.append('codecontract', this.setCode())
                    data.append('date', new Date().toMysqlFormat())
                    data.append('value', this.contractValue)
                    data.append('discount', this.contractDiscount)
                    data.append('addition', this.contractAddition)
                    data.append('variant_tax', JSON.stringify(this.contractService.taxas))
                    data.append('downpayment', this.$ci.parse(this.contractDownPayment))
                    data.append('downpaymentdate', this.contractDownPaymentDate)
                    if(this.selectEngaged){
                        data.append('groom_name', this.engagedGroomName)
                        data.append('groom_address', JSON.stringify(this.engagedGroomAddress))
                        data.append('groom_phone', this.engagedGroomPhone)
                        data.append('groom_mobile', this.engagedGroomMobile)
                        data.append('bride_name', this.engagedBrideName)
                        data.append('bride_address', JSON.stringify(this.engagedBrideAddress))
                        data.append('bride_phone', this.engagedBridePhone)
                        data.append('bride_mobile', this.engagedBrideMobile)
                    }
                    if(this.selectGraduationCommitte){
                        data.append('committe_name', this.graduationCommitteMember)
                    }
                    
                    if(this.crud == 'c'){
                        axios(this.urlApi + 'createContract', {
                               method: 'POST',
                               data: data
                        })
                        .then(response => {
                            this.loadingVisible = false
                            this.hideContractFinish()
                            this.$refs.formEngaged.reset()
                            this.$refs.formGraduationCommitte.reset()
                            this.$refs.formEvent.reset()
                            this.selectEngaged = false
                            this.selectGraduationCommitte = false
                            this.selectEventData = false
                            this.stepContract = 1
                            this.contractService = {taxas:[]}
                            this.contractFormation = {}
                            this.contractTaxValue = []
                            this.contractAddition = 0
                            this.contractDiscount = 0
                            this.contractFormationValue = 0
                            this.contractTaxVariantValue = 0
                            this.contractFixTaxValue = 0
                            this.contractValue = 0
                            this.contractValueTotal = 0
                            this.itemsContract = []
                            this.itemsContractTrash = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            // console.log(response.data)
                            this.getInscribe()
                        })
                    } else if(this.crud == 'u'){
    
                    }
            },
            validateContract: function(id){
                this.closeDialog('dialogAnalyzeContract')
                this.loadingVisible = true
                let data = new FormData()
                data.append('value', this.contractValue)
                data.append('discount', this.contractDiscount)
                data.append('addition', this.contractAddition)
                data.append('variant_tax', JSON.stringify(this.contractService.taxas))
                data.append('downpayment', this.$ci.parse(this.contractDownPayment))
                data.append('downpaymentdate', this.contractDownPaymentDate)
                axios(this.urlApi + 'validateContract/' + id, {
                    method: 'POST',
                    data: data
                })
                .then(response => {
                    this.loadingVisible = false
                    this.contractService = {taxas:[]}
                    this.contractFormation = {}
                    this.contractTaxValue = []
                    this.contractAddition = 0
                    this.contractDiscount = 0
                    this.contractFormationValue = 0
                    this.contractTaxVariantValue = 0
                    this.contractFixTaxValue = 0
                    this.contractValue = 0
                    this.contractValueTotal = 0
                    Swal.fire(response.data.msg, '', response.data.icon)
                    this.getContracts()
                })
            },
            saveMoments: function(){
                if(this.$refs.formMoments.validate()){
                    this.closeDialog('dialogMoments')
                    this.loadingVisible = true
                    if(this.momentsStatus){
                        this.momentsStatus = 1
                    } else{
                        this.momentsStatus = 0
                    }
                    let data = new FormData()
                    data.append('name', this.momentsName)
                    data.append('description', this.momentsDescription)
                    data.append('status', this.momentsStatus)

                    if(this.crud == 'c'){
                        axios(this.urlApi + 'createMoments', {
                            method: 'POST',
                            data: data
                        })
                        .then( response =>{
                            this.loadingVisible = false
                            this.clearFormMoments()
                            this.momentsName = ''
                            this.momentsDescription = ''
                            this.momentsStatus = true
                            this.moments = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getMoments()
                        })
                    } else if(this.crud == 'u'){
                        axios(this.urlApi + 'updateMoments/' + this.momentsId, {
                            method: 'POST',
                            data: data
                        })
                        .then( response => {
                            this.loadingVisible = false
                            this.clearFormMoments()
                            this.momentsId = ''
                            this.momentsName = ''
                            this.momentsDescription = ''
                            this.momentsStatus = true
                            this.moments = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getMoments()
                            
                        })
                    }

                }
            },
            saveMusic: function(){
                if(this.$refs.formMusic.validate()){
                    this.closeDialog('dialogMusic')
                    this.loadingVisible = true
                    if(this.musicStatus){
                        this.musicStatus = 1
                    } else{
                        this.musicStatus = 0
                    }
                    let data = new FormData()
                    data.append('name', this.musicName)
                    data.append('url', this.musicUrl)
                    data.append('status', this.musicStatus)
                    data.append('moments', JSON.stringify(this.musicHasMoments))

                    if(this.crud == 'c'){
                        axios(this.urlApi + 'createMusic', {
                            method: 'POST',
                            data: data
                        })
                        .then( response =>{
                            this.loadingVisible = false
                            this.clearFormMusic()
                            this.musicName = ''
                            this.musicUrl = ''
                            this.musicStatus = true
                            this.musics = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getMusic()
                            
                        })
                    } else if(this.crud == 'u'){
                        axios(this.urlApi + 'updateMusic/' + this.musicId, {
                            method: 'POST',
                            data: data
                        })
                        .then( response => {
                            this.loadingVisible = false
                            this.musicId = ''
                            this.musicName = ''
                            this.musicUrl = ''
                            this.musicStatus = true
                            this.musics = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getMusic()
                            this.clearFormMusic()
                        })
                    }

                }
            },
            saveSignature: function(){
                if(this.$refs.formSignature.validate()){
                    this.closeDialog('dialogSignature')
                    this.loadingVisible = true
                    if(this.signatureStatus){
                        this.signatureStatus = 1
                    } else{
                        this.signatureStatus = 0
                    }
                    let data = new FormData()
                    data.append('name', this.signatureUserName.name)
                    data.append('account_idaccount', this.signatureUserName.idaccount)
                    data.append('type', this.signatureType)
                    data.append('font', this.signatureFont)
                    data.append('status', this.signatureStatus)
                    if(this.crud == 'c'){
                        axios(this.urlApi + 'createSignature', {
                            method: 'POST',
                            data: data
                        })
                        .then( response =>{
                            this.loadingVisible = false
                            this.signatureUserName = {id: '', name: ''}
                            this.signatureType = ''
                            this.signatureFont = ''
                            this.signatureStatus = false
                            this.signatures = []
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getSignature()
                        })
                    } else if(this.crud == 'u'){
                        axios(this.urlApi + 'updateSignature/' + this.signatureId, {
                            method: 'POST',
                            data: data
                        })
                        .then( response => {
                            this.loadingVisible = false
                            Swal.fire(response.data.msg, '', response.data.icon)
                            this.getSignature()
                        })
                    }
                }
            },
            //sends
            sendEmailPreInscribe: function(id, email){
                this.bsPreInscribe = false
                this.loadingVisible = true
                let data = new FormData()
                data.append('datetime', new Date().toISOString().substr(0, 10) + ' ' + new Date().toLocaleString().substr(11, 8))
                data.append('idpre_inscribe', id)
                data.append('email', email)
                data.append('msg', this.sendEmail)
                axios(this.urlApi + 'sendEmailPreInscribe/',{
                    method: 'POST',
                    data: data
                })
                .then(response => {
                    this.loadingVisible = false
                    this.sendEmail - ''
                    Swal.fire(response.data.msg, '', response.data.icon)
                })
            },
            sendWhatsappPreInscribe: function(id, mobile){
                this.bsPreInscribe = false
                this.loadingVisible = true
                let data = new FormData()
                data.append('datetime', new Date().toISOString().substr(0, 10) + ' ' + new Date().toLocaleString().substr(11, 8))
                data.append('idpre_inscribe', id)
                axios(this.urlApi + 'sendWhatsappPreInscribe',{
                    method: 'POST',
                    data: data
                })
                .then( response => {
                    this.loadingVisible = false
                    Swal.fire({
                        title: response.data.msg,
                        icon: response.data.icon,
                        confirmButtonText: 'Abrir WhatsApp'
                    }).then((result) => {
                        if(result.isConfirmed){
                            let msg = encodeURI(this.sendWhatsapp)
                            let number = mobile.replace(/\D/g, '')
                            window.open('https://wa.me/55' + number + '/?text=' + msg)
                        }
                    })
                })
            }
        },
        // COMPUTED
        computed: {
    
        }
    })