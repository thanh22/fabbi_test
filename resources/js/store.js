import { createStore } from 'vuex';
import createPersistedState from "vuex-persistedstate";
import axios from 'axios';
import SecureLS from "secure-ls";
const ls = new SecureLS({ isCompression: false });

const store = createStore({
  namespaced:true,
  state() {
    return {
      step1: {},
      step2: {},
      step3: [],
      listMeals: [],
      listRestaurants: [],
      listDishes: [],
    }
  },
  mutations: {
    SET_STEP_ONE (state, payload) {
      state.step1 = payload;
    },
    SET_STEP_TWO (state, payload) {
      state.step2 = payload;
    },
    SET_STEP_THREE (state, payload) {
      state.step3 = payload;
    },
    SET_LIST_MEALS (state, payload) {
      state.listMeals = payload;
    },
    SET_LIST_RESTAURANT (state, payload) {
      state.listRestaurants = payload;
    },
    SET_DISHES (state, payload) {
      state.listDishes = payload;
    },
    RESET_STATE(state) {
      state.step1 = {};
      state.step2 = {};
      state.step3 = [];
      state.listMeals = [];
      state.listRestaurants = [];
      state.listDishes = [];
    }
  },
  getters: {
    stepOne: state => state.step1,
    stepTwo: state => state.step2,
    stepThree: state => state.step3,
    listMeals: state => state.listMeals,
    listRestaurants: state => state.listRestaurants,
    listDishes: state => state.listDishes,
  },
  actions: {
    addStepOne({commit}, payload) {
      commit('SET_STEP_ONE', payload);
    },
    addStepTwo({commit}, payload) {
      commit('SET_STEP_TWO', payload);
    },
    addStepThree({commit}, payload) {
      commit('SET_STEP_THREE', payload);
    },
    resetState({commit}) {
      commit('RESET_STATE')
    },
    getListMeals({commit}, payload) {
      axios.get('api/meal')
      .then((res) => {
        commit('SET_LIST_MEALS', res.data.data);
      })
      .catch((err) => {
      })
    },
    getListRestaurants({commit}, meal) {
      axios.get(`api/restaurant/${meal}`)
      .then((res) => {
        commit('SET_LIST_RESTAURANT', res.data.data);
      })
      .catch((err) => {
      })
    },
    getListDishes({commit}, payload) {
      console.log('st',payload);
      axios.post('api/dishes', payload.params)
      .then((res) => {
        commit('SET_DISHES', res.data.data);
      })
      .catch((err) => {
      })
    },
    create({}, payload) {
      axios.post('api/create', payload.params)
      .then((res) => {
        payload.successCallback(res.data.data);
      })
      .catch((err) => {
        payload.errCallback(err)
      })
    }
  },
  plugins: [
    createPersistedState({
      storage: {
        getItem: key => ls.get(key),
        setItem: (key, value) => ls.set(key, value),
        removeItem: key => ls.remove(key)
      }
    })
  ],
})

export default store
