<template>
    <div class="row">
        <div class="col-6 col-md-4 col-lg-3 mb-3 mt-3 pl-sm-1 pr-sm-1 my_cart"
             v-for="product in products"
             :key="product.id"
        >
            <div class="card h-100">
                <div class="budgets" :class="singleItemClass(product)" v-if="product.is_new || product.is_hit || product.is_bestseller">
                    <span v-if="product.is_new" class="badge badge-pill badge-primary">Новый</span>
                    <span  v-if="product.is_hit" class="badge badge-pill badge-warning">Хит</span>
                    <span  v-if="product.is_bestseller" class="badge badge-pill badge-success">Бестселлер</span>
                </div>
                <img class="card-img-top img-fluid" :src="product.img" alt=""/>
                <div class="card-body">
                    <h3 class="card-title text-center">{{ product.title }}</h3>
                    <p v-if="product.note" class="text-muted" style="color: red">
                        <span style="color: red">Важно:</span> {{ product.note }}
                    </p>

                    <p class="d-flex justify-content-between mb-2 align-items-center">
                        <span style="font-size: 9px">Артикул:</span>
                        <span style="font-size: 9px">{{ product.article_number }}</span>
                    </p>
                    <h4 class="d-flex justify-content-between mb-2 align-items-center">
                        <span style="font-size: 17px">Цена:</span>
                        <span style="font-size: 17px; font-weight: bold">{{ product.price }} ₽</span>
                    </h4>
                </div>
                <div class="card-footer">
                    <div class="container">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-6 pl-0 pr-0">
                                <count-items :count-from-cart="getCountFromCart(product.id)" @set-cnt="setNewCnt(product.id, $event)"></count-items>
                            </div>
                            <div class="col-5 cart-buttons pl-0 pr-0">
                                <button style="width: 100%;" v-if="inCart(product.id)" class="btn btn-danger" @click="removeFromCart(product.id)">
                                    Убрать
                                </button>
                                <button style="width: 100%;" v-else class="btn btn-success" @click="addToCartNew(product.id)">
                                    В корзину
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import CountItems from '../components/CountItems';

    export default {
        name: "ProductsList",
        props: ['products'],
        data: () => ({
        }),
        components: {
            CountItems
        },
        computed: {
            ...mapGetters('cart', {
                cartAll: 'all',
                inCart: 'has',
                productsTemp: 'productsTemp',
                getCountFromCart: 'getCountFromCart'
            })
        },
        methods: {
            ...mapActions('cart', {
                addToCartNew: 'addNew',
                // addToCart: 'add',
                removeFromCart: 'remove',
                setCnt: 'setCnt',
                setTempCnt: 'setTempCnt'
            }),
            setNewCnt(id, cnt) {
                // console.log(arguments)
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
                    // console.log('cnt = ' + cnt);
                    if (cnt > 0) {
                        // console.log(1);
                        this.setTempCnt({id, cnt})
                    }
                }
            },
            singleItemClass(pr){
                // console.log('id=',pr.id);
                let cntTrue = [pr.is_new, pr.is_hit, pr.is_bestseller].filter(Boolean).length
                // console.log('cntTrue', cntTrue);
                // console.log([pr.is_new, pr.is_hit, pr.is_bestseller].filter(Boolean).length);
                // console.log(pr);
                return {
                    'single-item': cntTrue === 1,
                }
            }
        }
    }
</script>

<style lang="scss">
    .parent {
        text-align: center;
        display: block;
        color: white;
        font-weight: 700;
        text-decoration: none;
        user-select: none;
        padding: 1em 2em;
        border: 1px solid;
        border-radius: 1px;
        word-break: break-all;
        background-color: #c1034a;
    }
    .my_cart {
        .card-footer {
            //justify-content: space-around;
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
            //max-height: 60px;
            .countItems {
                @include media-breakpoint-down(xs) {
                }
            }

            //.cart-buttons {
            //    @include media-breakpoint-down(xs) {
            //    }
            //    button {
            //        width: 100%;
            //        font-size: 14px;
            //        @include media-breakpoint-down(xs) {
            //            font-size: 12px;
            //            padding-left: 5px;
            //            padding-right: 5px;
            //        }
            //    }
            //}
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
                    //word-break: break-all;
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
