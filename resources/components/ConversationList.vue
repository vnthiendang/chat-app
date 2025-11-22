<template>
    <div class="conversation-list">
        <div class="list-header">
            <h2>Messages</h2>
            <button @click="createNewConversation" class="new-chat-btn">+ New Chat</button>
        </div>

        <ul class="conversation-items" v-if="conversations.length > 0">
            <li
                v-for="conv in conversations"
                :key="conv.id"
                @click="selectConversation(conv.id)"
                :class="['conversation-item', { active: selectedConversationId === conv.id }]"
            >
                <div class="conversation-avatar">
                    <!-- <img
                        :src="getOtherParticipantAvatar(conv.participants) || '/home/danvswe/Pictures/32bc85081dcb9195c8da.jpg'"
                        :alt="getOtherParticipantName(conv.participants)"
                    /> -->
                </div>
                <div class="conversation-info">
                    <div class="conversation-header">
                        <strong>{{ getOtherParticipantName(conv.participants) }}</strong>
                        <small>{{ formatTime(conv.updated_at) }}</small>
                    </div>
                    <p class="last-message">{{ conv.last_message?.content || 'No messages yet' }}</p>
                    <small v-if="conv.unread_count > 0" class="unread-count">
                        {{ conv.unread_count }}
                    </small>
                </div>
            </li>
        </ul>

        <div v-else class="no-conversations">
            <p>{{ loading ? 'Loading conversations...' : 'No conversations yet' }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { format, isToday, isYesterday } from 'date-fns'

export default {
    name: 'ConversationList',
    props: {
        currentUserId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            conversations: [],
            selectedConversationId: null,
            loading: false,
            error: null,
            convInterval: null,
        }
    },
    mounted() {
        const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjMzNzIzMjcsImV4cCI6MTc2MzM3NTkyNywibmJmIjoxNzYzMzcyMzI3LCJqdGkiOiJmOXpNVEhtU3ZvMGpHaVY3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.h3iSsYg2IFQtpHlcT7gwVLe5-FyvnlhqjkH_nVY6aaM'
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        }
        
        this.fetchConversations()
        
        // Refresh conversations every 3 seconds
        this.convInterval = setInterval(() => {
            this.fetchConversations()
        }, 30000)
    },
    beforeUnmount() {
        if (this.convInterval) {
            clearInterval(this.convInterval)
        }
    },
    methods: {
        async fetchConversations() {
            try {
                this.loading = true
                const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjMzNzIzMjcsImV4cCI6MTc2MzM3NTkyNywibmJmIjoxNzYzMzcyMzI3LCJqdGkiOiJmOXpNVEhtU3ZvMGpHaVY3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.h3iSsYg2IFQtpHlcT7gwVLe5-FyvnlhqjkH_nVY6aaM'
                const response = await axios.get('/api/conversations', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    },
                })
                
                // Handle paginated response
                if (response.data.data) {
                    this.conversations = response.data.data
                } else if (Array.isArray(response.data)) {
                    this.conversations = response.data
                }
                this.error = null
            } catch (err) {
                console.error('Conversations fetch error:', err)
                this.error = 'Failed to load conversations'
            } finally {
                this.loading = false
            }
        },

        selectConversation(conversationId) {
            this.selectedConversationId = conversationId
            this.$emit('conversation-selected', conversationId)
        },

        createNewConversation() {
            this.$emit('create-new-conversation')
        },

        getOtherParticipantName(participants) {
            const other = participants?.find(p => p.id !== this.currentUserId)
            return other?.name || 'Unknown'
        },

        getOtherParticipantAvatar(participants) {
            const other = participants?.find(p => p.id !== this.currentUserId)
            return other?.avatar || null
        },

        formatTime(timestamp) {
            const date = new Date(timestamp)
            if (isToday(date)) {
                return format(date, 'HH:mm')
            }
            if (isYesterday(date)) {
                return 'Yesterday'
            }
            return format(date, 'dd/MM/yyyy')
        },
    },
}
</script>

<style scoped>
.conversation-list {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 30%;
    border-right: 1px solid #e0e0e0;
    background-color: #fff;
}

.list-header {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.list-header h2 {
    margin: 0;
    font-size: 20px;
}

.new-chat-btn {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
}

.new-chat-btn:hover {
    background-color: #0056b3;
}

.conversation-items {
    list-style: none;
    padding: 0;
    margin: 0;
    overflow-y: auto;
    flex: 1;
}

.conversation-item {
    display: flex;
    padding: 10px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.conversation-item:hover {
    background-color: #f9f9f9;
}

.conversation-item.active {
    background-color: #e7f3ff;
    border-left: 4px solid #007bff;
}

.conversation-avatar {
    width: 50px;
    height: 50px;
    margin-right: 15px;
    flex-shrink: 0;
}

.conversation-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.conversation-info {
    flex: 1;
    position: relative;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.conversation-header strong {
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.conversation-header small {
    font-size: 12px;
    color: #999;
    flex-shrink: 0;
}

.last-message {
    margin: 0;
    color: #666;
    font-size: 13px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.unread-count {
    position: absolute;
    top: 10px;
    right: 15px;
    background-color: #ff6b6b;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.no-conversations {
    text-align: center;
    color: #999;
    padding: 20px;
}
</style>