import { makeRequest, makeRequestPost, makeRequestPostJson } from "../api/server";

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
        // testData: [
        //     { name: 'name', value: '1' },
        //     { name: 'phone', value: '2' },
        //     { name: 'city', value: '3' },
        //     { name: 'city', value: '4' },
        //     { name: 'street', value: '5' },
        //     { name: 'house_number', value: '6' },
        //     { name: 'transport_company', value: '7' }
        // ],
        testData: 'test123',
        token: null,
        billNumber: null,
        flagOrderSent: false
    },
    getters: {
        all: state => state.products,
        productsTemp: state => state.productsTemp,
        length: state=> state.products.length,
        billNumber: state=> state.products.length > 0 ? state.billNumber : null,
        token: state => state.token,
        flagOrderSent: state=> state.flagOrderSent,
        // лежит товар в корзине или не лежит
        // параметрозованный геттер
        // has(state) {
            // return function (id) {
                // хотябы 1 элемент с таким id нашелся
        //         return state.products.some(pr => pr.id === id);
        //     }
        // },
        // короткий вариант записи
        // хотябы 1 элемент с таким id нашелся
        has: state => (id) => state.products.some(pr => pr.id === id),
        hasTemp: state => (id) => state.productsTemp.some(pr => pr.id === id),
        getCountFromCart(state, getters) {
          return function (id) {
              if (getters.has(id)) {
                  return state.products[state.products.findIndex(pr => pr.id === id)].cnt;
              } else if (getters.hasTemp(id))  {
                  return state.productsTemp[state.productsTemp.findIndex(pr => pr.id === id)].cnt;
                  // return 1;
              } else {
                  return 1;
              }
          }
        },

        // выбранные товары
        // с помощью rootGetters обращается в модуль products и достаём с помощью гетера item по id инфу о товаре
        // далее склеиваем массивы
         productsDetailed(state, getters, rootState, rootGetters) {
            return state.products.map(pr => {
              // let info = rootGetters['products/item'](pr.id);
              let info = rootGetters['products/item'](pr.id);
              // console.log('>>>2', { ...info });
              return { ...pr, ...info };
            });

        },
        // подсчет суммы товаров в корзине
        total: (state, getters) => getters.productsDetailed.reduce( (t, pr) => t + pr.price * pr.cnt, 0),
        getTestData: state => state.testData,
    },
    mutations: {
        add(state, id) {
            state.products.push({ id, cnt: 1});
        },
        addNew(state, { id, cnt }) {
            state.products.push({ id, cnt: cnt});
        },
        remove(state, id) {
            state.products = state.products.filter(pr => pr.id !== id);
        },
        setCnt(state, { id, cnt }) {
            // console.log(id, cnt);
            let i = state.products.findIndex(pr => pr.id === id);
            state.products[i].cnt = Math.max(1, cnt);
        },
        setTempCnt(state, { id, cnt }) {
            // console.log('test');
            // console.log(id, cnt);
            let i = state.productsTemp.findIndex(pr => pr.id === id);
            // console.log('i = ' + i);
            state.productsTemp[i].cnt = Math.max(1, cnt);
        },
        setTempCntPushArr(state, { id, cnt }) {
            // console.log(id, cnt);
            state.productsTemp.push({ id: id, cnt: cnt});
            // state.productsTemp[i].cnt = Math.max(1, cnt);
        },
        setCart(state, { token, cart }) {
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
        },
        setTestData(state, value) {
            // console.log(value);
            // state.testData[0].value = value;
            state.testData = value;
        },
    },
    actions: {
        async add({ state, getters, commit }, id) {
            // если в корзине нет такого элемента
            if (!getters.has(id)) {
                // let url = `/api/cart/add?token=${state.token}&id=${id}`;
                let url = `/api/cart/add/${state.token}/${id}`;
                let res = await makeRequestPost(url);
                if (res) {
                    commit('add', id);
                }
            } else {
                console.log('В корзине такой элемент уже есть');
            }
        },
        async addNew({ state, getters, commit }, id) {
            // если в корзине нет такого элемента
            if (!getters.has(id)) {
                let cnt = getters.hasTemp(id) ? state.productsTemp[state.productsTemp.findIndex(pr => pr.id === id)].cnt : 1 ;
                // console.log(cnt);

                let url = `/api/cart/add`;
                let token = state.token;
                try {
                    let res = await makeRequestPostJson(url, { token, id, cnt });
                    if (res) {
                        commit('addNew', { id, cnt });

                        // console.log(state.billNumber);
                        // console.log(this);
                        // console.log(state.token);

                        if (state.billNumber === null) {
                            this.dispatch('cart/setBillNumber', state.token);
                        } else {
                            // console.log('bill_number !== null');
                        }
                    }
                } catch (e) {
                    console.log(e);
                }

                // let url = `/api/cart/add/token/${state.token}/item/${id}/count/${cnt}`;
                // let res = await makeRequestPost(url);
                // if (res) {
                //     commit('addNew', { id, cnt });
                //
                //     // console.log(state.billNumber);
                //     // console.log(this);
                //     // console.log(state.token);
                //
                //     if (state.billNumber === null) {
                //         this.dispatch('cart/setBillNumber', state.token);
                //     } else {
                //         // console.log('bill_number !== null');
                //     }
                // }
            } else {
                console.log('В корзине такой элемент уже есть');
            }
        },
        async remove({ state, getters, commit }, id) {
            if (getters.has(id)) {

                // let url = `/api/cart/remove/${state.token}/${id}`;
                // let res = await makeRequestPost(url);
                // if (res) {
                //     commit('remove', id);
                // }

                let url = `/api/cart/remove`;
                let token = state.token;
                try {
                    let res = await makeRequestPostJson(url, { token, id });
                    if (res) {
                        commit('remove', id);
                    }
                } catch (e) {
                    console.log(e);
                }

            } else {
                console.log('Товар уже удален из корзины');
            }
        },
        // async setCnt(state, store, id, cnt) {
        async setCnt({ state, getters, commit }, { id, cnt }) {
            if (cnt >= 1 && cnt <= 65535) {
                if (getters.has(id)) {
                    // console.log(state.token);
                    // console.log(id);
                    // console.log(cnt);

                    // let res = await makeRequestPostJson(url, { token, value, field });

                    // let url = `/api/cart/set-cnt/${state.token}/${id}/${cnt}`;
                    let url = '/api/cart/set-cnt';
                    let token = state.token;
                    let res = await makeRequestPostJson(url, { token, id, cnt });
                    if (res) {
                        // console.log('Кол-во товара удачно обновлено на сервере');
                        commit('setCnt', { id, cnt });
                    } else {
                        // console.log('Ошибка обновления количества товара в корзине');
                    }
                } else {
                }
            } else {
                // console.log('cnt < 1 || > 65535');
            }

        },
        setTempCnt(store, payload) {
            // console.log(store.state.productsTemp);

            // console.log(store.getters.all);
            if (store.getters.hasTemp(payload.id)) {
                // console.log('индекс есть');
                store.commit('setTempCnt', payload);
            } else {
                store.commit('setTempCntPushArr', payload);
                // console.log('индекса нет');
            }
        },
        setTempCntToLocalStorage(store, payload) {
            // console.log(store.state.productsTemp);

            // console.log(store.getters.all);
            if (store.getters.hasTemp(payload.id)) {
                // console.log('индекс есть');
                store.commit('setTempCnt', payload);
            } else {
                store.commit('setTempCntPushArr', payload);
                // console.log('индекса нет');
            }
        },
        async load({commit}) {
            let oldToken = localStorage.getItem('CART_TOKEN');
            let url = `/api/cart/load?token=${oldToken}`;
            let res = await makeRequest(url);
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
            commit('setCart', { cart, token });
            if (cart.length > 0) {
                commit('products/setItems', products, { root: true })
                this.dispatch('cart/setBillNumber', token);
            }
        },

        async setBillNumber({ commit }, token) {
            let url = `/api/cart/invoice/load?token=${token}`;
            let { bill_number } = await makeRequest(url);
            commit('setBillNumber', bill_number);
        },
        async sendOrderToStore({ state, getters, commit }, { name, phone, city, street, house_number, transport_company }) {
            // console.log({ name, phone, city, street, house_number, transport_company });

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
            let url = `/api/cart/store`;
            let { new_token } = await makeRequestPostJson(url, json);
            if (new_token) {
                console.log('Ваш заказ удачно отправлен!');
                console.log('good newToken = true');
                console.log('good newToken = ' + new_token);
                commit('clearAllProduct');
                commit('flagOrderSent', true);
                commit('setNewToken', new_token);
                commit('setBillNumber', null)
                localStorage.setItem('CART_TOKEN', new_token);
                this.dispatch('order/clearOrder');
            } else {
                console.log('sendOrderToStore - new_token != true');
            }
        },
    }
}
