import '../styles/app.css';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import Vue from "vue";
import router from "./router";
import store from "./store";
import App from "./App";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

// PAGES
Vue.component('app', App);
Vue.component('product-creation-page', require('./components/pages/product-creation-page.js').default);

new Vue({
    router,
    store
}).$mount("#app");
