import {makeRequest, makeRequestPostJson} from '../api/server';

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
        async load({commit, rootGetters}) {
            const token = rootGetters['cart/token'];
            const url = `/api/cart/load-order?token=${token}`;
            try {
                const order = await makeRequest(url);
                commit('setOrder', order);
            } catch (error) {
                console.error('Ошибка во время загрузки информации о заказчике: ', error);
            }
        },
        async sendCustomerInfoToServer({state, commit, rootGetters}, {value, field}) {
            let token = rootGetters['cart/token'];
            let url = `/api/cart/set-order-info`;
            try {
                await makeRequestPostJson(url, {token, value, field});
            } catch (error) {
                console.error('Ошибка во время обновления информации о заказчике: ', error);
            }
        },
        setOrderField({commit}, payload) {
            commit('setOrderField', payload);
        },
        clearOrder({commit}) {
            commit('clearOrder');
        },
        setIsValidShoppingCart({commit}, flag) {
            commit('setIsValidShoppingCart', flag);
        }
    }
}
