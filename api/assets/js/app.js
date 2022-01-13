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

new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  components: {
      Loading: VueLoading 
  },
  data: () => ({
    drawer: false,
    // urlApi: 'http://localhost/toquedivino/api/',
    urlApi: 'https://app.cerimonialtoquedivino.com.br/api/',
    loadingVisible: false,
    start: false,
    tela: 1,
    dados: {service: '', formation: '', inscribe: {}, address: {}},
    active: false,
    maskPhone: '(##) ####-####',
    maskMobile: '(##) #####-####',
    maskCep: '#####-###',
    maskCpf: '###.###.###-##',
    maskCnpj: '##.###.###/####-##',
    setIP: {},
    selectedService: '',
    serviceRules:[v => !!v || "Por favor diga qual o tipo do evento"],
    services: [],
    instruments: [],
    selectedInstruments: [],
    alertSelectedInstruments: false,
    formations: [],
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
    query: ""
  }),
  watch: {
      
  },
  mounted() {
    if(localStorage.start){
        this.start = localStorage.start
    }
    
    if(localStorage.tela){
        this.tela = localStorage.tela
    } else{
        localStorage.tela = this.tela
    }

    if(localStorage.getItem('services')){
        try{
            this.services = JSON.parse(localStorage.getItem('services'))
        } catch(e){
            localStorage.removeItem('services')
        }
    }

    if(localStorage.getItem('instruments')){
        try{
            this.instruments = JSON.parse(localStorage.getItem('instruments'))
        } catch(e){
            localStorage.removeItem('instruments')
        }
    }

    if(localStorage.getItem('formations')){
        try{
            this.formations = JSON.parse(localStorage.getItem('formations'))
        } catch(e){
            localStorage.removeItem('formations')
        }
    }

    if(localStorage.getItem('dados')){
        try{
            this.dados = JSON.parse(localStorage.getItem('dados'))
        } catch(e){
            localStorage.removeItem('dados')
        }
    }

    if(localStorage.getItem('setIP')){
        try{
            this.setIP = JSON.parse(localStorage.getItem('setIP'))
        } catch(e){
            localStorage.removeItem('setIP')
        }
    }

    // localStorage.removeItem('start')
    // localStorage.removeItem('services')
    // localStorage.removeItem('instruments')
    // localStorage.removeItem('tela')
    // localStorage.removeItem('dados')
    // localStorage.removeItem('setIP')
  },
  created(){

  },
  methods: {
    startApp: function(){
        this.start = true
        localStorage.start = this.start
        this.getServices()
        this.getInstruments()
        this.getIP()
    },
    restartApp: function(){
        this.start = false
        this.tela = 1
        this.services = []
        this.instruments = []
        this.formations = []
        localStorage.removeItem('start')
        localStorage.removeItem('services')
        localStorage.removeItem('instruments')
        localStorage.removeItem('formations')
        localStorage.removeItem('tela')
        localStorage.removeItem('dados')
        localStorage.removeItem('setIP')
    },
    endApp: function(){
        this.restartApp()
    },
    endScreen: function(){
        if(this.$refs.formEvent.validate()){
            Promise.all([this.saveInscribe(), this.deleteLead()])
            .then(values => {
                console.log(values)
                this.tela = 6
                localStorage.removeItem('tela')
                localStorage.setItem('tela', this.tela)
            })
        }
    },
    nextScreen: function(){
        if(this.tela == 1){
            if(this.$refs.firstScreen.validate()){
                this.tela = 2
                localStorage.removeItem('tela')
                localStorage.setItem('tela', this.tela)
                this.dados.service = this.selectedService 
                localStorage.setItem('dados', JSON.stringify(this.dados))
            }
        } else if(this.tela == 2){
            if(this.selectedInstruments.length != 0){
                this.alertSelectedInstruments = false
                this.tela = 3
                localStorage.removeItem('tela')
                localStorage.setItem('tela', this.tela)
                this.getFormationByInstruments()
            } else{
                this.alertSelectedInstruments = true
            }
        } else if(this.tela == 3){
            if(this.selectedFormation){
                this.tela = 4
                localStorage.removeItem('tela')
                localStorage.setItem('tela', this.tela)
                this.dados.formation = this.selectedFormation
                localStorage.setItem('dados', JSON.stringify(this.dados))
            } else {
                this.alertSelectedFormation = true
            }
        } else if(this.tela == 4){
            if(this.$refs.formInscribePartOne.validate()){
                this.tela = 5
                localStorage.removeItem('tela')
                localStorage.setItem('tela', this.tela)
                this.dados.inscribe = {accountable: this.inscribeAccountable, email: this.inscribeEmail, phone: this.inscribePhone, mobile: this.inscribeMobile}
                localStorage.setItem('dados', JSON.stringify(this.dados))
            }
        } else if (this.tela == 5){
            if(this.$refs.formInscribePartTwo.validate()){
                this.tela = 6
                localStorage.removeItem('tela')
                localStorage.setItem('tela', this.tela)
                this.dados.address = this.inscribeAddress
                localStorage.setItem('dados', JSON.stringify(this.dados))
                this.saveLead()
            }
        }
    },
    prevScreen: function(){
        if(this.tela >= 1){
            this.tela = this.tela - 1
            localStorage.removeItem('tela')
            localStorage.setItem('tela', this.tela)
        }
        if(this.tela == 4){
            this.inscribeAccountable = this.dados.inscribe.accountable
            this.inscribeEmail = this.dados.inscribe.email
            this.inscribePhone = this.dados.inscribe.phone
            this.inscribeMobile = this.dados.inscribe.mobile
        }
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
    showTeste: function(){
        console.log(this.selectedFormation)
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
    //gets
    getServices: function() {
        this.loadingVisible = true
        axios.get(this.urlApi + 'getServices')
        .then(response => {
            this.services = response.data
            localStorage.setItem('services', JSON.stringify(this.services))
            this.loadingVisible =  false
        })
    },
    getInstruments: function() {
        this.loadingVisible = true
        axios.get(this.urlApi + 'getInstrument')
        .then(response => {
            this.instruments = response.data
            localStorage.setItem('instruments', JSON.stringify(this.instruments))
            this.loadingVisible = false
        })
    },
    getFormationByInstruments: function(){
        this.loadingVisible = true
        let data = new FormData()
        data.append('instruments', JSON.stringify(this.selectedInstruments))
        axios(this.urlApi + 'getFormationByInstruments', {
            method: 'POST',
            data: data
        })
        .then(response => {
            this.formations = response.data
            localStorage.setItem('formations', JSON.stringify(this.formations))
            this.loadingVisible = false
            console.log(this.formations)
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
    getIP: function(){
        axios.get('https://api.ipify.org?format=json')
        .then(response => {
            this.setIP = response.data
            localStorage.setItem('setIP', JSON.stringify(this.setIP))
        })
    },
    //saves
    saveLead: function(){
        let data = new FormData()
        data.append('datetime', new Date().toMysqlFormat())
        data.append('ip', this.setIP.ip)
        data.append('origin', 'app toque divino')
        data.append('accountable', this.dados.inscribe.accountable)
        data.append('phone', this.dados.inscribe.phone)
        data.append('mobile', this.dados.inscribe.mobile)
        data.append('email', this.dados.inscribe.email)
        data.append('flag', 0)
        axios(this.urlApi + 'createLead', {
            method: 'POST',
            data: data
        }).then(response => {
            console.log(response.data)
        })
    },
    saveInscribe: function(){
        this.loadingVisible = true
        let data = new FormData()
        data.append('datetime', new Date().toMysqlFormat())
        data.append('email', this.dados.inscribe.email)
        data.append('accountable', this.dados.inscribe.accountable)
        data.append('phone', this.dados.inscribe.phone)
        data.append('mobile', this.dados.inscribe.mobile)
        data.append('address', JSON.stringify(this.dados.address))
        data.append('cpf', this.inscribeCpf)
        data.append('rg', this.inscribeRg)
        data.append('service_idservice', this.dados.service.idservice)
        data.append('formation_idformation', this.dados.formation.idformation)
        data.append('eventname', this.eventName)
        data.append('eventdate', this.eventDate)
        data.append('eventtime', this.eventTime)
        data.append('eventaddress', JSON.stringify(this.eventAddress))
        axios(this.urlApi + 'createInscribeApp', {
            method: 'POST',
            data: data
        }).then(response => {
            console.log(response.data)
            this.loadingVisible = false
        })
    },
    saveEvent: function(){
        let data = new FormData()
        data.append('eventname', this.eventName)
        data.append('eventdate', this.eventDate)
        data.append('eventtime', this.eventTime)
        data.append('eventaddress', JSON.stringify(this.eventAddress))
        data.append('idinscribe', this.inscribeID)

        axios(this.urlApi + 'createEventCustomers', {
            method: 'POST',
            data: data
        })
        .then(response => {
            console.log(response.data)
        })
    },
    //deletes
    deleteLead: function(){
        let data = new FormData()
        data.append('ip', this.setIP.query)
        data.append('email', this.dados.inscribe.email)
        data.append('phone', this.dados.inscribe.phone)
        axios(this.urlApi + 'deleteLead', {
            method: 'POST',
            data: data
        }).then(response =>{
            console.log(response.data)
        })
    },
    queryHere: function(value){
        axios.get(`https://autocomplete.search.hereapi.com/v1/autocomplete/?q=${value}/in=countryCode%3ABRA&lang=pt-BR&limit=1`, {
            headers: {
                'Authorization' : 'Bearer uQYJ0goB8hGY0g_YF7e8EQ4hIAbMYAw0HWdU7RFYPxQ'
            }
        })
        .then(response => {
            console.log(response.data)
        })
    }
  },
  computed: {

  }
})