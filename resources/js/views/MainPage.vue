<template>
    <main-menu
        :categories="parentsCategories"
        :parent-title="parentTitle"
    >
    </main-menu>
</template>

<script>
import {mapGetters} from 'vuex';
import MainMenu from '../components/MainMenu.vue';
import ItemsList from '../components/ItemsList.vue';

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
