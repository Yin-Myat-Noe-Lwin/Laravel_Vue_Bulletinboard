import { createStore } from 'vuex';
import VuexPersist from 'vuex-persist';

const vuexLocalStorage = new VuexPersist({
  key: 'APPDATA',
  storage: window.localStorage,
  reducer: (state) => ({
    userData: state.userData,
    postData: state.postData,
    postEditData: state.postEditData
  })
});

const store = createStore({
  state: {
    userData: {},
    postData: {},
    postEditData: {}
  },
  mutations: {
    setUserData(state, userData) {
      state.userData = userData;
    },
    setPostData(state, postData) {
      state.postData = postData;
    },
    setPostEditData(state, postEditData) {
      state.postEditData = postEditData;
    },
  },
  actions: {
    updateUserData({ commit }, userData) {
      commit('setUserData', userData);
    },
    updatePostData({ commit }, postData) {
      commit('setPostData', postData);
    },
    updatePostEditData({ commit }, postEditData) {
      commit('setPostEditData', postEditData);
    },
  },
  getters: {
    getUserData(state) {
      return state.userData;
    },
    getPostData(state) {
      return state.postData;
    },
    getPostEditData(state) {
      return state.postEditData;
    },
  },
  plugins: [vuexLocalStorage.plugin],
});

export default store;
