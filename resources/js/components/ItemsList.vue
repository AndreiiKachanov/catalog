<template>
    <h3 v-if="title" class="text-left mt-3 my-h3"><span>{{ title }}</span></h3>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4 col-lg-3 mb-3 pl-sm-1 pr-sm-1 my-cart"
             v-for="item in items.slice(0, this.countStart)"
             :key="item.id"
        >
            <div class="card h-100">
                <div class="budgets" :class="singleItemClass(item)"
                     v-if="item.is_new || item.is_hit || item.is_bestseller">
                    <span v-if="item.is_new" class="badge badge-pill badge-primary">Новый</span>
                    <span v-if="item.is_hit" class="badge badge-pill badge-warning">Хит</span>
                    <span v-if="item.is_bestseller" class="badge badge-pill badge-success">Бестселлер</span>
                </div>
                <img class="card-img-top img-fluid" :src="item.img" alt=""/>
                <div class="card-body">
                    <h3 class="card-title text-center">{{ item.title }}</h3>
                    <p v-if="item.note" class="text-muted" style="color: red">
                        <span style="color: red">Важно:</span> {{ item.note }}
                    </p>
                    <p v-if="item.min_order_amount" class="text-muted" style="color: red">
                        Мин. заказ <strong>{{ item.min_order_amount }}</strong> шт.
                    </p>

                    <p class="d-flex justify-content-between mb-2 align-items-center">
                        <span style="font-size: 9px">Артикул:</span>
                        <span style="font-size: 9px">{{ item.article_number }}</span>
                    </p>
                    <h4 class="d-flex justify-content-between mb-2 align-items-center">
                        <span style="font-size: 17px">Цена:</span>
                        <span style="font-size: 17px; font-weight: bold">{{ item.price }} ₽</span>
                    </h4>
                </div>
                <div class="card-footer">
                    <div class="container">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-6 pl-0 pr-0">
                                <count-items :count-from-cart="getCountFromCart(item.id)"
                                             @set-cnt="setNewCnt(item.id, $event)"></count-items>
                            </div>
                            <div class="col-5 cart-buttons pl-0 pr-0">
                                <button style="width: 100%;" v-if="inCart(item.id)" class="btn btn-sm btn-danger"
                                        @click="removeFromCart(item.id)">
                                    Убрать
                                </button>
                                <button style="width: 100%;" v-else class="btn btn-sm btn-success"
                                        @click="addToCart(item.id)">
                                    В корзину
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="countStart < items.length" class="col-12 text-center">
            <button
                class="btn btn-sm btn-success mt-2 mb-3"
                @click="countStart += countLoad"
            >Показать еще
            </button>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import CountItems from '../components/CountItems';

export default {
    name: "ItemsList",
    props: ['items', 'title', 'type-list'],
    data() {
        return {
            countStart: '',
            countLoad: ''
        }
    },
    components: {
        CountItems
    },
    computed: {
        ...mapGetters('cart', {
            cartAll: 'all',
            inCart: 'has',
            productsTemp: 'productsTemp',
            getCountFromCart: 'getCountFromCart'
        }),
    },
    methods: {
        ...mapActions('cart', {
            addToCart: 'add',
            removeFromCart: 'remove',
            setCnt: 'setCnt',
            setTempCnt: 'setTempCnt'
        }),
        setNewCnt(id, cnt) {
            if (cnt <= 0) {
                return;
            }
            this.inCart(id) ? this.setCnt({id, cnt}) : this.setTempCnt({id, cnt});
        },
        singleItemClass(pr) {
            let cntTrue = [pr.is_new, pr.is_hit, pr.is_bestseller].filter(Boolean).length;
            return {
                'single-item': cntTrue === 1,
            }
        },
    },
    created() {
        function isMdDisplay(width) {
            return width >= 768 && width <= 991;
        }

        this.countStart = this.typeList === 'short'
            ? isMdDisplay(innerWidth) ? 6 : 4
            : isMdDisplay(innerWidth) ? 9 : 12;

        this.countLoad = isMdDisplay(innerWidth) ? 9 : 8
    },
}
</script>

<style lang="scss">
.my-h3 {
    margin-bottom: 25px;

    span {
        padding-bottom: 5px;
        border-bottom: 3px solid #c1034a;
    }
}

.my-cart {
    .card-footer {
        @include media-breakpoint-down(xs) {
            .container {
                padding: 0;

                .row {
                    .cart-buttons {
                        button {
                            font-size: .6rem;
                            padding: 0.375rem 0.1rem;
                        }
                    }

                    margin: 0;
                }
            }
            padding-left: 0.8rem;
            padding-right: 0.8rem;
        }

        @include media-breakpoint-down(lg) {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

    }

    .text-muted {
        margin-bottom: 8px;
    }

    .budgets {
        span.badge {
            padding: 0.7em 1.3em;
            @include media-breakpoint-down(xs) {
                font-size: 9px;
                margin-left: 0.2em;
                margin-right: 0.2em;
                padding-right: 0.5rem;
                padding-left: 0.5rem;
            }
        }

        display: flex;
        position: absolute;
        width: 100%;
        justify-content: space-around;
        margin: .7rem 0;
    }

    .single-item {
        justify-content: flex-start;
        padding-left: 14px;
    }

    @include media-breakpoint-down(xs) {
        &:nth-child(odd) {
            padding-right: 0.25rem !important;
        }

        &:nth-child(even) {
            padding-left: 0.25rem !important;
        }
        .card-body {
            padding: 0.6rem;

            .card-title {
                font-size: 1.1rem;
            }

            .text-muted {
                margin-bottom: 8px;
                font-size: 0.9rem;
                word-break: break-all;
            }
        }
    }
}

</style>
