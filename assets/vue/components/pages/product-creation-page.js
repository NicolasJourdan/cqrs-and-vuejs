export default {
    name: "ProductCreationPage",
    data() {
        return {
            price: 0
        }
    },
    created() {
        this.$store.dispatch('product/getAveragePrice');
    },
    computed: {
        averagePrice() {
            return this.$store.getters['product/averagePrice']
        },
        isInAverage() {
            let averageMiddle = this.averagePrice;
            let bornMin = averageMiddle * 0.8;
            let bornMax = averageMiddle * 1.2;

            return this.price >= bornMin && this.price <= bornMax;
        },
    },
}
