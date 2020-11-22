<template>
    <tr>
        <td>{{ name }}</td>
        <td>{{ price }} €</td>
        <td>{{ quantity }}</td>
        <td>{{ totalPrice }} €</td>
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
            }
        },
        computed: {
            totalPrice() {
                return this.price * this.quantity;
            }
        }
    }
</script>
