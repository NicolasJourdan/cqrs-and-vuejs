import axios from "axios";

export default {
    create(items) {
        return axios.post("/api/carts", {
            items: items,
        });
    },
    getOne(cartId) {
        return axios.get("/api/carts/" + cartId);
    },
    getAll() {
        return axios.get("/api/carts");
    },
    delete(cartId) {
        return axios.delete("/api/carts/" + cartId);
    },
    addItem(cartId, productId) {
        return axios.put("/api/carts/" + cartId + "/add", {
            item: {
                productId: productId,
                quantity: 1
            }
        });
    },
    removeItem(cartId, itemId) {
        return axios.put("/api/carts/" + cartId + "/remove", {
            itemId: itemId
        });
    },
    checkout(cartId) {
        return axios.put("/api/carts/" + cartId + "/checkout");
    },
};
