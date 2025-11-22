import api from './api'

export const chatService = {
  getConversations () {
    return api.get('/conversations')
  },

  getMessages (conversationId) {
    return api.get(`/conversations/${conversationId}/messages`)
  },

  createConversation (otherUserId) {
    return api.post('/conversations', {
      type: 'direct',
      other_user_id: otherUserId
    })
  },

  sendMessage (conversationId, message) {
    return api.post(`/messages`, {
      content: message,
      conversation_id: conversationId
    })
  }

  // deleteConversation (conversationId) {
  //   return api.delete(`/conversations/${conversationId}`)
  // },

  // searchConversations (query) {
  //   return api.get('/conversations', {
  //     params: { search: query }
  //   })
  // }
}
