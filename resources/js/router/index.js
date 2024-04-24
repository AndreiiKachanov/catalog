import {createRouter, createWebHistory} from 'vue-router';

import MainPage from '../views/MainPage.vue';
import Cart from '../views/Cart';
import Contacts from '../views/Contacts';
import Checkout from '../views/Checkout';
import Product from '../views/Product';
import CategoryPage from '../views/CategoryPage.vue';
import Search from '../views/Search.vue';
import E404 from '../views/E404';

// массив, который описывает пути доступные на нашем сайте
let routes = [
    {
        name: 'products',
        path: '/',
        component: MainPage
    },
    {
        name: 'cart',
        path: '/cart',
        component: Cart
    },
    {
        name: 'contacts',
        path: '/contacts',
        component: Contacts
    },
    {
        name: 'checkout',
        path: '/order',
        component: Checkout
    },
    {
        name: 'category',
        path: '/category/:slug',
        component: CategoryPage
    },
    {
        name: 'search',
        path: '/search',
        component: Search
    },
    {
        name: 'products-item',
        path: '/product/:slug',
        component: Product
    },
    {
        name: 'E404',
        path: '/:pathMatch(.*)',
        component: E404
    }
];

export default createRouter({
    routes,
    history: createWebHistory('/')
});
