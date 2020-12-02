<template>
    <b-modal
            :id="'modal-' + product.id"
            size="lg"
            :title="product.nom_du_produit"
            ok-only
            centered
            scrollable
    >
        <img :alt="product.nom_du_produit" :src="product.url"/>
    </b-modal>
</template>

<script>
    export default {
        name: "ProductModal",
        props: ['product'],
    }
</script>

