import {makeRequest, makeRequestPostJson} from '../api/server';

export default {
    namespaced: true,
    state: {
        // сохраненные в корзине товары
        products: [
            // { id: 100, cnt: 1},
            // { id: 101, cnt: 10},
        ],
        // массив выбранного количества товаров, но не сохраненного в корзину
        productsTemp: [
            // { id: 1, cnt: 5},
        ],
        token: null,
        billNumber: null,
        flagOrderSent: false
    },
    getters: {
        all: state => state.products,
        productsTemp: state => state.productsTemp,
        length: state => state.products.length,
        billNumber: state => state.products.length > 0 ? state.billNumber : null,
        token: state => state.token,
        flagOrderSent: state => state.flagOrderSent,
        // хотя бы 1 элемент с таким id нашелся
        has: state => (id) => state.products.some(pr => pr.id === id),
        hasTemp: state => (id) => state.productsTemp.some(pr => pr.id === id),
        getCountFromCart(state, getters) {
            return function (id) {
                if (getters.has(id)) {
                    return state.products[state.products.findIndex(pr => pr.id === id)].cnt;
                }
                if (getters.hasTemp(id)) {
                    return state.productsTemp[state.productsTemp.findIndex(pr => pr.id === id)].cnt;
                }
                return 1;
            }
        },
        // Выбранные товары. С помощью rootGetters обращается в модуль products и достаём с помощью геттера
        // item по id информацию о товаре, далее склеиваем массивы
        productsDetailed(state, getters, rootState, rootGetters) {
            return state.products.map(pr => {
                let info = rootGetters['products/item'](pr.id);
                return {...pr, ...info};
            });
        },
        // подсчет суммы товаров в корзине
        total: (state, getters) => getters.productsDetailed.reduce((t, pr) => t + pr.price * pr.cnt, 0),
    },
    mutations: {
        // add(state, id) {
        //     state.products.push({ id, cnt: 1});
        // },
        add(state, {id, cnt}) {
            state.products.push({id, cnt: cnt});
        },
        remove(state, id) {
            state.products = state.products.filter(pr => pr.id !== id);
        },
        setCnt(state, {id, cnt}) {
            let i = state.products.findIndex(pr => pr.id === id);
            state.products[i].cnt = Math.max(1, cnt);
        },
        setTempCnt(state, {id, cnt}) {
            let i = state.productsTemp.findIndex(pr => pr.id === id);
            state.productsTemp[i].cnt = Math.max(1, cnt);
        },
        setTempCntPushArr(state, {id, cnt}) {
            state.productsTemp.push({id: id, cnt: cnt});
        },
        setCart(state, {token, cart}) {
            state.token = token;
            state.products = cart;
        },
        setBillNumber(state, number) {
            state.billNumber = number;
        },
        flagOrderSent(state, flag) {
            state.flagOrderSent = flag;
        },
        clearAllProduct(state) {
            state.products = [];
        },
        setNewToken(state, token) {
            state.token = token;
        }
    },
    actions: {
        async add({state, getters, commit}, id) {
            // если в корзине нет такого элемента - отправляем на сервер запрос и добавляем в корзину
            if (!getters.has(id)) {
                let cnt = getters.hasTemp(id) ? state.productsTemp[state.productsTemp.findIndex(pr => pr.id === id)].cnt : 1;
                const url = `/api/cart/add`;
                const token = state.token;
                try {
                    const res = await makeRequestPostJson(url, {token, id, cnt});
                    if (res) {
                        commit('add', {id, cnt});
                        if (state.billNumber === null) {
                            this.dispatch('cart/setBillNumber', state.token);
                        }
                    }
                } catch (error) {
                    console.error('Ошибка при добавлении товара в корзину: ', error);
                }
            }
        },
        async remove({state, getters, commit}, id) {
            if (!getters.has(id)) {
                return;
            }
            const url = `/api/cart/remove`;
            const token = state.token;
            try {
                const res = await makeRequestPostJson(url, {token, id});
                if (res) {
                    commit('remove', id);
                }
            } catch (error) {
                console.error('Ошибка при удалении товара из корзины: ', error);
            }
        },
        async setCnt({state, getters, commit}, {id, cnt}) {
            if (cnt < 1 || cnt > 65535) {
                return;
            }

            if (!getters.has(id)) {
                return
            }
            const url = '/api/cart/set-cnt';
            const token = state.token;
            try {
                const res = await makeRequestPostJson(url, {token, id, cnt});
                if (res) {
                    commit('setCnt', {id, cnt});
                }
            } catch (error) {
                console.error('Ошибка при обновлении кол-во товара: ', error);
            }
        },
        setTempCnt(store, payload) {
            if (store.getters.hasTemp(payload.id)) {
                store.commit('setTempCnt', payload);
            } else {
                store.commit('setTempCntPushArr', payload);
            }
        },
        async load({commit}) {
            let oldToken = localStorage.getItem('CART_TOKEN');
            let url = `/api/cart/load?token=${oldToken}`;
            try {
                const res = await makeRequest(url);
                let needUpdateToken, cart, token, products;

                if (res.success === false) {
                    oldToken = null;
                    url = `/api/cart/load?token=${oldToken}`;
                    const response = await makeRequest(url);
                    needUpdateToken = response.needUpdateToken;
                    cart = response.cart;
                    token = response.token;
                    products = response.products;
                } else {
                    needUpdateToken = res.needUpdateToken;
                    cart = res.cart;
                    token = res.token;
                    products = res.products;
                }

                //needUpdateToken = false - значит токен есть в бд
                //needUpdateToken = true - значит токена нет в бд. Тогда записывает в localStorage новый токен, который получили с сервера
                if (needUpdateToken) {
                    localStorage.setItem('CART_TOKEN', token);
                }
                // По номеру токена вытянуть из бд данные заказа (order) и записать в хранилище
                commit('setCart', {cart, token});
                if (cart.length > 0) {
                    commit('products/setItems', products, {root: true})
                    this.dispatch('cart/setBillNumber', token);
                }
            } catch (error) {
                console.error('Ошибка при загрузке корзины: ', error);
            }
        },

        async setBillNumber({commit}, token) {
            try {
                let url = `/api/cart/invoice/load?token=${token}`;
                let {bill_number} = await makeRequest(url);
                commit('setBillNumber', bill_number);
            } catch (error) {
                console.error('Ошибка при установке номера заказа: ', error);
            }
        },
        async sendOrderToStore({state, getters, commit}, {name, phone, city, street, house_number, transport_company}) {
            let json = {
                token: state.token,
                name: name,
                phone: phone,
                city: city,
                street: street,
                house_number: house_number,
                transport_company: transport_company,
                items: getters.all
            };

            try {
                let url = `/api/cart/store`;
                let {new_token} = await makeRequestPostJson(url, json);
                if (new_token) {
                    commit('clearAllProduct');
                    commit('flagOrderSent', true);
                    commit('setNewToken', new_token);
                    commit('setBillNumber', null)
                    localStorage.setItem('CART_TOKEN', new_token);
                    this.dispatch('order/clearOrder');
                }
            } catch (error) {
                console.error('Ошибка при отправке заказа: ', error);
            }
        },
    }
}
