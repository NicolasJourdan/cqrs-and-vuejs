import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import Cart from "../views/Cart";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        { path: "/", component: Home },
        { path: "/cart", component: Cart },
        { path: "/product/new" },
    ]
});
