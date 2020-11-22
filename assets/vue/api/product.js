import axios from "axios";

export default {
    create(product) {
        return axios.post("/api/products", {
            name: product.name,
            price: product.price,
        });
    },
    getOne(productId) {
        return axios.get("/api/products/" + productId);
    },
    getAll() {
        return axios.get("/api/products");
    },
    edit(product) {
        let data = {
            id: product.id
        };

        if (product.name !== undefined) {
            data.name = product.name;
        }

        if (product.price !== undefined) {
            data.price = product.price;
        }

        return axios.put("/api/products/" + product.id, data);
    },
    delete(productId) {
        return axios.delete("/api/products/" + productId);
    },
};
