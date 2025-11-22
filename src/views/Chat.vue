<template>
  <div class="chat-main-container">
    <ConversationList
      :currentUserId="currentUserId"
      @conversation-selected="onConversationSelected"
      @create-new-conversation="onCreateNewConversation"
    />

    <ChatBox
      v-if="selectedConversationId"
      :conversationId="selectedConversationId"
      :currentUserId="currentUserId"
    />

    <div v-else class="no-conversation-selected">
      <p>Start a conversation</p>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ConversationList from '../components/ConversationList.vue'
import ChatBox from '@/components/ChatBox.vue'

export default {
  name: 'Chat',
  components: {
    ConversationList,
    ChatBox
  },
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('chat', ['selectedConversationId']),
    currentUserId () {
      return this.currentUser ? this.currentUser.id : null
    }
  },
  methods: {
    onConversationSelected (conversationId) {
      this.$store.dispatch('chat/selectConversation', conversationId)
    },

    onCreateNewConversation () {
      console.log('Create new conversation triggered')
    }
  },
  async mounted () {
    if (!this.currentUser) {
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
.chat-main-container {
  display: flex;
  height: calc(100vh - 80px);
  background-color: #f5f5f5;
  gap: 0;
}

.chat-main-container > div:nth-child(1) {
  width: 30%;
  flex-shrink: 0;
  border-right: 1px solid #e0e0e0;
}

.chat-main-container > div:nth-child(2),
.no-conversation-selected {
  flex: 1;
}

.no-conversation-selected {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #999;
  font-size: 18px;
  background-color: #fff;
}

@media (max-width: 768px) {
  .chat-main-container {
    flex-direction: column;
  }

  .chat-main-container > div:nth-child(1) {
    width: 100%;
    max-height: 40%;
  }

  .chat-main-container > div:nth-child(2),
  .no-conversation-selected {
    max-height: 60%;
  }
}
</style>
