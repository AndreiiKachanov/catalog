<template>
    <div>
        <loader :is-loading="loading"></loader>
    </div>
    <div v-if="hasProductsInCart || flagOrderSent" class="pb-3">
        <div v-if="flagOrderSent && !hasProductsInCart">
            <div class="col-10 col-sm-6 offset-1 offset-sm-3 text-center mt-3 mb-2">
                <h4>Ваш заказ удачно отправлен. Ожидайте звонка менеджера.</h4>
            </div>
        </div>
        <div v-else class="row">
            <div class="col-10 col-sm-6 offset-1 offset-sm-3 text-center mt-3 mb-2">
                <h2>Заполните форму</h2>
            </div>
            <div class="col-12 col-lg-8 offset-lg-2">
                <form @submit.prevent="sendForm">
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" :style="progressStyles"></div>
                    </div>
                    <app-field
                        v-for="(field, i) in info"
                        :value="field.value"
                        :label="field.label"
                        :valid="field.valid"
                        :activated="field.activated"
                        :name="field.name"
                        :key="i"
                        @input="onInput(i, $event)"
                        @change="onChange(i, $event, field.name)"
                    >
                    </app-field>
                    <h2 class="text-center mb-2">Ваш заказ</h2>
                    <div class="out-border">
                        <div class="mb-2 mt-2 text-center">
                            <span>№ заказа: </span><span class="ml-1 font-weight-bold">{{ billNumber }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                <tr>
                                    <th style="white-space: nowrap">№</th>
                                    <th style="text-align: center;" colspan="2">Товар</th>
                                    <th style="text-align: right; white-space: nowrap">Кол-во</th>
                                    <th style="text-align: right;">Цена</th>
                                    <th style="text-align: right;">Всего</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in products" :key="product.id">
                                    <td style="vertical-align: middle;"></td>
                                    <td style="width: 15%; padding: 5px 0; vertical-align: middle;">
                                        <img
                                        style="padding: 0"
                                        :src="`${product.img}`"
                                        class="img-thumbnail"
                                        :alt="`${product.title}`"
                                        :title="`${product.title}`"
                                        >
                                    </td>
                                    <td style="width: 40%;">{{ product.title }}</td>
                                    <td style="text-align: right;">{{ product.cnt }}</td>
                                    <td style="text-align: right; white-space: nowrap">{{ product.price }} ₽</td>
                                    <td style="text-align: right; white-space: nowrap">{{ product.price * product.cnt }} ₽</td>
                                </tr>
                                <tr style="font-size: 0.9rem; font-weight: bold;">
                                    <td colspan="3">Всего:</td>
                                    <td colspan="3" style="text-align: center;">{{ cartTotal }} ₽</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <router-link :to="{ name: 'cart' }" class="btn btn-sm mr-5 btn-danger pl-3 pr-3">
                            Отменить
                        </router-link>
                        <button class="btn btn-sm btn-success pl-3 pr-3" :disabled="!formReady">
                            Заказать
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <app-e404 v-else title="Page not found"></app-e404>
</template>

<script>
    import AppE404 from '../components/E404';
    import { mapActions, mapGetters } from "vuex";
    import AppField from "../components/AppField.vue";
    import Loader from "../components/Loader.vue"

    export default {
        components: {
            AppField,
            AppE404,
            Loader
        },
        data: () => ({
            info: [
                {
                    label: 'ФИО:',
                    value: '',
                    pattern: /^[а-яА-Я \-]{2,50}$/,
                    name: 'name'
                },
                {
                    label: 'Номер телефона:',
                    value: '',
                    // Съедает следующие телефоны:
                    // +7(903)888-88-88
                    // 8(999)99-999-99
                    // +380(67)777-7-777
                    // 001-541-754-3010
                    // +1-541-754-3010
                    // 19-49-89-636-48018
                    // +233 205599853
                    pattern: /^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/,
                    name: 'phone'
                },
                {
                    label: 'Город:',
                    value: '',
                    // pattern: /^[a-zA-Z ]{2,30}$/,
                    // любой буквенный символ или дефис или пробел
                    pattern: /^[а-яА-Я \-]{2,50}$/,
                    name: 'city'
                },
                {
                    label: 'Улица:',
                    value: '',
                    pattern: /^[а-яА-Я \-\.]{2,50}$/,
                    name: 'street'
                },
                {
                    label: 'Номер дома:',
                    value: '',
                    pattern: /^[0-9а-яА-Я \-]{1,50}$/,
                    name: 'house_number'
                },
                {
                    label: 'Транспортная компания:',
                    value: '',
                    pattern: /^[а-яА-Я \-]{2,50}$/,
                    name: 'transport_company'
                }
            ],
            formDone: false,
            loading: false,
        }),
        // beforeRouteEnter(to, from, next) {
        //     const data = to.params.test111; // Получение данных из параметра
        //     console.log(data);
        //     // next();
        // },
        computed: {
            ...mapGetters('cart', {
                products: 'productsDetailed',
                cartTotal: 'total',
                billNumber: 'billNumber',
                token: 'token',
                cartCnt: 'length',
                flagOrderSent: 'flagOrderSent',
                testData: 'getTestData'
            }),
            ...mapGetters('order', ['order', 'isValidShoppingCart']),
            hasProductsInCart() {
                return this.cartCnt > 0;
            },
            fieldsDone() {
                return this.info.reduce((t, f) => t + (f.valid ? 1 : 0), 0);
            },
            formReady() {
                return this.fieldsDone === this.info.length;
            },
            progressStyles() {
                return {
                    // кол-во процентов заполненных полей
                    width: (this.fieldsDone / this.info.length * 100) + '%'
                }
            }
        },
        methods: {
            ...mapActions('cart', ['sendOrderToStore']),
            ...mapActions('order', ['setOrderField', 'sendCustomerInfoToServer']),
            onInput(i, e) {
                let field = this.info[i];
                let value = e.target.value;
                field.value = value.trim();
                field.valid = field.pattern.test(field.value);
                field.activated = true;
                // console.log(field.name);
                this.setOrderField({ field: field.name, value });
            },
            onChange(i, e, field) {
                let val = e.target.value;
                let value = val.length > 255 ? val.slice(0, maxLength) : val;
                this.sendCustomerInfoToServer({ field, value });
            },
            sendForm(e) {
                if (this.formReady) {
                    this.loading = true;
                    this.sendOrderToStore({
                        name: e.target.elements.name.value,
                        phone: e.target.elements.phone.value,
                        city: e.target.elements.city.value,
                        street: e.target.elements.street.value,
                        house_number: e.target.elements.house_number.value,
                        transport_company: e.target.elements.transport_company.value,

                    }).then(() => {
                        this.loading = false;
                        this.formDone = true;
                    });
                } else {
                    console.log('Форма не готова');
                }
            },
        },
        beforeMount() {
            if(!this.isValidShoppingCart) {
                return this.$router.push({ name: 'cart' });
            }
        },
        created() {
            return this.info.forEach(field => {
                // console.log(this.order.name);
                // field.value = this.order.name;
                if (field.name in this.order) {
                    field.value = this.order[field.name];
                }
                field.activated = field.value !== '';
                field.valid = field.pattern.test(field.value);
            });
        }
    }
</script>

<style lang="scss">
    .out-border {
        border: 1px solid #c6c7c7;
        border-radius: 5px;
    }

    .table {
        counter-reset: trCount;
        vertical-align: middle;
    }
    .table tr th:not(:first-child) {
        vertical-align: middle;
    }
    .table tr:not(:last-child) td:first-child:before {
        counter-increment: trCount;
        content:counter(trCount);
    }

    .table tr th:first-child {
        padding-left: 10px;
    }

    .table tr td:first-child {
        padding-left: 5px !important;
        padding-right: 10px !important;
        //vertical-align: middle;
        text-align: center;
    }

    .table tr td:last-child, .table tr th:last-child {
        //padding-right: 10px;
        text-align: center;
    }

    .table th, .table td {
        border-color: #c6c7c7;
    }

    .table thead th {
        border-bottom: none;
    }

    h2 {
        @include media-breakpoint-down(xs) {
            font-size: 1.5rem;
        }
    }

    .table {
        @include media-breakpoint-down(xs) {
            th, td {
                font-size: 0.75rem;
            }
        }
    }
</style>
