<template>
    <table class="table" v-bind:class="{ 'table-hover':!isLoading }">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom du produit</th>
                <th scope="col">Prix</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody v-if="isLoading">
            <tr><td class="text-center" colspan="3">Chargement des produits...</td></tr>
        </tbody>
        <tbody v-else-if="hasError">
            <tr><td class="text-center" colspan="3">{{ error }}</td></tr>
        </tbody>
        <tbody v-else-if="!hasProducts">
            <tr><td class="text-center" colspan="3">Pas de produits actuellement.</td></tr>
        </tbody>
        <tbody v-else>
            <ProductItem
                    v-for="product in products"
                    :key="product.id"
                    :name="product.name"
                    :price="product.price"
                    :id="product.id"
            />
        </tbody>
    </table>
</template>

<script>
    import ProductItem from "./ProductItem";

    export default {
        name: "ProductList",
        components: {
            ProductItem
        },
        created() {
            this.$store.dispatch('product/getAll');
        },
        computed: {
            isLoading() {
                return this.$store.getters['product/isLoading'];
            },
            hasError() {
                return this.$store.getters['product/hasError'];
            },
            error() {
                return this.$store.getters['product/error'];
            },
            hasProducts() {
                return this.$store.getters['product/hasProducts'];
            },
            products() {
                return this.$store.getters['product/products'];
            },
        }
    }
</script>
