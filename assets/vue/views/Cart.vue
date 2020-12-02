<template>
    <div>
        <div class="row col">
            <h1>Panier</h1>
        </div>
        <div class="row col">
            <CartList/>
        </div>
    </div>

</template>

<script>
    import CartList from "../components/Cart/Cart";

    export default {
        name: "Cart",
        components: {
            CartList
        }
    }
</script>
