import axios from "axios";

const state = {
  userNow: { id: "", name: "Customer", photo: process.env.VUE_APP_IMGPATH + "profile.svg", logged: false, login: true },
  alert: { status: false, msg: "" }
};

const getters = {
  userNow: (state) => state.userNow,
};

const actions = {
  
  setUserNow({commit}, payload){
    commit('setUserNow', payload)
  }
  
};

const mutations = {
  setUserNow: (state, userNow) => (state.userNow = userNow),
  setAlertLogin: (state, alertLogin) => (state.alert = alertLogin)
};

export default {
  state,
  getters,
  actions,
  mutations,
};
