import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Customer from '../views/Customer.vue'
import CustomerHome from '../components/CustomerHome.vue'
import CustomerAccount from '../components/CustomerAccount.vue'
import CustomerInscribe from '../components/CustomerInscribe.vue'
import CustomerRepertoy from '../components/CustomerRepertory.vue'
import CustomerAgreement from '../components/CustomerAgreement.vue'
import CustomerLogin from '../components/CustomerLogin.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/customer',
    name: 'Customer',
    component: Customer,
    children: [
      {
        path: 'home',
        component: CustomerHome
      },
      {
        path: 'account',
        component: CustomerAccount
      },
      {
        path: 'inscribe',
        component: CustomerInscribe
      },
      {
        path: 'repertory',
        component: CustomerRepertoy
      },
      {
        path: 'agreement',
        component: CustomerAgreement
      },
      {
        path: 'login',
        component: CustomerLogin
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
