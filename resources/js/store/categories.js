import  { makeRequest } from "../api/server";

export default {
    namespaced: true,
    state: {
        categories: [],
        cacheUrls: []
    },
    getters: {
        allCategories: function (state) {
            return state.categories
        },
        cacheUrls: state => state.cacheUrls,
        // parentsCategories: function (state) {
        //     return state.categories.filter(cat => cat.parent_id === null)
        // },
        parentsCategories: state => state.categories.filter(cat => cat.parent_id === null),
        // subCategories: function (state) {
        //     return function (parentCategory) {
        //         return state.categories.filter(cat => cat.parent_id === parentCategory)
        //     }
        // },
        subCategories: state => (idCurrentCategory) => state.categories.filter(cat => cat.parent_id === idCurrentCategory)


    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
        },
        setCacheUrls(state, slug) {
            state.cacheUrls.push(slug);
        }
    },
    actions: {
        async loadCategories(store) {
            let categories = await makeRequest('/api/get-categories');
            store.commit('setCategories', categories.data);
        },
        setCacheUrls(store, slug) {
            store.commit('setCacheUrls', slug);
        }
    }
}
