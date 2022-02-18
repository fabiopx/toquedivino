import axios from "axios";

const state = {
  userNow: { id: "", name: "Customer", photo: process.env.VUE_APP_IMGPATH + "profile.svg", logged: false, login: true },
  alert: { status: false, msg: "" },
  inscribeID: null
};

const getters = {
  userNow: (state) => state.userNow,
  inscribeID: (state) => state.inscribeID
};

const actions = {
  
  setUserNow({commit}, payload){
    commit('setUserNow', payload)
  },

  setInscribeID({commit}, payload){
    commit('setInscribeID', payload)
  }
  
};

const mutations = {
  setUserNow: (state, userNow) => (state.userNow = userNow),
  setAlertLogin: (state, alertLogin) => (state.alert = alertLogin),
  setInscribeID: (state, inscribeID) => (state.inscribeID = inscribeID)
};

export default {
  state,
  getters,
  actions,
  mutations,
};
