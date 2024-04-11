import  { makeRequest } from "../api/server";

export default {
    namespaced: true,
    state: {
        // items: tmpGetPr()
        // items: [{'id':1}, {'id':2}],
        items: [],
        showMoreArr: {},
        mataInfo: {}
        // itemsFromParent: []
    },
    getters: {
        // карта для кеширования (отношение айдишников и номера в массиве) - по айдишнику товара лежит номер элемента в массиве
        // нужна для быстрого поиска, например индекс в базе данных
        // такие карты актуальны для массивов больших и редко изменяющихся
        itemsMap(state) {
            // console.log(1);
            let map = {};
            state.items.forEach((pr, i) => {
                map[pr.id.toString()] = i;
                // console.log(1);
            });
            // console.log(map);
            return map;
        },
        itemsMapSlug(state) {
            // console.log(1);
            let map = {};
            state.items.forEach((pr, i) => {
                map[pr.slug.toString()] = i;
                // console.log(1);
            });
            // console.log(map);
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
                // console.log(slug, ' - ', getters.itemsMapSlug[slug]);
                return state.items[getters.itemsMapSlug[slug]]
            }
        },
        // allFromParentCategory: function (state) {
        //     return state.itemsFromParent
        // }
        // возвращает все items, где category = ids
        // getItemsByIdsCategories(state) {
        //     return function (ids) {
        //         return state.items.filter(item => ids.includes(item.category)).splice(0, 50);
        //     }
        // },
        // возвращает все items, где category = ids
        getItemsByIdsCategories: state => (ids, cnt) => state.items.filter(item => ids.includes(item.category)).splice(0, cnt),
        // getItemsByIdsCategories1: state => ids => state.items.filter(item => ids.includes(item.category)),
        itemsByIdCategories: state => ids => state.items.filter(item => ids.includes(item.category)),
        newItems: state => state.items.filter(item => item.is_new === true),
        hitItems: state => state.items.filter(item => item.is_hit === true),
        lengthItemsByIdsCategories: state => (ids) => state.items.filter(item => ids.includes(item.category)).length,

        // hasNewItemsForPagination: state => slug => state.showMoreArr[slug],
        hasNewItemsForPagination(state) {
            return function (slug) {
                // setTimeout(() => console.log('setTimeout 111 test = ', state.showMoreArr[slug]), 50);
                // console.log('>>>1', state.showMoreArr[slug]);
                return state.showMoreArr[slug];
            }
        },
        metaInfo: state => state.mataInfo,
        all: state => state.items,
        showMoreArr: state => state.showMoreArr,
    },
    mutations: {
        setItems(state, items) {
            // console.log('>>>', items);
            state.items = items;
        },
        setItemsTest(state, newItems) {
            // console.log('old items:', state.items);
            // console.log('setItemsTest:', newItems);

            // Добавляем только не дублирующиеся элементы из newItems по ключу id
            state.items.push(...newItems.filter(item => (state.items.find(pr => pr.id === item.id) == null)));
        },
        setShowMoreArrItem(state, { slug, metaInfo }) {
            let currentPage = metaInfo.current_page;
            let lastPage = metaInfo.last_page;

            if (currentPage < lastPage) {
                state.showMoreArr[slug] = currentPage;
            }
            if (currentPage === lastPage) {
                state.showMoreArr[slug] = false;
            }
        },

    },
    actions: {
        async load(store) {
           let items = await makeRequest('/api/items');
           // console.log('products arr>>>', items.data);
           // console.log('>>>', rootGetters['products/all']);
            store.commit('setItems', items.data);
        },
        async loadByIds(store, itemIds) {
            console.log(itemIds);
           let items = await makeRequest('/api/items');
           //  store.commit('setItems', items.data);
        },
        // async getProductsFromParentCategoryAndSubcategories({ commit }, slug) {
        //     let url = `/api/items/category/${slug}`;
        //     let items  = await makeRequest(url);
        //     // console.log(items.data);
        //     commit('setItemsTest', items.data);
        // },
        async getProductsFromCategory({ commit, state }, slug) {
            // console.log(2);
            let page = state.showMoreArr[slug] === undefined ? 1 : state.showMoreArr[slug] + 1
            let url = `/api/items/category/${slug}?page=${page}`;
            let items  = await makeRequest(url);
            commit('setItemsTest', items.data);
            let metaInfo =  items.meta;
            commit('setShowMoreArrItem', { slug, metaInfo });
        }
    }
}
