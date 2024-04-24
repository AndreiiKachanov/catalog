import './bootstrap';
import {createApp} from 'vue';
// базовый объект, который выводится во view
import App from './components/App';

import vueAwesomeSidebar from 'vue-awesome-sidebar'
import 'vue-awesome-sidebar/dist/vue-awesome-sidebar.css'

import store from './store/index';
import router from './router/index';

let locationPath = window.location.pathname;
let isSubCategoryPath = /^\/category\/[a-z0-9\-]*$/.test(locationPath);
let isProductItemPath = /^\/product\/[a-z0-9\-]*$/.test(locationPath);

// Монтируем vue только на данных страницах
let arrPath = ['/', '/cart', '/contacts', '/order', '/search'];
if (arrPath.indexOf(locationPath) > -1 || isSubCategoryPath || isProductItemPath) {
    const app = createApp({});

    app.component('app-component', App);

    app.use(store);
    app.use(router);
    app.use(vueAwesomeSidebar);

    store.dispatch('categories/loadCategories');
    store.dispatch('settings/loadSettings');


    store.dispatch('cart/load').then(() => {
        store.dispatch('order/load').then(() => {
            // app.mount('#app-vue');
            store.dispatch('products/load').then(() => {
                app.mount('#app-vue');
            });
        });
    });

}

