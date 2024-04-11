<template>
    <main-menu
        :categories="subCategories(idCurrentCategory)"
        :parent-title="parentTitle"
    >
    </main-menu>

    <items-list
        v-if="getNewItems.length > 0"
        :title="'Новинки'"
        :type-list="'short'"
        :items="getNewItems"
    ></items-list>

    <items-list
        v-if="getHitItems.length > 0"
        :title="'Хиты'"
        :type-list="'short'"
        :items="getHitItems"
    ></items-list>

    <items-list
        v-if="getItemsByIdCategories.length > 0"
        :title="'Все товары'"
        :type-list="'full'"
        :items="getItemsByIdCategories"
    ></items-list>
</template>

<script>
    import { mapGetters } from 'vuex';
    import MainMenu from "../components/MainMenu.vue"
    import ItemsList from "../components/ItemsList.vue";

    export default {
        name: "CategoryPage",
        data: () => ({
        }),
        components: {
            ItemsList,
            MainMenu,
        },
        computed: {
            ...mapGetters('products', {
                getItemsByIdsCategories: 'getItemsByIdsCategories',
                itemsByIdCategories: 'itemsByIdCategories',
            }),
            ...mapGetters('categories', {
                allCategories: 'allCategories',
                subCategories: 'subCategories',
            }),
            // айдишники текущей категории + дочерних категорий
            getCategoriesId() {
                // ид категории, в которую зашли
                let idCat = this.idCurrentCategory;
                let arrIdsCat = [idCat];
                // ids дочерних категорий
                if (this.hasSubCategories) {
                    let idsChilds = this.allCategories.map((element) => {
                        if (element.parent_id === this.idCurrentCategory) {
                            return element.id;
                        }
                    }).filter(element => element >= 0);
                    // объединяем айдишники в массив
                    arrIdsCat.push(...idsChilds);
                }
                return arrIdsCat;
            },
            getItemsByIdCategories() {
                return this.itemsByIdCategories(this.getCategoriesId);
            },
            getNewItems() {
                return this.getItemsByIdCategories.filter(item => item.is_new === true);
            },
            getHitItems() {
                return this.getItemsByIdCategories.filter(item => item.is_hit === true);
            },
            // itemsOnlyCategory() {
            //     // console.log(111);
            //     // ид категории, в которую зашли
            //     let idCat = this.idCurrentCategory;
            //     // console.log('idCat = ', idCat);
            //     let arr = [idCat];
            //     // console.log(this.itemsFromCategories(arr));
            //     return this.itemsFromCategories(arr);
            // },
            idCurrentCategory() {
                // try {
                //     setTimeout(() => console.log('setTimeout test'), 1000);
                //     console.log('assCategories', this.allCategories);
                //     if (this.allCategories.length > 0) {
                        return this.allCategories[this.allCategories.findIndex(pr => pr.slug === this.slug)].id;
                    // }

                // } catch(err) {
                //     console.log(err);
                // }
            },
            slug() {
                return this.$route.params.slug;
            },
            hasSubCategories() {
                // хотя бы 1 элемент с таким parent_id нашелся
                return this.allCategories.some(cat => cat.parent_id === this.idCurrentCategory);
            },
            parentTitle() {
                return this.allCategories[this.allCategories.findIndex(pr => pr.slug === this.slug)].title;
            }
        }
    }
</script>

<style lang="scss">
</style>
