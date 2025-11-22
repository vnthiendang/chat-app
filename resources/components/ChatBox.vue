<template>
    <div class="chat-box">
        <div class="chat-header">
            <h3>{{ getConversationTitle() }}</h3>
            <small v-if="conversation?.unread_count > 0" class="unread-badge">
                {{ conversation.unread_count }} unread
            </small>
        </div>

        <ChatMessages 
            :messages="messages" 
            :currentUserId="currentUserId"
            v-if="messages.length > 0">
            </ChatMessages>
        <div v-else class="no-messages">
            <p>No messages yet. Start the conversation!</p>
        </div>

        <div class="chat-input-area">
            <form @submit.prevent="sendMessage">
                <input
                    v-model="messageContent"
                    type="text"
                    placeholder="Type a message..."
                    class="message-input"
                    :disabled="loading"
                />
                <button type="submit" class="send-btn" :disabled="loading || !messageContent.trim()">
                    {{ loading ? 'Sending...' : 'Send' }}
                </button>
            </form>
            <div v-if="error" class="error-message">{{ error }}</div>
        </div>
    </div>
</template>

<script>
import ChatMessages from './ChatMessages.vue'
import axios from 'axios'
export default {
    name: 'ChatBox',
    components: {
        ChatMessages,
    },
    props: {
        conversationId: {
            type: Number,
            // required: true,
        },
        currentUserId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            conversation: null,
            messages: [],
            messageContent: '',
            loading: false,
            error: null,
            messageInterval: null,
        }
    },
    watch: {
        // conversationId(newId) {
        //     if (newId) {
        //         // this.fetchConversation()
        //         this.fetchMessages()
        //     }
        // }
    },
    mounted() {
        // const token = localStorage.getItem('token') || sessionStorage.getItem('token')
        const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjMzNzIzMjcsImV4cCI6MTc2MzM3NTkyNywibmJmIjoxNzYzMzcyMzI3LCJqdGkiOiJmOXpNVEhtU3ZvMGpHaVY3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.h3iSsYg2IFQtpHlcT7gwVLe5-FyvnlhqjkH_nVY6aaM'
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        }
        
        // this.fetchConversation()
        this.fetchMessages()
        
        // Auto-refresh messages every 2 seconds (2000)
        this.messageInterval = setInterval(() => {
            this.fetchMessages()
        }, 20000)
    },
    beforeUnmount() {
        if (this.messageInterval) {
            clearInterval(this.messageInterval)
        }
    },
    methods: {
        async fetchConversation() {
            try {
                // const token = localStorage.getItem('token') || sessionStorage.getItem('token')
                const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjMzNzIzMjcsImV4cCI6MTc2MzM3NTkyNywibmJmIjoxNzYzMzcyMzI3LCJqdGkiOiJmOXpNVEhtU3ZvMGpHaVY3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.h3iSsYg2IFQtpHlcT7gwVLe5-FyvnlhqjkH_nVY6aaM'
                const response = await axios.get(
                    `/api/conversations/${this.conversationId}`,
                    {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                        },
                    }
                )
                this.conversation = response.data.conversation
                this.error = null
            } catch (err) {
                this.error = 'Failed to load conversation'
                console.error('Conversation fetch error:', err)
            }
        },

        async fetchMessages() {
            try {
                // const token = localStorage.getItem('token') || sessionStorage.getItem('token')
                const response = await axios.get(
                    `/messages`,
                    // {
                    //     headers: {
                    //         'Authorization': `Bearer ${token}`,
                    //         'Accept': 'application/json',
                    //     },
                    // }
                )
                
                // Handle paginated response
                console.log('Messages fetch response:', response)
                if (response.data.data) {
                    this.messages = response.data.data
                } else if (Array.isArray(response.data)) {
                    this.messages = response.data
                }
                this.error = null
            } catch (err) {
                console.error('Messages fetch error:', err)
                if (err.response?.status === 403) {
                    this.error = 'You do not have access to this conversation'
                }
            }
        },

        async sendMessage() {
            if (!this.messageContent.trim()) return

            this.loading = true
            try {
                // const token = localStorage.getItem('token') || sessionStorage.getItem('token')
                const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjMzNzIzMjcsImV4cCI6MTc2MzM3NTkyNywibmJmIjoxNzYzMzcyMzI3LCJqdGkiOiJmOXpNVEhtU3ZvMGpHaVY3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.h3iSsYg2IFQtpHlcT7gwVLe5-FyvnlhqjkH_nVY6aaM'
                const response = await axios.post(
                    '/messages',
                    {
                        conversation_id: 11,
                        content: this.messageContent,
                        sender_id: 1
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                        },
                    }
                )

                this.messages.push(response.data)
                this.messageContent = ''
                this.error = null
                
                // Scroll to bottom
                this.$nextTick(() => {
                    const container = this.$el.querySelector('.chat-messages-container')
                    if (container) {
                        container.scrollTop = container.scrollHeight
                    }
                })
            } catch (err) {
                this.error = err.response?.data?.message || 'Failed to send message'
                console.error('Send message error:', err)
            } finally {
                this.loading = false
            }
        },

        getConversationTitle() {
            if (!this.conversation?.participants) return 'Chat'
            const other = this.conversation.participants.find(p => p.id !== this.currentUserId)
            return other?.name || 'Chat'
        },
    },
}
</script>

<style scoped>
.chat-box {
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fff;
}

.chat-header {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h3 {
    margin: 0;
    font-size: 18px;
}

.unread-badge {
    background-color: #ff6b6b;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
}

.chat-input-area {
    padding: 15px;
    border-top: 1px solid #e0e0e0;
}

.chat-input-area form {
    display: flex;
    gap: 10px;
}

.message-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 20px;
    outline: none;
    font-size: 14px;
}

.message-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

.send-btn {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
}

.send-btn:hover:not(:disabled) {
    background-color: #0056b3;
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 10px;
    padding: 10px;
    background-color: #f8d7da;
    border-radius: 4px;
}

.no-messages {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}
</style>