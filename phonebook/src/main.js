import Vue from 'vue'
import App from './App.vue'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import router from './router'
import axios from 'axios'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Multiselect from 'vue-multiselect'
import { Modal } from 'bootstrap-vue/es/components'
import bModal from 'bootstrap-vue/es/components/modal/modal'
import bBtn from 'bootstrap-vue/es/components/button/button'
import bModalDirective from 'bootstrap-vue/es/directives/modal/modal'

Vue.use(BootstrapVue);
Vue.use(Modal);

axios.defaults.baseURL = 'http://vue-php.loc';

Vue.config.productionTip = false

Vue.component('multiselect', Multiselect)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('b-modal', bModal);
Vue.component('b-btn', bBtn);
Vue.directive('b-modal', bModalDirective);

new Vue({
	router,
  render: h => h(App)
}).$mount('#app')
