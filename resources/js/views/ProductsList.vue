<template>
    <div>
        <div class="col col-sm-2 menu" style="border: 1px solid red;">
            <p>Router Links Parent Categories Menu</p>
<!--            {{ allCategories }}-->
            <ul class="list-group">
                <router-link
                    v-for="category in parentsCategories"
                    :key="category.route"
                    :to="`/categories/${category.slug}`"
                    v-slot="{ route, isExactActive, navigate }"
                    :custom="true">
                    <li class="list-group-item" :class="isExactActive ? 'active' : ''">
                        <a :href="route.fullPath" @click="navigate">{{ category.title }}</a>
                    </li>
                </router-link>
            </ul>
        </div>

<!--        {{ cartAll }}-->
        <br>
<!--        {{ productsTemp }}-->
        <h1>Products</h1>
        <div>
            Route Name <strong>{{ this.$route.name }}</strong>
        </div>
        <products-list-new :products="productsNew"></products-list-new>

<!--        <div class="row">-->
<!--            <div class="col col-sm-4 mb-3 mt-3"-->
<!--                v-for="product in products"-->
<!--                 :key="product.id"-->
<!--            >-->
<!--                <div class="card">-->
<!--                    <div class="card-body">-->
<!--                        <h3>{{ product.title }}</h3>-->
<!--                        <img class="img-fluid" :src="product.img" alt=""/>-->
<!--                        <div>{{ product.price }}</div>-->
<!--                        <router-link :to="`/${product.slug}`">Read more</router-link>-->
<!--                        <count-items :count-from-cart="getCountFromCart(product.id)"  @set-cnt="setNewCnt(product.id, $event)"></count-items>-->
<!--                        <button v-if="inCart(product.id)" class="btn btn-danger" @click="removeFromCart(product.id)">-->
<!--                            Remove-->
<!--                        </button>-->
<!--                        <button v-else class="btn btn-success" @click="addToCartNew(product.id)">-->
<!--                            Add to cart-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </div>-->

<!--            </div>-->
<!--            <button @click="showMore">Показать еще</button>-->
<!--        </div>-->

<!--        <count-items :count-from-cart="getCountFromCart(product.id)"  @set-cnt="setNewCnt(product.id, $event)"></count-items>-->

        {{ products }}
    </div>
</template>

<script>

import { mapGetters, mapActions } from 'vuex';
// import CountItems from '../components/CountItems';
import ProductsListNew from "../components/ProductsList.vue";

export default {
    name: "ProductsList",
    data: () => ({
        // parentCategories: [
        //     { route: 'parent-category', title: '1.Популярные товары', slug: '1-populyarnye-tovary' },
        //     { route: 'parent-category', title: '2.Мягкие игрушки', slug: '2-myagkie-igrushki' }
        // ]
    }),
    components: {
        // CountItems,
        ProductsListNew
    },
    created() {
    },
    computed: {
        // ...mapGetters({ products: 'products/all' })
        // или 2 вариант записи
        ...mapGetters('products', { products: 'all' }),
        ...mapGetters('categories', { allCategories: 'allCategories', parentsCategories: 'parentsCategories' }),
        productsNew() {
            let products;
            if (this.$route.name === 'products') {
                products = this.products;
            }
            if (this.$route.name === 'category') {
                let parentSlug = this.$route.params.slug;
                // console.log('parentSlug=', parentSlug);
                products = this.getProductsFromParentCategoryAndSubcategories(parentSlug);
            }
            return products;
        }
        // ...mapGetters('cart', { cartAll: 'all', inCart: 'has', productsTemp: 'productsTemp', getCountFromCart: 'getCountFromCart' }),
    },
    methods: {
        // ...mapActions('cart', { addToCartNew: 'addNew', addToCart: 'add', removeFromCart: 'remove', setCnt: 'setCnt', setTempCnt: 'setTempCnt' }),
        ...mapActions('products', { showMoreAct: 'showMoreAct', getProductsFromParentCategoryAndSubcategories: 'getProductsFromParentCategoryAndSubcategories' }),
        // setNewCnt(id, cnt) {
        //     if (this.inCart(id)) {
        //         console.log('В корзине есть такой товар');
        //         console.log('id =' + id, 'cnt=' + cnt);
        //         if (cnt > 0) {
        //             this.setCnt({id, cnt})
        //         }
        //     }
        //     else {
        //         console.log('В корзине нет такого товара');
        //         console.log('cnt = ' + cnt);
        //         if (cnt > 0) {
        //             console.log(1);
        //             this.setTempCnt({id, cnt})
        //         }
        //     }
        // },
        // showMore() {
        //     this.showMoreAct(123);
        // }

    }
}
</script>

<style scoped>
    .row {
        padding-left: 15px;
    }
</style>
