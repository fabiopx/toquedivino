import axios from "axios";

const state = {
  userNow: { id: "", name: "", photo: "", logged: false, login: true },
};

const getters = {
  userNow: (state) => state.userNow,
};

const actions = {
  setUserNow({ commit }, payload) {
    commit("setUserNow", payload);
  },

  async loginCustomer({ commit }, payload) {
    let data = new FormData();
    data.append("email", payload.email);
    data.append("password", payload.password);
    const response = await axios(process.env.VUE_APP_URL + "loginCustomer", {
      method: "POST",
      data: data,
    });

    commit('setUserNow', response.data.userNow)
  },
};

const mutations = {
  setUserNow: (state, userNow) => (state.userNow = userNow),
};

export default {
  state,
  getters,
  actions,
  mutations,
};
