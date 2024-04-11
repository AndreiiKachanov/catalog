import  { makeRequest } from "../api/server";

export default {
    namespaced: true,
    state: {
        settings: []
    },
    getters: {
        allSettings: function (state) {
            return state.settings
        },
        minOrderAmount: function (state) {
            let obj = state.settings.find(item => item.prop_key === 'min_order_cost');
            return obj.prop_value !== null ? parseInt(obj.prop_value) : null;
            // return parseInt(obj.prop_value ?? null);
        },
    },
    mutations: {
        setSettings(state, settings) {
            state.settings = settings;
        },
    },
    actions: {
        async loadSettings(store) {
           let settings = await makeRequest('/api/get-settings');
            store.commit('setSettings', settings.data);
        }
    }
}
