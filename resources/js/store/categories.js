import  { makeRequest } from '../api/server';

export default {
    namespaced: true,
    state: {
        categories: []
    },
    getters: {
        allCategories: state => state.categories,
        parentsCategories: state => state.categories.filter(cat => cat.parent_id === null),
        subCategories: state => (idCurrentCategory) => state.categories.filter(cat => cat.parent_id === idCurrentCategory)
    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
        }
    },
    actions: {
        async loadCategories(store) {
            try {
                const categories = await makeRequest('/api/get-categories');
                store.commit('setCategories', categories.data);
            } catch (error) {
                console.error('Ошибка во время загрузки категорий: ', error);
            }
        }
    }
}
