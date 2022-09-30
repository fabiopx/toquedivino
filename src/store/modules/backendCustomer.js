import axios from "axios";

const state = {
  userNow: { id: "", name: "Customer", logged: false, login: true },
  alert: { status: false, msg: "" },
  inscribeID: null,
  startedRepertory: false,
  budget: {},
  access: {first: true, isInscribe: false, isAgreement: false, isBudget: false, isEvent: false},
};

const getters = {
  userNow: (state) => state.userNow,
  inscribeID: (state) => state.inscribeID,
  startedRepertory: (state) => state.startedRepertory,
  budget: (state) => state.budget,
  access: (state) => state.access
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

  setBudget({commit}, payload){
    commit('setBudget', payload);
  },

  setIsBudget({commit}, payload){
    commit('setIsBudget', payload);
  },

  setAccess({commit}, payload){
    commit('setAccess', payload);
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
    const response = await axios.get(process.env.VUE_APP_URL + "/events/verify/" + state.inscribeID);
    const resp = (response.data) ? true : false
    commit('setIsEvent', resp)
  },

  async verifyStatusInscribe({commit}){
    const response  = await axios.get(process.env.VUE_APP_URL + "/inscribes/verifyStatus/" + state.inscribeID);
    const resp = (response.data == "0") ? true : false;
    console.log(resp)
    commit('setFirstAccess', resp);
  }
  
};

const mutations = {
  setUserNow: (state, userNow) => (state.userNow = userNow),
  setAlertLogin: (state, alertLogin) => (state.alert = alertLogin),
  setInscribeID: (state, inscribeID) => (state.inscribeID = inscribeID),
  setStartedRepertory: (state, startedRepertory) => (state.startedRepertory = startedRepertory),
  setIsAgreement: (state, isAgreement) => (state.access.isAgreement = isAgreement),
  setIsBudget: (state, isBudget) => (state.access.isBudget = isBudget),
  setBudget: (state, budget) => (state.budget = budget),
  setIsEvent: (state, isEvent) => (state.access.isEvent = isEvent),
  setFirstAccess: (state, firstAccess) => (state.access.first = firstAccess)
};

export default {
  state,
  getters,
  actions,
  mutations,
};
