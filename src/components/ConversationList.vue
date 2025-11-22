<template>
  <div class="conversation-list-container">
    <div class="conversation-list-header">
      <h2>Messages</h2>
      <button @click="onCreateNewConversation" class="btn-new-conversation">
        <span>+</span>
      </button>
    </div>

    <div class="conversation-list-search">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search conversations..."
        class="search-input"
      >
    </div>

    <div class="conversation-list">
      <div
        v-if="loading"
        class="loading-message"
      >
        Loading...
      </div>

      <div
        v-else-if="filteredConversations.length === 0"
        class="empty-message">No conversation
      </div>

      <div
        v-for="conversation in filteredConversations"
        :key="conversation.id"
        class="conversation-item"
        :class="{ active: conversation.id === selectedConversationId }"
        @click="selectConversation(conversation.id)"
      >
        <!-- <div class="conversation-avatar">
          <span>{{ getInitials(conversation.other_user_name) }}</span>
        </div> -->

        <div class="conversation-info">
          <h3 class="conversation-name">{{ conversation.other_user_name }}</h3>
          <p class="conversation-preview">{{ conversation.last_message.content }}</p>
        </div>

        <div class="conversation-meta">
          <span class="conversation-time">{{ formatTime(conversation.updated_at) }}</span>
          <button
            @click.stop="deleteConversation(conversation.id)"
            class="btn-delete"
            title="Delete conversation"
          >
            ×
          </button>
        </div>
      </div>
    </div>

    <!-- Modal create new conversation -->
    <div v-if="showCreateModal" class="modal-overlay" @click="showCreateModal = false">
      <div class="modal-content" @click.stop>
        <h3>Start new conversation</h3>
        <input
          v-model="newConversationUserId"
          type="number"
          placeholder="Enter ID user"
          class="modal-input"
        >
        <div class="modal-actions">
          <button @click="createNewConversation" class="btn-create">Create</button>
          <button @click="showCreateModal = false" class="btn-cancel">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'ConversationList',
  props: {
    currentUserId: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      searchQuery: '',
      showCreateModal: false,
      newConversationUserId: ''
    }
  },
  computed: {
    ...mapGetters('chat', ['conversations', 'selectedConversationId', 'loading']),
    filteredConversations () {
      if (!this.searchQuery) {
        return this.conversations
      }
      return this.conversations.filter(c =>
        c.other_user_name.toLowerCase().includes(this.searchQuery.toLowerCase())
      )
    }
  },
  methods: {
    ...mapActions('chat', { fetchConversations: 'fetchConversations', selectConversation: 'selectConversation', createConversation: 'createConversation', deleteConversationAction: 'deleteConversation' }),
    // ...mapActions('chat', ['fetchConversations', 'selectConversation', 'createConversation', 'deleteConversation']),
    selectConversation (conversationId) {
      this.$store.dispatch('chat/selectConversation', conversationId)
      this.$emit('conversation-selected', conversationId)
    },
    onCreateNewConversation () {
      this.showCreateModal = true
      this.$emit('create-new-conversation')
    },
    async createNewConversation () {
      if (!this.newConversationUserId) {
        alert('Pls enter ID user')
        return
      }
      try {
        const conversation = await this.createConversation(parseInt(this.newConversationUserId))
        this.selectConversation(conversation.id)
        this.showCreateModal = false
        this.newConversationUserId = ''
      } catch (error) {
        console.error('Failed to create conversation:', error)
      }
    },
    async deleteConversation (conversationId) {
      if (confirm('Want to del?')) {
        try {
          await this.deleteConversation(conversationId)
        } catch (error) {
          console.error('Failed to delete conversation:', error)
        }
      }
    },
    getInitials (name) {
      if (!name) return '?'
      return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
    },
    formatTime (dateString) {
      const date = new Date(dateString)
      const today = new Date()
      const yesterday = new Date(today)
      yesterday.setDate(yesterday.getDate() - 1)

      if (date.toDateString() === today.toDateString()) {
        return date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
      } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Hôm qua'
      } else {
        return date.toLocaleDateString('vi-VN')
      }
    }
  },
  async mounted () {
    await this.fetchConversations()
  }
}
</script>

<style scoped>
.conversation-list-container {
  display: flex;
  flex-direction: column;
  height: 100%;
  background-color: #fff;
  border-right: 1px solid #e0e0e0;
}

.conversation-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e0e0e0;
}

.conversation-list-header h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #2c3e50;
}

.btn-new-conversation {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #42b983;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.btn-new-conversation:hover {
  background-color: #369970;
}

.conversation-list-search {
  padding: 0.5rem 1rem;
}

.search-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  font-size: 0.9rem;
}

.search-input:focus {
  outline: none;
  border-color: #42b983;
}

.conversation-list {
  flex: 1;
  overflow-y: auto;
}

.loading-message,
.empty-message {
  padding: 2rem 1rem;
  text-align: center;
  color: #7f8c8d;
  font-size: 0.9rem;
}

.conversation-item {
  display: flex;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
  transition: background-color 0.2s ease;
  gap: 0.75rem;
}

.conversation-item:hover {
  background-color: #f9f9f9;
}

.conversation-item.active {
  background-color: #e8f5e9;
  border-left: 4px solid #42b983;
}

.conversation-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: #42b983;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  flex-shrink: 0;
}

.conversation-info {
  flex: 1;
  min-width: 0;
}

.conversation-name {
  margin: 0;
  font-size: 1rem;
  color: #2c3e50;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.conversation-preview {
  margin: 0.25rem 0 0 0;
  font-size: 0.85rem;
  color: #7f8c8d;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.conversation-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.5rem;
  flex-shrink: 0;
}

.conversation-time {
  font-size: 0.8rem;
  color: #95a5a6;
  white-space: nowrap;
}

.btn-delete {
  background: none;
  border: none;
  color: #e74c3c;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.conversation-item:hover .btn-delete {
  opacity: 1;
}

.btn-delete:hover {
  color: #c0392b;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  padding: 2rem;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.modal-content h3 {
  margin: 0 0 1rem 0;
  color: #2c3e50;
}

.modal-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  font-size: 1rem;
  margin-bottom: 1rem;
}

.modal-input:focus {
  outline: none;
  border-color: #42b983;
}

.modal-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
}

.btn-create,
.btn-cancel {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.btn-create {
  background-color: #42b983;
  color: white;
}

.btn-create:hover {
  background-color: #369970;
}

.btn-cancel {
  background-color: #ecf0f1;
  color: #2c3e50;
}

.btn-cancel:hover {
  background-color: #bdc3c7;
}
</style>
