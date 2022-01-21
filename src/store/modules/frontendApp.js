import axios from "axios";

const state = {
    services: [],
    selService: '',
    instruments: [],
    formations: [],
    inscribe:{},
    tela: 1,
    isStart: false
};

const getters = {
    services:state => state.services,
    selService: state => state.selService,
    instruments: state => state.instruments,
    formations: state => state.formations,
    inscribe: state => state.inscribe,
    tela: state => state.tela,
    isStart: state => state.isStart
};

const actions = {
    async getServices({commit}){
        const response = await axios.get('http://localhost/toquedivino/api/api/getServices')

        commit('setServices', response.data)
    },

    async getInstruments({commit}){
        const response = await axios.get('http://localhost/toquedivino/api/api/getInstrument')

        commit('setInstruments', response.data)
    },

    async getFormations({commit}, payload){
        const data = new FormData()
        data.append('instruments', JSON.stringify(payload))
        const response = await axios('http://localhost/toquedivino/api/api/getFormationByInstruments', {method: 'POST', data: data})

        commit('setFormations', response.data)
    },

    setSelectedService({commit}, payload){
        commit('setSelectedService', payload)
    },

    setFormation({commit}, payload){
        commit('setFormations', payload)
    },

    setInscribe({commit}, payload){
        commit('setInscribe', payload)
    },

    start({commit}){
        commit('setStart', true)
    },

    restart({commit}){
        commit('setStart', false)
        commit('setTela', 1)
        commit('setSelectedService', null)
    },

    next({commit, state}){
        commit('setTela', state.tela + 1)
    },

    prev({commit, state}){
        commit('setTela', state.tela - 1)
    }
};

const mutations = {
    setServices: (state, services) => (state.services = services),
    setSelectedService: (state, selectedService) => (state.selService = selectedService),
    setInstruments: (state, instruments) => (state.instruments = instruments),
    setFormations: (state, formations) => (state.formations = formations),
    setInscribe: (state, inscribe) => (state.inscribe = inscribe),
    setStart: (state, isStart) => (state.isStart = isStart),
    setTela: (state, tela) => (state.tela = tela),
};

export default {
    state,
    getters,
    actions,
    mutations
};