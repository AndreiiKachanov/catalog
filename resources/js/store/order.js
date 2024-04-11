import { makeRequest, makeRequestPost, makeRequestPostJson } from "../api/server";

export default {
    namespaced: true,
    state: {
        // сохраненные в корзине товары
        order: {},
            // {
            //     "name": "1df",
            //     "phone": "2df",
            //     "city": "3",
            //     "street": "4",
            //     "house_number": "5",
            //     "transport_company": "6"
            // }
        isValidShoppingCart: false
    },
    getters: {
        order: state => state.order,
        isValidShoppingCart: state => state.isValidShoppingCart
    },
    mutations: {
        setOrder(state, order) {
            state.order = order;
        },
        setOrderField(state, payload) {
            // console.log(state.order[0]);
            // state.order[0].`${payload.field}` = payload.value;
            state.order[payload.field] = payload.value;
        },
        clearOrder(state) {
            state.order = {}
        },
        setIsValidShoppingCart(state, flag) {
            state.isValidShoppingCart = flag;
        }
    },
    actions: {
        async load({ commit, rootGetters }) {
            let token = rootGetters['cart/token'];
            let url = `/api/cart/load-order?token=${token}`;
            let order = await makeRequest(url);
            // console.log(order);
            commit('setOrder', order);
        },
        async sendCustomerInfoToServer({ state, commit, rootGetters }, { value, field }) {
            let token = rootGetters['cart/token'];
            let url = `/api/cart/set-order-info`;
            try {
                let res = await makeRequestPostJson(url, { token, value, field });
                // if (res) {
                //     console.log(`Поле ${field} удачно обновлено`);
                // }
            } catch (e) {
                console.log(e);
            }
        },
        setOrderField({ commit }, payload) {
            commit('setOrderField', payload);
        },
        clearOrder({ commit }) {
            commit('clearOrder');
        },
        setIsValidShoppingCart({ commit }, flag) {
            commit('setIsValidShoppingCart', flag);
        }
    }
}
