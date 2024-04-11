<template>
        <div>
            <div>
                Parent Categories Product List <strong>{{ this.$route.params.slug }}</strong>
            </div>
            <div>
                Route Name <strong>{{ this.$route.name }}</strong>
            </div>
        </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CountItems from '../components/CountItems';

export default {
    name: "ParentCategoryProductsList",
    data: () => ({
        test: 1,
    }),
    components: {
        CountItems
    },
    computed: {
        // ...mapGetters({ products: 'products/all' })
        // или 2 вариант записи
        ...mapGetters('products', { products: 'all' }),
        ...mapGetters('cart', { cartAll: 'all', inCart: 'has', productsTemp: 'productsTemp', getCountFromCart: 'getCountFromCart' }),
    },
    created() {
        console.log(this.$route);
    },
    methods: {
        ...mapActions('cart', { addToCartNew: 'addNew', addToCart: 'add', removeFromCart: 'remove', setCnt: 'setCnt', setTempCnt: 'setTempCnt' }),
        ...mapActions('products', { showMoreAct: 'showMoreAct' }),
        setNewCnt(id, cnt) {
            // console.log(arguments)
            if (this.inCart(id)) {
                console.log('В корзине есть такой товар');
                console.log('id =' + id, 'cnt=' + cnt);
                if (cnt > 0) {
                    this.setCnt({id, cnt})
                }
            }
            else {
                console.log('В корзине нет такого товара');
                console.log('cnt = ' + cnt);
                if (cnt > 0) {
                    console.log(1);
                    this.setTempCnt({id, cnt})
                }
            }
            // записывать в localstorage
            // else {
            //     console.log('В корзине нет такого товара');
            //     // console.log('cnt = ' + cnt);
            //     if (cnt > 0) {
            //         // console.log(1);
            //         // localStorage.setItem("CART_TEMP", JSON.stringify({id, cnt}));
            //         let items = JSON.parse(localStorage.getItem("CART_TEMP") || "[]");
            //         // console.log(items)
            //         items.push({id, cnt});
            //         localStorage.setItem("CART_TEMP", JSON.stringify(items));
            //         // console.log(items)
            //         this.setTempCnt({ id, cnt })
            //     }
            // }
        },
        showMore() {
            this.showMoreAct(123);
        }
    }
}
</script>

<style scoped>
    .row {
        padding-left: 15px;
    }
</style>
