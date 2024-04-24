import {createStore} from 'vuex';

import cart from './cart'
import order from './order'
import products from './products'
import categories from './categories'
import settings from './settings'

export default createStore({
    modules: {
        products,
        cart,
        order,
        categories,
        settings
    },
    // Строгий режим запрещает менять данные отовсюду кроме мутаций.
    // Для прод режима мы его отключаем. Для дев режима он будет работать
    strict: process.env.NODE_ENV !== 'production',
});
