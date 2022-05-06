import axios from "axios";

const state = {
  userNow: { id: "", name: "Customer", photo: process.env.VUE_APP_IMGPATH + "profile.svg", logged: false, login: true },
  alert: { status: false, msg: "" },
  inscribeID: null,
  startedRepertory: false,
  isAgreement: false,
  isBudget: false,
  budget: {},
  isEvent: false,
};

const getters = {
  userNow: (state) => state.userNow,
  inscribeID: (state) => state.inscribeID,
  startedRepertory: (state) => state.startedRepertory,
  isAgreement: (state) => state.isAgreement,
  isBudget: (state) => state.isBudget,
  budget: (state) => state.budget,
  isEvent: (state) => state.isEvent
};

const actions = {
  
  setUserNow({commit}, payload){
    commit('setUserNow', payload);
  },

  setInscribeID({commit}, payload){
    commit('setInscribeID', payload);
  },

  setStartedRepertory({commit}, payload){
    commit('setStartedRepertory', payload);
  },

  setIsAgreement({commit}, payload){
    commit('setIsAgreement', payload);
  },

  setIsBudget({commit}, payload){
    commit('setIsBudget', payload);
  },

  setBudget({commit}, payload){
    commit('setBudget', payload);
  },

  async setBudgetActive({commit}){
    const response = await axios.get(process.env.VUE_APP_URL + "/budgets/get/" + state.inscribeID);
    const budgets = response.data;
    budgets.forEach(function(budget){
      if(budget.status == 1){
        commit('setBudget', budget);
      }
    })
  },

  async verifyIsAgreement({commit}){
    const response = await axios.get(process.env.VUE_APP_URL + "/agreements/getCustomer/" + state.inscribeID);
    const isAgreement = (response.data) ? true: false;
    commit('setIsAgreement', isAgreement);
  },

  async setIsEvent({commit}){
    const response = await axios.get(process.env.VUE_APP_URL + "/events/getCustomers/" + state.inscribeID)
    const resp = (response.data) ? true : false
    commit('setIsEvent', resp)
  },
  
};

const mutations = {
  setUserNow: (state, userNow) => (state.userNow = userNow),
  setAlertLogin: (state, alertLogin) => (state.alert = alertLogin),
  setInscribeID: (state, inscribeID) => (state.inscribeID = inscribeID),
  setStartedRepertory: (state, startedRepertory) => (state.startedRepertory = startedRepertory),
  setIsAgreement: (state, isAgreement) => (state.isAgreement = isAgreement),
  setIsBudget: (state, isBudget) => (state.isBudget = isBudget),
  setBudget: (state, budget) => (state.budget = budget),
  setIsEvent: (state, isEvent) => (state.isEvent = isEvent)
};

export default {
  state,
  getters,
  actions,
  mutations,
};
