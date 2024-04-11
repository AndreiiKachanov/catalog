import './bootstrap';
import { createApp } from 'vue';
// базовый объект, который выводится во view
import App from './components/App';

import vueAwesomeSidebar from 'vue-awesome-sidebar'
import 'vue-awesome-sidebar/dist/vue-awesome-sidebar.css'

import store from './store/index';
import router from './router/index';

let locationPath = window.location.pathname;
// console.log(locationPath);


// ^\/category\/[\w\-]+$ - любой буквенный символ или дефис
// ^\/category\/[a-z\-0-1]+$

// console.log('res=', /^\/category\/[a-z\-0-1]+$/.test(locationPath)); // false
let isSubCategoryPath = /^\/category\/[a-z0-9\-]*$/.test(locationPath);
let isProductItemPath = /^\/product\/[a-z0-9\-]*$/.test(locationPath);

// Монтируем vue только на данных страницах
let arrPath = ['/', '/cart', '/contacts', '/order', '/search'];
if (arrPath.indexOf(locationPath) > -1 || isSubCategoryPath || isProductItemPath) {
    // console.log('test');
    const app = createApp({});

    app.component('app-component', App);

    app.use(store);
    app.use(router);
    app.use(vueAwesomeSidebar);

    store.dispatch('categories/loadCategories');
    store.dispatch('settings/loadSettings');

    store.dispatch('products/load').then(() => {
        // store.dispatch('order/load').then(() => {
        //     app.mount('#app-vue');
        // });
    });

    store.dispatch('cart/load').then(() => {
        store.dispatch('order/load').then(() => {
            app.mount('#app-vue');
        });
    });

}

