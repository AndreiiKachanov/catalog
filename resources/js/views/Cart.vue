<template>
    <hr>
    <div v-if="hasProductsInCart" class="container mb-4">
        <div class="row">
            <div v-for="product in products" :key="product.id" class="main-row col-12">
                <div class="row ml-0 mr-0">
                    <div class="col-3 col-sm-2 pl-0 pr-0">
                        <router-link
                            :to="`/product/${product.slug}`"
                            v-slot="{ route, isExactActive, navigate }"
                            :custom="true"
                        >
                            <a :href="route.fullPath" @click="navigate" class="item fruit">
                                <img :src="`${product.img}`" class="img-thumbnail" :alt="`${product.title}`" :title="`${product.title}`">
                            </a>
                        </router-link>
                    </div>
                    <div class="col-9 col-sm-10 pl-3 pr-0 pt-3">
                        <div class="row ml-0 mr-0">
                            <div class="col-12 pl-0 pr-0 mb-1">
                                <router-link
                                    :to="`/product/${product.slug}`"
                                    v-slot="{ route, isExactActive, navigate }"
                                    :custom="true"
                                >
                                    <a :href="route.fullPath" @click="navigate">
                                        {{ product.title }}
                                    </a>
                                </router-link>
                            </div>
                            <div class="col-12 pl-0 pr-0 mb-1">
                                <p v-if="!minOrderAmount && product.min_order_amount">Мин. заказ <strong>{{ product.min_order_amount }}</strong> шт.</p>
                            </div>
                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="col-5 pl-0 pr-1">
                                        <div class="row ml-0 mr-0" style="text-align: right">
                                            <div class="col-12 pl-0 pr-0">
                                                {{ product.cnt }} x {{ product.price }} ₽
                                            </div>
                                            <div class="col-12 pl-0 pr-0">
                                                <strong>
                                                    {{ product.price * product.cnt }} ₽
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5 cart-items pl-0 pr-0">
                                        <count-items
                                            :has-min-order="orderQuantityBelowMinimum(product)"
                                            :count-from-cart="getCountFromCart(product.id)"
                                            @set-cnt="setNewCnt2(product.id, $event)"
                                        >
                                        </count-items>
                                    </div>
                                    <div class="col-2">
                                        <button
                                            class="btn btn-sm btn-danger"
                                            @click="remove(product.id)"
                                        ><i class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -6px;">
                                    <div class="col-3 col-sm-5"></div>
                                    <div class="min-parent col-7 col-sm-5 d-flex justify-content-center pl-0 pr-0">
                                        <div v-if="checkMinOrderEvery(product.cnt, product.min_order_amount)" class="min text-center">
                                            Мин. заказ <strong>{{ product.min_order_amount }}</strong> шт.
                                        </div>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p v-if="isClickCheckout && minOrderAmount !== null && totalCost < minOrderAmount" class="text-center mb-0" style="font-size: 17px; color: red;">
                    Минимальный заказ {{ minOrderAmount }} ₽
                </p>
            </div>
            <div class="col-12 text-center mt-3">
                <router-link
                    v-if="cartCnt"
                    :to="{ name: 'checkout' }"
                >
                    <span class="btn btn-sm btn-success" @click.prevent="checkBeforeLeave">Оформить - <strong>{{ cartTotal }} ₽</strong></span>
                </router-link>
            </div>
        </div>
    </div>
    <p class="text-center" v-else>Корзина пустая</p>

</template>

<script>
    import {mapGetters, mapActions} from "vuex";
    import CountItems from "../components/CountItems.vue";
    import AppE404 from '../components/E404';
    import test from "./Test.vue";

    export default {
        name: "Cart",
        data: () => ({
            isClickCheckout: false
        }),
        components: {
            CountItems,
            AppE404
        },
        computed: {
            ...mapGetters('cart', {
                products: 'productsDetailed',
                cartTotal: 'total',
                cartCnt: 'length',
                all: 'all',
                inCart: 'has',
                getCountFromCart: 'getCountFromCart'
            }),
            ...mapGetters('settings', {
                minOrderAmount: 'minOrderAmount'
            }),
            ...mapGetters('order', {
                isValidShoppingCart: 'isValidShoppingCart'
            }),
            hasProductsInCart() {
                return this.cartCnt > 0;
            },
            totalCost() {
                let totalCost = 0;
                for (let i = 0; i < this.products.length; i++) {
                    totalCost += this.products[i].cnt * this.products[i].price;
                }
                return totalCost;
            },
            // Определяем, что выбрано больше или столько же товаров, как в админке
            isMoreSelectedThanAdmin() {
                // console.log('короткая функция every', this.products.every(item => item.cnt >= item.min_order_amount));
                return this.products.every(item => item.cnt >= item.min_order_amount);
            },
            isMinOrder(){
                return false;
            }

        },
        methods: {
            ...mapActions('cart', ['setCnt', 'remove']),
            ...mapActions('order', ['setIsValidShoppingCart']),
            setNewCnt({id, cnt}) {
                // console.log('id=', id);
                if (this.inCart(id)) {
                    // console.log('В корзине есть такой товар');
                    // console.log('id =' + id, 'cnt=' + cnt);
                    if (cnt > 0) {
                        this.setCnt({id, cnt})
                    } else {
                        // console.log('cnt < 0, ничего не делаем');
                    }
                }
                else {
                    // console.log('В корзине нет такого товара');
                }
            },
            setNewCnt2(id, cnt) {
                // console.log(1);
                // console.log(arguments)
                if (this.inCart(id)) {


                    this.setCnt({id, cnt})
                    // if (cnt > 0 && cnt < 65536) {
                    //     this.setCnt({id, cnt})
                    // } else {
                        // console.log('cnt < 0, ничего не делаем');
                    // }
                }
                else {
                    // console.log('В корзине нет такого товара');
                    // console.log('cnt = ' + cnt);
                    if (cnt > 0) {
                        // console.log(1);
                        this.setTempCnt({id, cnt})
                    }
                }
            },
            checkMinOrderEvery(cnt, minOrderAmountInCart) {
                // если нажата кнопка и кол-во выбранных меньше чем в админке в товарах
                return this.isClickCheckout && this.minOrderAmount === null && cnt < minOrderAmountInCart;
            },
            checkBeforeLeave() {
                // Устанавливаем, что кнопка 'Оформить' нажата
                // if (!this.isClickCheckout) {
                //     this.isClickCheckout = true;
                // }
                // то же самое, как и сверху
                // Этот оператор присваивает true переменной this.isClickCheckout, только если она имеет значение false или undefined. В противном случае она остается без изменений.
                this.isClickCheckout ||= true;
                const minOrderAmount = this.minOrderAmount;
                // Если мин. сумма не указана в админке
                if (minOrderAmount === null) {
                    if (this.isMoreSelectedThanAdmin) {
                        this.setIsValidShoppingCart(true);
                        return this.$router.push({ name: 'checkout' });
                    }
                } else {
                    // Иначе если мин. сумма указана
                    if (this.totalCost >= minOrderAmount) {
                        this.setIsValidShoppingCart(true);
                        return this.$router.push({ name: 'checkout' });
                    }
                }
                this.setIsValidShoppingCart(false);
            },
            orderQuantityBelowMinimum(product) {
                return !this.minOrderAmount && this.isClickCheckout && product.min_order_amount > product.cnt;
            },
        }
    }
</script>

<style lang="scss">
    .main-row {
        border-bottom: 1px solid #eeeeee;
        padding-top: 15px;
        padding-bottom: 15px;
        &:first-child {
            padding-top: 0;
        }

        .cart-items {
            @include media-breakpoint-down(xs) {
                span.input-group-prepend, span.input-group-append, input[type='text'] {
                    button {
                        padding: 0.25rem 0.35rem
                    }
                }
                input[type='text'] {
                    padding-left: 0;
                    padding-right: 0;
                }
            }
        }

        .min-parent {
            @include media-breakpoint-down(xs) {
                margin-left: 20px;
            }
            .min {
                color: red;
                @include media-breakpoint-down(xs) {
                    font-size: 0.8rem;
                }
            }
        }
    }
</style>
