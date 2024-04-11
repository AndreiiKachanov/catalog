<template>
    <main-menu
        :categories="parentsCategories"
        :parent-title="parentTitle"
    >
    </main-menu>

<!--    <items-list-->
<!--        v-if="getNewItems.length > 0"-->
<!--        :title="'Новинки'"-->
<!--        :type-list="'short'"-->
<!--        :items="getNewItems"-->
<!--    ></items-list>-->

<!--    <items-list-->
<!--        v-if="getHitItems.length > 0"-->
<!--        :title="'Хиты'"-->
<!--        :type-list="'short'"-->
<!--        :items="getHitItems"-->
<!--    ></items-list>-->
</template>

<script>
    import { mapGetters } from 'vuex';
    import MainMenu from "../components/MainMenu.vue";
    import ItemsList from "../components/ItemsList.vue";

    export default {
        name: "MainPage",
        data: () => ({
            parentTitle: ''
        }),
        components: {
            MainMenu,
            ItemsList
        },
        computed: {
            ...mapGetters('categories', {
                parentsCategories: 'parentsCategories'
            }),
            ...mapGetters('products', {
                newItems: 'newItems',
                hitItems: 'hitItems'
            }),
            // getParentsCategories() {
            //     return this.parentsCategories.sort((a, b) => a.sorting > b.sorting ? 1 : -1);
            // },
            getNewItems() {
                return this.newItems;
            },
            getHitItems() {
                return this.hitItems;
            }
        }
    }
</script>

<style lang="scss">
</style>
