import '../styles/app.css';
import Vue from "vue";
import router from "./router";
import store from "./store";
import App from "./App";

// PAGES
Vue.component('app', App);
Vue.component('product-creation-page', require('./components/pages/product-creation-page.js').default);

new Vue({
    router,
    store
}).$mount("#app");
