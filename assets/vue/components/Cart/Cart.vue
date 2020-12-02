<template>
    <div class="container mt-3 mb-5">
        <b-overlay :show="isProcessing" rounded="sm">
            <table class="table" v-bind:class="{ 'table-hover':!isLoading }">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Nom du produit</th>
                    <th scope="col">Prix unitaire</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix total</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <tr><td class="text-center" colspan="5">Chargement du panier...</td></tr>
                </tbody>
                <tbody v-else-if="hasError">
                <tr><td class="text-center" colspan="5">{{ error }}</td></tr>
                </tbody>
                <tbody v-else-if="!hasItems">
                <tr><td class="text-center" colspan="5">Votre panier est vide.</td></tr>
                </tbody>
                <tbody v-else>
                <CartItem
                        v-for="item in items"
                        :key="item.id"
                        :name="item.product.name"
                        :price="item.product.price"
                        :quantity="item.quantity"
                        :itemId="item.id"
                />
                <tr style="background-color: rgba(0,0,0,.05);">
                    <td><b>PRIX TOTAL</b></td>
                    <td></td>
                    <td></td>
                    <td><b>{{ total }} €</b></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </b-overlay>
    </div>
</template>

<script>
    import CartItem from "./CartItem";

    export default {
        name: "Cart",
        components: {
            CartItem
        },
        created() {
            this.$store.dispatch('cart/getCart');
        },
        computed: {
            total() {
                return this.$store.getters['cart/total'];
            },
            isLoading() {
                return this.$store.getters['cart/isLoading'];
            },
            isProcessing() {
                return this.$store.getters['cart/isProcessing'];
            },
            hasError() {
                return this.$store.getters['cart/hasError'];
            },
            error() {
                return this.$store.getters['cart/error'];
            },
            hasItems() {
                return this.$store.getters['cart/hasItems'];
            },
            items() {
                return this.$store.getters['cart/items'];
            },
        }
    }
</script>
