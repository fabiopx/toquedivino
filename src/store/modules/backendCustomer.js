import axios from "axios";

const state = {
  userNow: { id: "", name: "Customer", photo: process.env.VUE_APP_IMGPATH + "profile.svg", logged: false, login: true },
  alert: { status: false, msg: "" },
  inscribeID: null,
  startedRepertory: false,
  isAgreement: false
};

const getters = {
  userNow: (state) => state.userNow,
  inscribeID: (state) => state.inscribeID,
  startedRepertory: (state) => state.startedRepertory,
  isAgreement: (state) => state.isAgreement
};

const actions = {
  
  setUserNow({commit}, payload){
    commit('setUserNow', payload)
  },

  setInscribeID({commit}, payload){
    commit('setInscribeID', payload)
  },

  setStartedRepertory({commit}, payload){
    commit('setStartedRepertory', payload)
  },

  setIsAgreement({commit}, payload){
    commit('setIsAgreement', payload)
  }
  
};

const mutations = {
  setUserNow: (state, userNow) => (state.userNow = userNow),
  setAlertLogin: (state, alertLogin) => (state.alert = alertLogin),
  setInscribeID: (state, inscribeID) => (state.inscribeID = inscribeID),
  setStartedRepertory: (state, startedRepertory) => (state.startedRepertory = startedRepertory),
  setIsAgreement: (state, isAgreement) => (state.isAgreement = isAgreement)
};

export default {
  state,
  getters,
  actions,
  mutations,
};
