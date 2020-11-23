import ProductAPI from '../../api/product';

const
    FETCHING_PRODUCTS = "FETCHING_PRODUCTS",
    FETCHING_PRODUCTS_SUCCESS = "FETCHING_PRODUCTS_SUCCESS",
    FETCHING_PRODUCTS_ERROR = "FETCHING_PRODUCTS_ERROR",
    FETCHING_PRICE_AVERAGE = "FETCHING_PRICE_AVERAGE",
    FETCHING_PRICE_AVERAGE_SUCCESS = "FETCHING_PRICE_AVERAGE_SUCCESS",
    FETCHING_PRICE_AVERAGE_ERROR = "FETCHING_PRICE_AVERAGE_ERROR"
;

const state = {
    isLoading: false,
    products: [],
    error: null,
    averagePrice: null,
};

const getters = {
    isLoading: state => state.isLoading,
    hasError: state => null !== state.error,
    error: state => state.error,
    hasProducts: state => state.products.length > 0,
    products: state => state.products,
    averagePrice: state => state.averagePrice,
};

const mutations = {
    [FETCHING_PRODUCTS]: (state) => {
        state.isLoading = true;
        state.products = [];
        state.error = null;
    },
    [FETCHING_PRODUCTS_SUCCESS]: (state, products) => {
        state.isLoading = false;
        state.products = products;
        state.error = null;
    },
    [FETCHING_PRODUCTS_ERROR]: (state, error) => {
        state.isLoading = false;
        state.products = [];
        state.error = error;
    },
    [FETCHING_PRICE_AVERAGE]: (state) => {
        state.averagePrice = null;
    },
    [FETCHING_PRICE_AVERAGE_SUCCESS]: (state, averagePrice) => {
        state.averagePrice = averagePrice;
    },
    [FETCHING_PRICE_AVERAGE_ERROR]: (state) => {
        state.averagePrice = null;
    },
};

const actions = {
    getAll: async ({ commit }) => {
        commit(FETCHING_PRODUCTS);
        try {
            let response = await ProductAPI.getAll();
            commit(FETCHING_PRODUCTS_SUCCESS, response.data);

            return response.data;
        } catch (error) {
            commit(FETCHING_PRODUCTS_ERROR, error);

            return null;
        }
    },
    getAveragePrice: async ({ commit }) => {
        commit(FETCHING_PRICE_AVERAGE);
        try {
            let response = await ProductAPI.getAveragePrice();
            commit(FETCHING_PRICE_AVERAGE_SUCCESS, response.data.averagePrice);

            return response.data.averagePrice;
        } catch (error) {
            commit(FETCHING_PRICE_AVERAGE_ERROR);

            return null;
        }
    },
};

export default {
    namespaced: true,
    state: state,
    getters: getters,
    actions: actions,
    mutations: mutations
}