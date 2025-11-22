import Vue from 'vue'
import Vuex from 'vuex'
import { authService } from '../services/auth'
import chat from './modules/chat'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    token: localStorage.getItem('token') || null,
    isAuthenticated: !!localStorage.getItem('token')
  },

  mutations: {
    SET_USER (state, user) {
      state.user = user
    },

    SET_TOKEN (state, token) {
      state.token = token
      state.isAuthenticated = !!token
      if (token) {
        localStorage.setItem('token', token)
      }
    },

    LOGOUT (state) {
      state.user = null
      state.token = null
      state.isAuthenticated = false
      localStorage.removeItem('token')
    }
  },

  actions: {
    async register ({ commit }, payload) {
      const response = await authService.register(payload)
      return response.data
    },

    async login ({ commit }, { email, password }) {
      const response = await authService.login(email, password)
      const token = response.data.token || response.data.access_token
      commit('SET_TOKEN', token)
      return response.data
    },

    async fetchProfile ({ commit }) {
      const response = await authService.getProfile()
      commit('SET_USER', response.data)
      return response.data
    },

    async logout ({ commit }) {
      try {
        await authService.logout()
      } finally {
        commit('LOGOUT')
      }
    }
  },

  getters: {
    isAuthenticated: state => state.isAuthenticated,
    currentUser: state => state.user
  },
  modules: {
    chat: chat
  }
})
