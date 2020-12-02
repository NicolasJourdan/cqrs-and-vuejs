<template>
    <tr>
        <td>{{ name }}</td>
        <td>{{ price }} €</td>
        <td>{{ quantity }}</td>
        <td>{{ totalPrice }} €</td>
        <td>
            <b-button variant="primary" @click="deleteFromCart(itemId)">
                <b-icon icon="trash"></b-icon>
            </b-button>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "CartItem",
        props: {
            name: {
                type: String,
                required: true,
            },
            price: {
                type: Number,
                required: true,
            },
            quantity: {
                type: Number,
                required: true,
            },
            itemId: {
                type: Number,
                required: true,
            }
        },
        computed: {
            totalPrice() {
                return this.price * this.quantity;
            }
        },
        methods: {
            deleteFromCart(id) {
                this.$store.dispatch('cart/removeFromCart', id);
            }
        },
    }
</script>
