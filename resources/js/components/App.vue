<template>
    <VueAwesomeSideBar
        v-if="categoriesForSidebar.length > 0"
        :width="width"
        :miniMenu="false"
        :menuType="menuType"
        :collapsed="collapsed"
        :menu="categoriesForSidebar"
        :closeOnClickOutSide="true"
        @update:collapsed="handelCollapse"
        :overLayerOnOpen="isMobile"
        :childrenOpenAnimation="true"
        :keepChildrenOpen="true"
        :BottomMiniMenuBtn="false"
        @item-click="itemClickHandel"
        vueRouterEnabel
    ></VueAwesomeSideBar>

    <div v-if="this.$route.name === 'cart'" class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h5 class="mt-2 mb-2">
                    <span>Кол-во: <strong>{{ cartCnt }}</strong> Сумма: <strong>{{ cartTotal }}</strong> &#8381</span>
                </h5>
            </div>
            <div v-if="minOrderAmount !== null" class="col-12">
                <p class="text-center mb-0" style="font-size: 17px; color: red;">
                    Минимальный заказ {{ minOrderAmount }} ₽
                </p>
            </div>
        </div>
    </div>

    <div
        v-if="this.$route.name === 'products' || this.$route.name === 'category' || this.$route.name === 'product-item'"
        class="container"
    >
        <search-component/>
    </div>

    <div class="container">
        <div class="row">
            <div class="col col-sm-12">
                <router-view></router-view>
            </div>
        </div>
    </div>
    <nav class="mobile-bottom-nav">
        <div class="mobile-bottom-nav__item mobile-bottom-nav__item--active">
            <div class="mobile-bottom-nav__item-content">
                <router-link :to="{ name: 'products' }">
                    <i class="fa fa-home fa-2x" aria-hidden="true"></i>
                    Главная
                </router-link>
            </div>
        </div>

        <div class="mobile-bottom-nav__item mobile-bottom-nav__item--active" @click="collapsed = !collapsed">
            <div class="mobile-bottom-nav__item-content">
                <a href="#">
                    <i class="fa-solid fa-chart-simple fa-2x" aria-hidden="true"></i>
                    Категории
                </a>
            </div>
        </div>

        <div class="mobile-bottom-nav__item mobile-bottom-nav__item--active">
            <div class="mobile-bottom-nav__item-content">
                <router-link :to="{ name: 'search' }">
                    <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                    Поиск
                </router-link>
            </div>
        </div>

        <div class="mobile-bottom-nav__item cart-block">
            <div class="mobile-bottom-nav__item-content">
                <router-link :to="{ name: 'cart' }">
                    <i class="fa-solid fa-cart-plus fa-2x" aria-hidden="true">
                        <span v-if="this.cartCnt > 0" class="cnt">{{ cartCnt }}</span>
                    </i>
                    <span>Корзина</span>
                </router-link>
            </div>
        </div>

        <div class="mobile-bottom-nav__item">
            <div class="mobile-bottom-nav__item-content">
                <router-link :to="{ name: 'contacts' }">
                    <i class="fa fa-address-book fa-2x" aria-hidden="true"></i>
                    Контакты
                </router-link>
            </div>
        </div>
    </nav>
</template>

<script>
import {mapGetters} from 'vuex';
import SearchComponent from './Search.vue';

export default {
    components: {
        SearchComponent
    },
    data: () => ({
        menu: [
            {route: 'products', title: 'Продукты'},
            {route: 'cart', title: 'Корзина'},
            {route: 'checkout', title: 'Заказ'}
        ],
        collapsed: true,
        menuType: 'simple',
        miniMenu: false,
        width: '370px',
        search: ''
    }),
    computed: {
        ...mapGetters('cart', {cartCnt: 'length', cartTotal: 'total'}),
        ...mapGetters('categories', {allCategories: 'allCategories'}),
        ...mapGetters('settings', {
            minOrderAmount: 'minOrderAmount'
        }),
        isMobile() {
            return this.screenWidth < 600;
        },
        filteredProducts() {
            return this.allCategories.filter(p => {
                return p.title.toLowerCase().indexOf(this.search.toLowerCase()) !== -1;
            });
        },
        categoriesForSidebar() {
            let unflatten = function (array, parent, tree) {
                tree = typeof tree !== 'undefined' ? tree : [];
                parent = typeof parent !== 'undefined' ? parent : {id: null};
                let children = _.filter(array, function (child) {
                    return child.parent_id === parent.id;
                });

                if (!_.isEmpty(children)) {
                    if (parent.id === null) {
                        tree = children;
                    } else {
                        parent['children'] = children
                    }
                    _.each(children, function (child) {
                        unflatten(array, child);
                    });
                }
                return tree;
            }

            let arr = this.allCategories.map(item => ({
                id: item.id,
                name: item.title,
                href: '/category/' + item.slug,
                parent_id: item.parent_id,
                collapseOnClick: !this.allCategories.some((element) => element.parent_id === item.id)
            }));
            return unflatten(arr);
        }
    },
    methods: {
        itemClickHandel(item) {
            if (this.isMobile && !item?.children) {
                setTimeout(() => {
                    this.collapsed = true;
                }, 200);
            }
        },
        handelCollapse(value) {
            if (value === true) {
                this.collapsed = true;
            }
        }
    },
    created() {
        this.screenWidth = innerWidth;
    },
}
</script>

<style lang="scss">
.vas-menu {
    z-index: 853 !important;

    .labelName {
        font-weight: bold;
        @include media-breakpoint-down(xs) {
            font-size: 14px;
        }
    }

    padding-top: 10px !important;
    height: calc(100vh - 65px) !important;

    .menu-item-base {
        margin-top: 6px;
    }
}

.mobile-bottom-nav {

    i.fa {
        color: red;
    }

    @include media-breakpoint-down(xs) {
        i {
            font-size: 1.4em;
        }
    }

    span.cnt {
        font-family: TTNormsRegular, sans-serif;
        font-size: 15px;
        font-weight: bold;
        position: absolute;
        border: 1px solid #ccc;
        padding: 1px 5px;
        border-radius: 50%;
        background: #000;
        color: #ffffff;
        top: -10px;
        text-align: center;
        min-height: 20px;
        min-width: 26px;
        line-height: 1.5 !important;
        @include media-breakpoint-down(xs) {
            top: -7px;
            font-size: 11px;
            min-height: 21px;
            min-width: 21px;
        }
    }

    .cart-block {
        a {
            i {
                position: relative;
            }
        }

    }

    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 850;

    //give nav it's own compsite layer
    will-change: transform;
    transform: translateZ(0);
    display: flex;
    height: 65px;
    box-shadow: 0 -2px 5px -2px #333;
    background-color: #fff;

    &__item {
        flex-grow: 1;
        text-align: center;
        font-size: 12px;

        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    &__item--active {
        color: #000;
    }

    &__item-content {
        display: flex;
        flex-direction: column;

        a {
            color: #000;
            display: flex;
            flex-direction: column;
            font-weight: bold;

            &:hover {
                text-decoration: none;
            }

            i {
                color: red;
            }
        }
    }
}
</style>
