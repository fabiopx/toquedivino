import axios from "axios";

const state = {
  loginNow: {
    id: "",
    name: "Administrador",
    photo: process.env.VUE_APP_IMGPATH + "profile.svg",
    logged: false,
    login: true,
  },
};

const getters = {
  loginNow: (state) => state.loginNow,
};

const actions = {
  setLoginNow({ commit }, payload) {
    commit("setLoginNow", payload);
  },
};

const mutations = {
  setLoginNow: (state, loginNow) => (state.loginNow = loginNow),
};

export default {
  state,
  getters,
  actions,
  mutations,
};
