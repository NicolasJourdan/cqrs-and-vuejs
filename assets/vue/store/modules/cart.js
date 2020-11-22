import CartAPI from '../../api/cart';

const
    FETCHING_ITEMS = "FETCHING_ITEMS",
    FETCHING_ITEMS_SUCCESS = "FETCHING_ITEMS_SUCCESS",
    FETCHING_ITEMS_ERROR = "FETCHING_ITEMS_ERROR",
    ADD_ITEM = "ADD_ITEM",
    ADD_ITEM_SUCCESS = "ADD_ITEM_SUCCESS",
    ADD_ITEM_ERROR = "ADD_ITEM_ERROR",
    CART_ID = 4
;

const state = {
    isLoading: false,
    items: [],
    total: 0,
    error: null,
};

const getters = {
    isLoading: state => state.isLoading,
    hasError: state => null !== state.error,
    error: state => state.error,
    hasItems: state => state.items.length > 0,
    items: state => state.items,
    total: state => state.total,
};

const mutations = {
    [FETCHING_ITEMS]: (state) => {
        state.isLoading = true;
        state.items = [];
        state.error = null;
        state.total = 0;
    },
    [FETCHING_ITEMS_SUCCESS]: (state, cart) => {
        state.isLoading = false;
        state.items = cart.items;
        state.error = null;
        state.total = cart.totalAmount;
    },
    [FETCHING_ITEMS_ERROR]: (state, error) => {
        state.isLoading = false;
        state.items = [];
        state.error = error;
        state.total = 0;
    },
    [ADD_ITEM]: (state) => {
        state.isLoading = false;
        state.error = null;
    },
    [ADD_ITEM_SUCCESS]: (state, cart) => {
        state.isLoading = false;
        state.items = cart.items;
        state.error = null;
        state.total = cart.totalAmount;
    },
    [ADD_ITEM_ERROR]: (state, error) => {
        state.isLoading = false;
        state.items = [];
        state.error = error;
        state.total = 0;
    },
};

const actions = {
    getCart: async ({ commit }) => {
        commit(FETCHING_ITEMS);
        try {
            let response = await CartAPI.getOne(CART_ID);
            commit(FETCHING_ITEMS_SUCCESS, response.data);

            return response.data;
        } catch (error) {
            commit(FETCHING_ITEMS_ERROR, error);

            return null;
        }
    },
    addToCart: async ({ commit }, productId) => {
        commit(ADD_ITEM);
        try {
            let response = await CartAPI.addItem(CART_ID, productId);
            commit(ADD_ITEM_SUCCESS, response.data);

            return response.data;
        } catch (error) {
            commit(ADD_ITEM_ERROR, error);

            return null;
        }
    }
};

export default {
    namespaced: true,
    state: state,
    getters: getters,
    actions: actions,
    mutations: mutations
}