<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <router-link
                    class="navbar-brand"
                    to="/"
            >
                App
            </router-link>
            <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon" />
            </button>
            <div
                    id="navbarNav"
                    class="collapse navbar-collapse"
            >
                <ul class="navbar-nav">
                    <router-link
                            class="nav-item"
                            tag="li"
                            to="/"
                            active-class="active"
                    >
                        <a class="nav-link">Accueil</a>
                    </router-link>
                    <router-link
                            class="nav-item"
                            tag="li"
                            to="/cart"
                            active-class="active"
                    >
                        <a class="nav-link">
                            Mon panier
                            <b-badge pill variant="primary" v-if="!isLoading">{{ itemsCount }}</b-badge>
                            <b-badge pill variant="primary" v-else></b-badge>
                        </a>
                    </router-link>
                    <li class="nav-item">
                        <a class="nav-link" href="/product/new">Créer un produit</a>
                    </li>
                </ul>
            </div>
        </nav>
        <router-view />
    </div>
</template>

<script>
    export default {
        name: "App",
        created() {
            this.$store.dispatch('cart/getCart');
        },
        computed: {
            itemsCount() {
                let items = this.$store.getters['cart/items'];

                return items.length;
            },
            isLoading() {
                return this.$store.getters['cart/isLoading'];
            },
        }
    }
</script>