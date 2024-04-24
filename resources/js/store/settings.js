import {makeRequest} from '../api/server';

export default {
    namespaced: true,
    state: {
        settings: []
    },
    getters: {
        allSettings: state => state.settings,
        minOrderAmount: function (state) {
            const obj = state.settings.find(item => item.prop_key === 'min_order_cost');
            return obj.prop_value !== null ? parseInt(obj.prop_value) : null;
        },
    },
    mutations: {
        setSettings(state, settings) {
            state.settings = settings;
        },
    },
    actions: {
        async loadSettings(store) {
            try {
                const settings = await makeRequest('/api/get-settings');
                store.commit('setSettings', settings.data);
            } catch (error) {
                console.error('Ошибка при получении настроек сайта: ', error);
            }
        }
    }
}
