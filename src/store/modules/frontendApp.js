import axios from "axios";

const state = {
    services: [],
    instrumets: [],
    formations: [],
    tela: 1,
    start: false
};

const getters = {
    allServices: state => state.services,
    allInstruments: state => state.instruments,
    allFormations: state => state.formations,
    tela: state => state.tela,
    start: state => state.start
};

const actions = {
    async getServices({commit}){
        const response = await axios.get(process.env.VUE_APP_URL + 'getServices')

        commit('setServices', response.data)
    }
};

const mutations = {
    setServices: (state, services) => (state.services = services)
};

export default {
    state,
    getters,
    actions,
    mutations
};