import Vue from 'vue';
import './plugins/axios';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import store from './store';
import VueMask from 'v-mask';
import VueCookies from 'vue-cookies';
import VueLocalStorage from 'vue-localstorage';
import VueSession from 'vue-session';
import VuetifyGoogleAutocomplete from 'vuetify-google-autocomplete';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import extenso from 'extenso';
import CKEditor from '@ckeditor/ckeditor5-vue2';
import VCurrencyField from 'v-currency-field';
import { VTextField } from 'vuetify/lib';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';

Object.defineProperty(Vue.prototype, '$extenso', {value: extenso});


Vue.use(VueMask);
// Vue.use(VueCookies)
Vue.use(VueSession);
// Vue.use(VueLocalStorage, {
//   name: 'ls',
//   bind: true
// })


Vue.use(VuetifyGoogleAutocomplete, {
  apiKey: process.env.VUE_APP_API_KEY,

});
Vue.use(VueSweetalert2);
Vue.use(require('vue-moment'));
Vue.use(CKEditor);
Vue.component('v-text-field', VTextField);
Vue.use(VCurrencyField, {
  locale: 'pt-BR',
  decimalLength: 2,
  autoDecimalMode: true,
  min: null,
  max: null,
  defaultValue: 0,
  valueAsInteger: false,
  allowNegative: true,
});
Vue.use(VueLodash, {lodash: lodash});

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
