<template>
  <div class="chatbox-container">
    <div class="chatbox-header">
      <div class="chatbox-header-info" v-if="selectedConversation">
        <h3>{{ selectedConversation.other_user_name  || 'Conversation'}}</h3>
        <p class="status">Online</p>
      </div>
    </div>

    <div class="chatbox-messages">
      <div
        v-if="loading"
        class="loading-message"
      >
        Loading messages...
      </div>

      <div
        v-else-if="messages.length === 0"
        class="empty-message"
      >
        Did not have any messages. Start conversation!
      </div>

      <ChatMessage
        v-for="message in messages"
        :key="message.id"
        :message="message"
        :isCurrentUser="message.sender_id === currentUserId"
      />

      <div ref="messagesEnd"></div>
    </div>

    <div class="chatbox-input-area">
      <form @submit.prevent="sendMessage">
        <input
          v-model="newMessage"
          type="text"
          placeholder="Enter messages..."
          class="message-input"
          :disabled="sending"
        >
        <button
          type="submit"
          class="btn-send"
          :disabled="!newMessage.trim() || sending"
        >
          {{ sending ? '...' : 'Sent' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import ChatMessage from './ChatMessage.vue'

export default {
  name: 'ChatBox',
  components: {
    ChatMessage
  },
  props: {
    conversationId: {
      type: Number,
      required: true
    },
    currentUserId: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      newMessage: '',
      sending: false
    }
  },
  computed: {
    ...mapGetters('chat', ['selectedConversation', 'loading']),
    messages () {
      // return this.$store.getters['chat/messages'](this.conversationId)
      if (!this.conversationId) return []
      const getter = this.$store.getters['chat/messages']
      return typeof getter === 'function' ? getter(Number(this.conversationId)) : []
    }
  },
  methods: {
    // ...mapActions('chat', ['fetchMessages', 'sendMessage']),
    ...mapActions('chat', { fetchMessages: 'fetchMessages', sendMessageAction: 'sendMessage' }),
    async sendMessage () {
      if (!this.newMessage.trim()) return

      this.sending = true
      try {
        await this.sendMessage({
          conversationId: this.conversationId,
          message: this.newMessage
        })
        this.newMessage = ''
        this.$nextTick(() => {
          this.scrollToBottom()
        })
      } catch (error) {
        console.error('Failed to send message:', error)
        alert('Failed to send message')
      } finally {
        this.sending = false
      }
    },
    scrollToBottom () {
      this.$refs.messagesEnd.scrollIntoView({ behavior: 'smooth' })
    }
  },
  async mounted () {
    await this.fetchMessages(this.conversationId)
    this.$nextTick(() => {
      this.scrollToBottom()
    })
  },
  watch: {
    conversationId: async function (newId) {
      await this.fetchMessages(newId)
      this.$nextTick(() => {
        this.scrollToBottom()
      })
    },
    messages: function () {
      this.$nextTick(() => {
        this.scrollToBottom()
      })
    }
  }
}
</script>

<style scoped>
.chatbox-container {
  display: flex;
  flex-direction: column;
  height: 100%;
  background-color: #fff;
}

.chatbox-header {
  padding: 1rem;
  border-bottom: 1px solid #e0e0e0;
  background-color: #f9f9f9;
}

.chatbox-header-info h3 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.1rem;
}

.status {
  margin: 0.25rem 0 0 0;
  font-size: 0.8rem;
  color: #42b983;
}

.chatbox-messages {
  flex: 1;
  overflow-y: auto;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  background-color: #f5f5f5;
}

.loading-message,
.empty-message {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #7f8c8d;
  text-align: center;
  font-size: 0.95rem;
}

.chatbox-input-area {
  padding: 1rem;
  border-top: 1px solid #e0e0e0;
  background-color: #fff;
}

.chatbox-input-area form {
  display: flex;
  gap: 0.75rem;
}

.message-input {
  flex: 1;
  padding: 0.75rem 1rem;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  font-size: 0.95rem;
  font-family: inherit;
}

.message-input:focus {
  outline: none;
  border-color: #42b983;
  box-shadow: 0 0 0 3px rgba(66, 185, 131, 0.1);
}

.message-input:disabled {
  background-color: #f5f5f5;
  color: #95a5a6;
}

.btn-send {
  padding: 0.75rem 1.5rem;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.95rem;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.btn-send:hover:not(:disabled) {
  background-color: #369970;
}

.btn-send:disabled {
  background-color: #bdc3c7;
  cursor: not-allowed;
}
</style>
