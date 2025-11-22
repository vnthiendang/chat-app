import { chatService } from '../../services/chat'

const state = {
  conversations: [],
  messages: {},
  selectedConversationId: null,
  loading: false,
  error: null
}

const mutations = {
  SET_CONVERSATIONS (state, conversations) {
    state.conversations = conversations
  },

  SET_MESSAGES (state, { conversationId, messages }) {
    state.messages[conversationId] = messages
  },

  ADD_MESSAGE (state, { conversationId, message }) {
    if (!state.messages[conversationId]) {
      state.messages[conversationId] = []
    }
    state.messages[conversationId].push(message)
  },

  SET_SELECTED_CONVERSATION (state, conversationId) {
    state.selectedConversationId = conversationId
  },

  ADD_CONVERSATION (state, conversation) {
    state.conversations.unshift(conversation)
  },

  REMOVE_CONVERSATION (state, conversationId) {
    state.conversations = state.conversations.filter(c => c.id !== conversationId)
  },

  SET_LOADING (state, loading) {
    state.loading = loading
  },

  SET_ERROR (state, error) {
    state.error = error
  }
}

const actions = {
  async fetchConversations ({ commit }) {
    commit('SET_LOADING', true)
    try {
      const res = await chatService.getConversations()
      const payload = res.data && (res.data.data || res.data)
      commit('SET_CONVERSATIONS', payload || [])
      commit('SET_ERROR', null)
    } catch (error) {
      commit('SET_ERROR', error.response.data.message || 'Failed to fetch conversations')
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async fetchMessages ({ commit }, conversationId) {
    commit('SET_LOADING', true)
    try {
      const res = await chatService.getMessages(conversationId)
      const payload = res.data && (res.data.data || res.data)
      console.log(res.data)
      commit('SET_MESSAGES', { conversationId: Number(conversationId), messages: payload || [] })
      commit('SET_ERROR', null)
    } catch (error) {
      commit('SET_ERROR', error.response.data.message || 'Failed to fetch messages')
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async createConversation ({ commit }, otherUserId) {
    commit('SET_LOADING', true)
    try {
      const res = await chatService.createConversation(otherUserId)
      const payload = res.data && (res.data.data || res.data)
      if (payload) commit('ADD_CONVERSATION', payload)
      commit('SET_SELECTED_CONVERSATION', payload.id || null)
      return payload
    } catch (error) {
      commit('SET_ERROR', error.response.data.message || 'Failed to create conversation')
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async sendMessage ({ commit }, { conversationId, message }) {
    try {
      const res = await chatService.sendMessage(conversationId, message)
      const payload = res.data && (res.data.data || res.data)
      // payload may be single message object
      if (payload) {
        commit('ADD_MESSAGE', { conversationId: Number(conversationId), message: payload })
      }
      return payload
    } catch (error) {
      commit('SET_ERROR', error.response.data.message || 'Failed to send message')
      throw error
    }
  },

  async deleteConversation ({ commit }, conversationId) {
    commit('SET_LOADING', true)
    try {
      await chatService.deleteConversation(conversationId)
      commit('REMOVE_CONVERSATION', conversationId)
      commit('SET_ERROR', null)
    } catch (error) {
      commit('SET_ERROR', error.response.data.message || 'Failed to delete conversation')
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },

  selectConversation ({ commit }, conversationId) {
    commit('SET_SELECTED_CONVERSATION', conversationId)
  }
}

const getters = {
  conversations: state => state.conversations,
  messages: state => conversationId => state.messages[conversationId] || [],
  selectedConversationId: state => state.selectedConversationId,
  selectedConversation: state => state.conversations.find(c => c.id === state.selectedConversationId) || null,
  loading: state => state.loading,
  error: state => state.error
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
