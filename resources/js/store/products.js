import  { makeRequest } from '../api/server';

export default {
    namespaced: true,
    state: {
        // items: [{'id':1}, {'id':2}],
        items: [],
    },
    getters: {
        // карта для кеширования (отношение id и номера в массиве) - по id товара лежит номер элемента в массиве
        // нужна для быстрого поиска, например индекс в базе данных
        // такие карты актуальны для массивов больших и редко изменяющихся
        itemsMap(state) {
            let map = {};
            state.items.forEach((pr, i) => {
                map[pr.id.toString()] = i;
            });
            return map;
        },
        itemsMapSlug(state) {
            let map = {};
            state.items.forEach((pr, i) => {
                map[pr.slug.toString()] = i;
            });
            return map;
        },
        // получение 1 товара по id
        // item: state => id => state.items.find(pr => pr.id === id),
        // вариант с кешированием
        // у state.items забираем товар по номеру в массиве
        // item: (state, getters) => id => state.items[getters.itemsMap[id]],
        item(state, getters) {
            return function (id) {
                return state.items[getters.itemsMap[id]]
            }
        },
        itemSlug(state, getters) {
            return function (slug) {
                return state.items[getters.itemsMapSlug[slug]]
            }
        },
        // возвращает все items, где category = ids
        getItemsByIdsCategories: state => (ids, cnt) => state.items.filter(item => ids.includes(item.category)).splice(0, cnt),
        itemsByIdCategories: state => ids => state.items.filter(item => ids.includes(item.category)),
        newItems: state => state.items.filter(item => item.is_new === true),
        hitItems: state => state.items.filter(item => item.is_hit === true),
        all: state => state.items,
    },
    mutations: {
        setItems(state, items) {
            state.items = items;
        }
    },
    actions: {
        async load(store) {
            try {
                const items = await makeRequest('/api/items');
                store.commit('setItems', items.data);
            } catch (error) {
                console.error('Ошибка при получении товаров: ', error);
            }
        }
    }
}
