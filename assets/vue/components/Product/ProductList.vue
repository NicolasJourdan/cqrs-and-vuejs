<template>
    <div class="container mt-3 mb-5">
        <b-row class="mb-4">
            <b-col md="4">
                <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Rechercher un produit ou un prix"></b-form-input>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <b-overlay :show="isProcessing" rounded="sm">
                    <b-table
                            striped
                            hover
                            :items="products"
                            :filter="filter"
                            :fields="fields"
                            :current-page="currentPage"
                            :per-page="perPage"
                            :head-variant="'dark'"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            sort-icon-left
                    >
                        <template v-slot:cell(actions)="data">
                            <div>
                                <b-button variant="primary" @click="addToCart(data.item.id)">
                                    <b-icon icon="cart-plus"></b-icon>
                                </b-button>
                                <b-button v-b-modal="'modal-' + data.item.id">
                                    <b-icon icon="zoom-in"></b-icon>
                                </b-button>
                                <ProductModal :product="data.item"/>
                            </div>
                        </template>
                    </b-table>
                    <b-pagination
                            v-model="currentPage"
                            :total-rows="rows"
                            :per-page="perPage"
                    ></b-pagination>
                </b-overlay>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import ProductModal from "./ProductModal";

    export default {
        name: "ProductList",
        components: {
            ProductModal
        },
        data() {
            return {
                filter: "",
                perPage: 10,
                currentPage: 1,
                fields: [
                    { key: "nom_du_produit", sortable: true, class: "align-middle" },
                    { key: "prix", sortable: true, class: "align-middle" },
                    { key: "actions", sortable: false, class: "align-middle" },
                ],
                sortBy: 'prix',
                sortDesc: false,
            };
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
                let products = this.$store.getters['product/products'];
                let data = [];
                products.forEach(function (element) {
                    data.push({
                        id: element.id,
                        nom_du_produit: element.name,
                        prix: element.price,
                        url: "https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/MX0H2_AV2?wid=1144&hei=1144&fmt=jpeg&qlt=80&op_usm=0.5,0.5&.v=1567304952106"
                    });
                });

                return data;
            },
            rows () {
                return this.products.length;
            },
            isProcessing() {
                return this.$store.getters['cart/isProcessing'];
            }
        },
        methods: {
            addToCart(id) {
                this.$store.dispatch('cart/addToCart', id);
            },
        },
    }
</script>
