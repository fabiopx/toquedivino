import Vue from 'vue'
import Vuex from 'vuex'
import frontendApp from './modules/frontendApp'
import backendCustomer from './modules/backendCustomer'

Vue.use(Vuex)

export default new Vuex.Store({
  
  modules: {
    frontendApp,
    backendCustomer
  }
})
