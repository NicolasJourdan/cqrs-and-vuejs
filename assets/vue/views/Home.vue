<template>
    <div>
        <div class="row col">
            <h1>Produits</h1>
        </div>
        <div class="row col">
            <ProductList/>
        </div>
        <div class="row col">
            <h1>Panier</h1>
        </div>
        <div class="row col">
            <Cart/>
        </div>
    </div>
</template>

<script>
    import ProductList from "../components/Product/ProductList";
    import Cart from "../components/Cart/Cart";

    export default {
        name: "Home",
        components: {
            ProductList,
            Cart,
        }
    };
</script>
