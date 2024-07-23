// store/index.js
import { createStore } from 'vuex';

export default createStore({
  state: {
    refreshApp: false,
  },
  mutations: {
    SET_REFRESH_APP(state, value) {
      state.refreshApp = value;
    },
  },
  actions: {
    refreshApp({ commit }) {
      commit('SET_REFRESH_APP', true);
    },
  },
});
