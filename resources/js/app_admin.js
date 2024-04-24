import './bootstrap';
import {createApp} from 'vue';

// базовый объект, который выводится во view
import AppAdmin from './components/AppAdmin.vue';
import VueGoodTablePlugin from 'vue-good-table-next';
// import the styles
import 'vue-good-table-next/dist/vue-good-table-next.css'

let locationPath = window.location.pathname;
let isOrders = /^\/admin\/categories\/[0-9]+\/orders$/.test(locationPath);

// Монтируем vue только на данных страницах
if (isOrders) {
    const app = createApp({});
    app.use(VueGoodTablePlugin);
    app.component('orders-table', AppAdmin);
    app.mount('#app');
}
