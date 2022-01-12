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
    Vue.component('VueCountdown', VueCountdown)

    function twoDigits(d) {
        if (0 <= d && d < 10) return "0" + d.toString();
        if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
        return d.toString();
    }

    function toDateFormat(input){
        var datePart = input.match(/\d+/g),
        year = datePart[0], // get only two digits
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
    var totalValue = undefined

    new Vue({
        el: '#customers',
        vuetify: new Vuetify(),
        components: {
            Loading: VueLoading,
        },
        data: () => ({
            urlApi: 'http://localhost/toquedivino/api/',
            // urlApi: 'https://app.cerimonialtoquedivino.com.br/api/',
            userNow: {},
            drawer: false,
            colorToolbar: 'primary',
            navegador:[
                {
                    icon: 'mdi-home',
                    text: 'Home',
                    link: 'home'
                },
                {
                    icon: 'mdi-account',
                    text: 'Dados de acesso',
                    link: 'account'
                },
                {
                    icon: 'mdi-folder-information',
                    text: 'Cadastro',
                    link: 'inscribe'
                },
                {
                    icon: 'mdi-music-circle',
                    text: 'Repertório',
                    link: 'repertory'
                },
                {
                    icon: 'mdi-file-document-edit',
                    text: 'Contrato',
                    link: 'agreement'
                }
                
            ],
            showPage: 'home',
            showTabInscribe: 'inscribe',
            tabInscribeTitle: 'Dados do cadastro',
            showPassword: false,
            maskPhone: '(##) ####-####',
            maskMobile: '(##) #####-####',
            maskCpf: '###.###.###-##',
            maskCnpj: '##.###.###/####-##',
            maskMoney: '######.##',
            maskBirthdate: '##/##/####',
            maskCep: '#####-###',
            alert: false,
            alertMsg: '',
            colorToolbar: 'blue darken-4',
            alertLogin: {status: false, msg: ''},
            inputLoading: false,
            loginEmail: '',
            loginEmailRules:[v => !!v || 'Digite o e-mail para realizar o login'],
            loginPassword: '',
            loginPasswordRules: [v => !!v || 'Digite a senha para realizar o login'],
            loadingAccountFields: false,
            crud: 'c',
            accountName: '',
            accountEmail: '',
            accountPassword: '',
            accountPhone: '',
            accountPhoto: 'assets/img/profile.svg',
            currentFile: undefined,
            progressUpload: 0,
            progressShow: false,
            uploadSuccess: false,
            uploadError: false,
            uploadMsg: '',
            photo: '',
            services: [],
            formation: [],
            loadingInscribeFields: false,
            inscribeAccountable: '',
            inscribePhone: '',
            inscribeMobile: '',
            inscribeAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
            inscribeCpf: '',
            inscribeRg: '',
            inscribeStatus: '',
            inscribeService: '',
            inscribeFormation: '',
            inscribeServiceTax: '',
            inscribeAgree: false,
            loadingEventFields: false,
            eventEmpty: true,
            eventID: '',
            eventName: '',
            eventDate: '',
            eventDateCountDown: '',
            eventTime: '',
            countDownNow: new Date(),
            countDownTime: undefined,
            eventAddress: {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''},
            pickDateEvent: false,
            pickTimeEvent: false,
            loadingEngagedFields: false,
            selectEngaged: false,
            engagedID: '',
            engagedBrideAccountable: false,
            engagedGroomAccountable: false,
            engagedBrideName: '',
            engagedBrideNameRules: [v => !!v || 'O campo NOME DA NOIVA é requerido'],
            engagedBridePhoto: 'assets/img/woman.png',
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
            engagedGroomPhoto: 'assets/img/man.png',
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
            engagedBrideUploadPhoto: false,
            engagedGroomUploadPhoto: false,
            loadingCommitteFields: false,
            committeID: '',
            graduationCommitteName: '',
            graduationCommitteNameRules: [v => !!v || 'Por favor digite um nome'],
            graduationCommitteMember: [],
            loadingSelectFields: false,
            loadingListRepertory: false,
            startedRepertory: false,
            repertory: [],
            repertoryID: '',
            repertoryMoments: '',
            repertoryMomentsRules: [v => !!v || 'Selecione um Momento'],
            repertoryMusic: '',
            repertoryMusicRules: [v => !!v || 'Selecione um Música'],
            repertorySequence: '',
            moments: [],
            music: [],
            loadingAgreementService: false,
            loadingAgreementFormation: false,
            loadingAgreementEvent: false,
            loadingAgreementValues: false,
            contractCode: '',
            contractDate: '',
            contractValue: '',
            contractDiscount: '',
            contractAddition: '',
            contractDownPayment: '',
            contractDownPaymentExtenso: '',
            contractDownPaymentDate: '',
            contractValueTotal: '',
            contractValueExtenso: '',
            dialogContractSign: false,
            hasSignature: false,
            dialogSignWithPassword: false,
            formSignWithPassword: true,
            loadingSignWithPassword: false,
            alertSignWithPassword: false,
            signaturePassword: '',
            setIP: {}
        }),
        mounted(){
            if(localStorage.getItem('userNow')){
                try{
                    this.userNow = JSON.parse(localStorage.getItem('userNow'))
                    this.accountName = this.userNow.name
                    this.accountPhoto = this.userNow.photo
                    this.getInscribe()
                } catch(e){
                    localStorage.removeItem('userNow')
                    this.userNow = {logged: false, login: true, name: 'Usuário', id: '', photo: 'assets/img/profile.svg'}
                }
            } else{
                this.userNow = {logged: false, login: true, name: 'Usuário', id: '', photo: 'assets/img/profile.svg'}
            }
            this.showPage = 'home'
        },
        created(){

        },
        methods: {
            isActive: function(val) {
                return this.showPage === val
            },
            showActive: function(val){
                this.showPage = val
                switch(val){
                    case 'home':
                        this.colorToolbar = 'primary'
                        this.resetAllVars()
                        break
                    case 'account':
                        this.colorToolbar = 'blue'
                        this.resetAllVars()
                        this.getAccount()
                        break
                    case 'inscribe':
                        this.colorToolbar = 'teal'
                        this.showTabInscribe = 'inscribe'
                        this.resetAllVars()
                        this.getInscribe()
                        this.getServices()
                        this.getFormations()
                        break
                    case 'agreement':
                        this.colorToolbar = 'red darken-3'
                        this.resetAllVars()
                        this.getInscribe()
                        this.getServices()
                        this.getFormations()
                        this.getEvent()
                        this.getTax()
                        this.getAgreement()
                        this.getEngaged()
                        this.verifySignature()
                        this.getIP()
                        break
                    case 'repertory':
                        this.colorToolbar = 'amber darken-3'
                        this.resetAllVars()
                        this.getRepertory()
                        this.getRepertoryID()
                        this.getMoments()
                        break
                }
            },
            activeTabInscribe: function(tab){
                this.showTabInscribe = tab
                this.resetAllVars()
                this.getInscribe()
                switch(tab){
                    case 'events':
                        this.tabInscribeTitle = 'Dados do evento'
                        this.$refs.formEvents.reset()
                        this.getEvent()    
                        break
                    case 'inscribe':
                        this.tabInscribeTitle = 'Dados do cadastro'
                        this.getServices()
                        this.getFormations()
                        break
                    case 'engaged':
                        this.tabInscribeTitle = 'Dados dos noivos'
                        this.$refs.formEngaged.reset()
                        this.getEngaged()
                        break
                    case 'committe':
                        this.tabInscribeTitle = 'Comissão de formatura'
                        this.getCommitte()
                        break
                
                }
            },
            resetAllVars: function(){
                this.currentFile = undefined
                this.inscribeAccountable = ''
                this.inscribePhone = ''
                this.inscribeMobile = ''
                this.inscribeAddress = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
                this.inscribeCpf = ''
                this.inscribeRg = ''
                this.inscribeService = ''
                this.inscribeFormation = ''
                this.inscribeServiceTax = ''
                this.services = []
                this.formation = []
                this.eventID = ''
                this.eventName = ''
                this.eventDate = ''
                this.eventTime = ''
                this.eventAddress = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
                this.engagedID = ''
                this.engagedBrideName = ''
                this.engagedBrideAddress = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
                this.engagedBridePhone = ''
                this.engagedBrideMobile = ''
                this.engagedBrideCpf = ''
                this.engagedBrideRg = ''
                this.engagedBrideBirthdate = ''
                this.engagedBrideEmail = ''
                this.engagedGroomName = ''
                this.engagedGroomAddress = {street: '', number: '', complement: '', neighborhood: '', city: '', zipcode: '', state: '', country: ''}
                this.engagedGroomPhone = ''
                this.engagedGroomMobile = ''
                this.engagedGroomCpf = ''
                this.engagedGroomRg = '',
                this.engagedGroomBirthdate = ''
                this.engagedGroomEmail = ''
                this.graduationCommitteName = ''
                this.graduationCommitteMember = []
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
            setCode: function(){
                let text = ''
                let numbers = '0123456789'
                
                for(var i = 0; i < 8; i++){
                    text += numbers.charAt(Math.floor(Math.random() * numbers.length))
                }

                let year = new Date().getFullYear()

                return year + text
            },
            enterLogin: function(){
                if(this.$refs.formLogin.validate()){
                    if(localStorage.getItem('userNow')){
                        localStorage.removeItem('userNow')
                    }
                    let data = new FormData()
                    data.append('email', this.loginEmail)
                    data.append('password', this.loginPassword)
                    this.loadingVisible = true
                    axios(this.urlApi + 'loginCustomer', {
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
                        this.getAccount()
                        setInterval(() => {
                            this.alertLogin = false
                        }, 5000)
                    })
                }
            },
            logout: function(){
                localStorage.removeItem('userNow')
                this.resetAllVars()
                this.userNow.logged = false
                this.userNow.login = true
                this.showPage = 'home'
                this.colorToolbar = 'blue darken-4'
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
                            this.progressShow = false
                        })
                        .catch(error => {
                            this.uploadError = true
                            this.uploadMsg = error
                        })
                } else{
                    this.accountPhoto = imgPath + 'profile.svg'
                    this.progressShow = false
                    this.uploadMsg = ''
                    this.uploadSuccess = false
                    this.uploadError = false
                }
            },
            openUploadPhoto: function(profile){
                this[profile] = true
                this.currentFile = undefined
                this.uploadMsg = ''
                this.uploadSuccess = false
                this.uploadError = false
            },
            readEngagedPhoto: function(file, profile){
                if(file){
                    this[profile] = URL.createObjectURL(file)
                    this.progressShow = true
                    this.photo = (profile == 'engagedBridePhoto') ? 'woman.png' : 'man.png'
                    

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
                            this.progressShow = false
                        })
                        .catch(error => {
                            this.uploadError = true
                            this.uploadMsg = error
                            this.progressShow = false
                        })
                } else{
                    this[profile] = imgPath + this.photo
                    this.progressShow = false
                    this.uploadMsg = ''
                    this.uploadSuccess = false
                    this.uploadError = false
                }
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
            addMemberGraduationCommitte: function(){
                if(this.$refs.formGraduationCommitte.validate()){
                    this.graduationCommitteMember.push(this.graduationCommitteName)
                    this.$refs.formGraduationCommitte.reset()
                    this.alert = false
                    this.alertMsg = ''
                }
            },
            removeMemberGraduationCommitte: function(i, n){
                this.graduationCommitteMember.splice(i, n)
            },
            openDialogContractSign: function(){
                this.dialogContractSign = true
            },
            closeDialogContractSign: function(){
                this.dialogContractSign = false
                this.contractValueExtenso = ''
                this.contractDownPaymentExtenso = ''
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
            getIP: function(){
                axios.get('https://api.ipgeolocation.io/ipgeo?apiKey=641aff88cd584ec7815acacaff6dada7&fields=geo')
                .then(response => {
                    this.setIP = response.data
                })
            },
            calculateContractValueTotal: function(){
                totalValue = parseFloat(this.contractValue) - parseFloat(this.contractDiscount) + parseFloat(this.contractAddition)
                this.contractValueTotal = totalValue
                this.contractValueExtenso = totalValue.toString().extenso()
            },
            getAccount: function(){
                this.loadingAccountFields = true
                axios.get(this.urlApi + 'getUsers/' + this.userNow.id)
                .then(response => {
                    this.accountName = response.data.name
                    this.accountEmail = response.data.email
                    this.accountPassword = response.data.password
                    this.accountPhoto = (response.data.photo) ? response.data.photo : 'assets/img/profile.svg'
                    this.loadingAccountFields = false
                })
            },
            getServices: function() {
                this.loadingAgreementService = true
                axios.get(this.urlApi + 'getServices')
                    .then(response => {
                        this.services = response.data
                        this.loadingAgreementService = false
                    })
            },
            getFormations: function(){
                this.loadingAgreementFormation = true
                axios.get(this.urlApi + 'getFormation')
                .then(response =>{
                    this.formation = response.data
                    this.loadingAgreementFormation = false
                })
            },
            getInscribe: function(){
                this.loadingInscribeFields = true
                axios.get(this.urlApi + 'getInscribeCustomers/' + this.userNow.id)
                .then(response => {
                    const resp = response.data
                    if(resp){
                        this.inscribeID = resp.idinscribe
                        this.inscribeAccountable = resp.accountable
                        this.inscribePhone = resp.phone
                        this.inscribeMobile = resp.mobile
                        this.inscribeAddress = resp.address
                        this.inscribeCpf = resp.cpf
                        this.inscribeRg = resp.rg
                        this.inscribeStatus = resp.status
                        this.inscribeService = resp.service
                        this.inscribeFormation = resp.formation
                        this.eventName = resp.service.name
                    }
                    this.loadingInscribeFields = false
                    this.loadingAgreementValues = false
                })
            },
            getAgreement: function(){
                this.loadingAgreementValues = true
                axios.get(this.urlApi + 'getAgreement/' + this.inscribeID)
                .then(response => {
                    let resp = response.data
                    if(resp){
                        this.contractCode = resp.code
                        this.contractDate = toDateFormat(resp.date)
                        this.contractValue = resp.value
                        this.contractDiscount = resp.discount
                        this.contractAddition = resp.addition
                        this.contractDownPayment = resp.down_payment
                        this.contractDownPaymentExtenso = resp.down_payment.extenso()
                        this.contractDownPaymentDate = toDateFormat(resp.down_payment_date)
                        this.calculateContractValueTotal()
                    }
                    this.loadingAgreementValues = false
                })
            },
            getEvent: function(){
                this.loadingEventFields = true
                this.loadingAgreementEvent = true
                axios.get(this.urlApi + 'getEventCustomers/' + this.inscribeID)
                .then(response => {
                    const resp = response.data
                    if(resp){
                        this.eventID = resp.idevent
                        this.eventName = resp.name
                        this.eventDate = toDateFormat(resp.date)
                        this.eventDateCountDown = new Date(resp.date)
                        this.eventTime = resp.time
                        this.eventAddress = resp.address
                        this.crud = 'u'
                        this.eventEmpty = false
                        this.countDownTime = this.eventDateCountDown - this.countDownNow
                    } else{
                        this.crud = 'c'
                    }
                    this.loadingEventFields = false
                    this.loadingAgreementEvent = false
                })
            },
            getEngaged: function(){
                this.loadingEngagedFields = true
                axios.get(this.urlApi + 'getEngagedCustomers/' + this.inscribeID)
                .then(response =>{
                    const resp = response.data
                    if(resp){
                        this.crud = 'u'
                        this.engagedID = resp.idengaged
                        this.engagedGroomName = resp.groom_name
                        this.engagedGroomAddress = resp.groom_address
                        this.engagedGroomPhone = resp.groom_phone
                        this.engagedGroomMobile = resp.groom_mobile
                        this.engagedGroomPhoto = (resp.groom_photo) ? resp.groom_photo : 'assets/img/man.png'
                        this.engagedGroomCpf = resp.groom_cpf
                        this.engagedGroomRg = resp.groom_rg
                        this.engagedGroomBirthdate = toDateFormat(resp.groom_birthdate)
                        this.engagedGroomEmail = resp.groom_email
                        this.engagedBrideName = resp.bride_name
                        this.engagedBrideAddress = resp.bride_address
                        this.engagedBridePhone = resp.bride_phone
                        this.engagedBrideMobile = resp.bride_mobile
                        this.engagedBridePhoto = (resp.bride_photo) ? resp.bride_photo : 'assets/img/woman.png'
                        this.engagedBrideCpf = resp.bride_cpf
                        this.engagedBrideRg = resp.bride_rg
                        this.engagedBrideBirthdate = toDateFormat(resp.bride_birthdate)
                        this.engagedBrideEmail = resp.bride_email
                        this.selectEngaged = resp.selectEngaged
                    } else{
                        this.crud = 'c'
                        this.selectEngaged = false
                        console.log(selectEngaged)
                    }
                    this.loadingEngagedFields = false
                })
            },
            getCommitte: function(){
                this.loadingCommitteFields = true
                axios.get(this.urlApi + 'getCommitteCustomers/' + this.inscribeID)
                .then(response => {
                    const resp = response.data
                    if(resp){
                        this.crud = 'u'
                        this.committeID = resp.idgraduation_committe
                        this.graduationCommitteName = ''
                        this.graduationCommitteMember = resp.committe_name
                    } else{
                        this.crud = 'c'
                    }
                    this.loadingCommitteFields = false
                })
            },
            getRepertory: function(){
                this.loadingListRepertory = true
                axios.get(this.urlApi + 'getRepertoryCustomers/' + this.inscribeID)
                .then(response => {
                    const resp = response.data
                    if(resp){
                        this.startedRepertory = true
                        this.repertory = response.data
                    } else{
                        this.startedRepertory = false
                    }
                    this.loadingListRepertory = false
                })
            },
            getRepertoryID: function(){
                axios.get(this.urlApi + 'getRepertoryCustomersID/' + this.inscribeID)
                .then( response => {
                    this.repertoryID = response.data
                })
            },
            getMoments: function(){
                this.loadingSelectFields = true
                axios.get(this.urlApi + 'getMomentsCustomers')
                .then(response => {
                    this.moments = response.data
                    this.loadingSelectFields = false
                })
            },
            getMusic: function(id){
                this.loadingSelectFields = true
                axios.get(this.urlApi + 'getMusicCustomers/' + id)
                .then(response => {
                    this.music = response.data
                    this.loadingSelectFields = false
                })
            },
            getTax: function(){
                axios.get(this.urlApi + 'getTaxByService/' + this.inscribeID)
                .then(response => {
                    this.inscribeServiceTax = response.data
                })
            },
            verifySignature: function(){
                axios.get(this.urlApi + 'verifySignCustomers/' + this.userNow.id)
                .then(response => {
                    this.hasSignature = response.data
                })
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
                    this.verifySignature()
                })
            },
            saveAccount: function(){
                let data = new FormData()
                data.append('name', this.accountName)
                data.append('email', this.accountEmail)
                data.append('password', this.accountPassword)
                data.append('photo', (this.currentFile) ? 'assets/uploads/' + this.currentFile.name : this.accountPhoto)
                axios(this.urlApi + 'updateUserCustomers/' + this.userNow.id, {
                    method: 'POST',
                    data: data
                })
                .then(response => {
                    Swal.fire(response.data.msg, '', response.data.icon)
                    this.getAccount()
                })
            },
            saveInscribe: function(){
                let data = new FormData()
                data.append('accountable', this.inscribeAccountable)
                data.append('phone', this.inscribePhone)
                data.append('mobile', this.inscribeMobile)
                data.append('address', JSON.stringify(this.inscribeAddress))
                data.append('cpf', this.inscribeCpf)
                data.append('rg', this.inscribeRg)
                data.append('idservice', this.inscribeService.idservice)
                data.append('idformation', this.inscribeFormation.idformation)
                axios(this.urlApi + 'updateInscribeCustomers/' + this.inscribeID, {
                    method: 'POST',
                    data: data
                })
                .then(response =>{
                    Swal.fire(response.data.msg, '', response.data.icon)
                    this.getInscribe()
                })
            },
            saveEvent: function(){
                let data = new FormData()
                data.append('eventname', this.eventName)
                data.append('eventdate', this.eventDate)
                data.append('eventtime', this.eventTime)
                data.append('eventaddress', JSON.stringify(this.eventAddress))
                data.append('idinscribe', this.inscribeID)
                if(this.crud == 'c'){
                    axios(this.urlApi + 'createEventCustomers', {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getEvent()
                    })
                } else if(this.crud == 'u'){
                    axios(this.urlApi + 'updateEventCustomers/' + this.eventID, {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getEvent()
                    })
                }
            },
            saveEngaged: function(){
                let data = new FormData()
                data.append('groom_name', this.engagedGroomName)
                data.append('groom_address', JSON.stringify(this.engagedGroomAddress))
                data.append('groom_phone', this.engagedGroomPhone)
                data.append('groom_mobile', this.engagedGroomMobile)
                data.append('groom_photo', this.engagedGroomPhoto)
                data.append('groom_cpf', this.engagedGroomCpf)
                data.append('groom_rg', this.engagedGroomRg)
                data.append('groom_birthdate', this.engagedGroomBirthdate)
                data.append('groom_email', this.engagedGroomEmail)
                data.append('bride_name', this.engagedBrideName)
                data.append('bride_address', JSON.stringify(this.engagedBrideAddress))
                data.append('bride_phone', this.engagedBridePhone)
                data.append('bride_mobile', this.engagedBrideMobile)
                data.append('bride_photo', this.engagedBridePhoto)
                data.append('bride_cpf', this.engagedBrideCpf)
                data.append('bride_rg', this.engagedBrideRg)
                data.append('bride_birthdate', this.engagedBrideBirthdate)
                data.append('bride_email', this.engagedBrideEmail)
                data.append('idinscribe', this.inscribeID)
                if(this.crud == 'c'){
                    axios(this.urlApi + 'createEngagedCustomers', {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getEngaged()
                    })
                } else if(this.crud == 'u'){
                    axios(this.urlApi + 'updateEngagedCustomers/' + this.engagedID, {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getEngaged()
                    })
                }
            },
            saveCommitte: function(){
                let data = new FormData()
                data.append('committe_name', JSON.stringify(this.graduationCommitteMember))
                data.append('inscribe_idinscribe', this.inscribeID)
                if(this.crud == 'c'){
                    axios(this.urlApi + 'createCommitteCustomers', {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getCommitte()
                    })
                } else if(this.crud == 'u'){
                    axios(this.urlApi + 'updateCommitteCustomers/' + this.committeID, {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getCommitte()
                    })
                }
            },
            startRepertory: function(){
                let data = new FormData()
                data.append('idinscribe', this.inscribeID)
                axios(this.urlApi + 'startRepertory', {
                    method: 'POST',
                    data: data
                })
                .then(response => {
                    Swal.fire(response.data.msg, '', response.data.icon)
                    this.getRepertory()
                })
            },
            addRepertoryItem: function(){
                if(this.$refs.formAddRepertoryItem.validate()){
                    let data = new FormData()
                    data.append('idrepertory', this.repertoryID)
                    data.append('idmoments', this.repertoryMoments)
                    data.append('idmusic', this.repertoryMusic.idmusic)
                    data.append('sequence', this.repertorySequence)
                    axios(this.urlApi + 'addRepertoryItem', {
                        method: 'POST',
                        data: data
                    })
                    .then(response => {
                        Swal.fire(response.data.msg, '', response.data.icon)
                        this.getRepertory()
                    })
                }
            },
            delRepertoryItem: function(music, moments){
                axios.get(this.urlApi + 'deleteRepertoryItem/' + music + '/' + moments)
                .then(response => {
                    Swal.fire(response.data.msg, '', response.data.icon)
                    this.getRepertory()
                })
            },
            generateContract: function(){
                let data  = new FormData()
                data.append('codecontract', this.setCode())
                data.append('date', new Date().toMysqlFormat())
                data.append('value', '')
                data.append('discount', '')
                data.append('addition', '')
                data.append('downpayment', '')
                data.append('downpaymentdate', '')
                data.append('idinscribe', this.inscribeID)
                axios(this.urlApi + 'generateContractCustomers/', {
                    method: 'POST',
                    data: data
                })
                .then(response => {
                    Swal.fire(response.data.msg, '', response.data.icon)
                    this.getInscribe()
                    this.getServices()
                    this.getFormations()
                    this.getEvent()
                    this.getTax()
                })
            }
        }
    })