import './bootstrap';
import { createApp } from 'vue';




// базовый объект, который выводится во view
import AppAdmin from './components/AppAdmin.vue';
import NestedSelect from './components/NestedSelect.vue';

import VueGoodTablePlugin from 'vue-good-table-next';
//
// // import the styles
import 'vue-good-table-next/dist/vue-good-table-next.css'

let locationPath = window.location.pathname;
console.log(locationPath);


let isOrders = /^\/admin\/categories\/[0-9]+\/orders$/.test(locationPath);


// Монтируем vue только на данных страницах
if ( isOrders) {
    console.log('test');

    const app = createApp({});
    app.use(VueGoodTablePlugin);

    app.component('app-component', AppAdmin);
    app.mount('#app');
}

let arrPath = ['/admin/items/create'];
if (arrPath.indexOf(locationPath) > -1) {
    console.log('test');

    const app = createApp({});
    // app.use(VueGoodTablePlugin);

    app.component('nested-select', NestedSelect);
    app.mount('#app');
}



