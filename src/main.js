import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import store from './store'
import VueMask from 'v-mask'
import VueCookies from 'vue-cookies'
import VueLocalStorage from 'vue-localstorage'
import VueSession from 'vue-session'
import VuetifyGoogleAutocomplete from 'vuetify-google-autocomplete'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

Vue.use(VueMask)
// Vue.use(VueCookies)
Vue.use(VueSession)
// Vue.use(VueLocalStorage, {
//   name: 'ls',
//   bind: true
// })
Vue.use(VuetifyGoogleAutocomplete, {
  apiKey: 'AIzaSyCDfEljw9VcN0G4EiejU2QZDBwrINuNdTQ'
})
Vue.use(VueSweetalert2)
Vue.use(require('vue-moment'))

Vue.config.productionTip = false

new Vue({
  router,
  vuetify,
  store,
  localStorage: {
    dados: {
      type: Object,
      default: {
        services: {},
        formation: {},
        inscribe: {}
      }
    },
    start: {
      type: Boolean,
      default: true
    },
    tela: {
      type: Number,
      default: 1
    }
  },
  render: h => h(App)
}).$mount('#app')
