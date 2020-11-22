<template>
    <tr>
        <td>{{ name }}</td>
        <td>{{ price }} €</td>
        <td>
            <button
                class="btn btn-primary"
                type="button"
                @click="addToCart()"
            >
                <span class="material-icons">add_shopping_cart</span>
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "ProductItem",
        props: {
            name: {
                type: String,
                required: true,
            },
            price: {
                type: Number,
                required: true,
            },
            id: {
                type: Number,
                required: true,
            },
        },
        methods: {
            addToCart() {
                this.$store.dispatch('cart/addToCart', this.id);
            },
        }
    }
</script>

