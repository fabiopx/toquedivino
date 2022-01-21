import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import store from './store'
import VueMask from 'v-mask'
import VueSession from 'vue-session'
import VueCookies from 'vue-cookies'
import VueLocalStorage from 'vue-localstorage'

Vue.use(VueMask)
Vue.use(VueSession)
Vue.use(VueCookies)
Vue.use(VueLocalStorage, {
  name: 'ls',
  bind: true
})

Vue.config.productionTip = false

new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
