<template>
  <div class="message-container" :class="{ 'message-current-user': isCurrentUser }">
    <div class="message-bubble" :class="{ 'bubble-current-user': isCurrentUser }">
      <p class="message-text">{{ message.content }}</p>
      <span class="message-time">{{ formatTime(message.created_at) }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ChatMessage',
  props: {
    message: {
      type: Object,
      required: true
    },
    isCurrentUser: {
      type: Boolean,
      required: true
    }
  },
  methods: {
    formatTime (dateString) {
      return new Date(dateString).toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit'
      })
    }
  }
}
</script>

<style scoped>
.message-container {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 0.5rem;
}

.message-container.message-current-user {
  justify-content: flex-end;
}

.message-bubble {
  max-width: 60%;
  padding: 0.75rem 1rem;
  border-radius: 12px;
  background-color: #e8e8e8;
  color: #2c3e50;
  word-wrap: break-word;
}

.message-bubble.bubble-current-user {
  background-color: #42b983;
  color: white;
}

.message-text {
  margin: 0 0 0.25rem 0;
  line-height: 1.4;
  font-size: 0.95rem;
}

.message-time {
  display: block;
  font-size: 0.75rem;
  opacity: 0.7;
}
</style>
