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
            <p>Select a conversation to start chatting</p>
        </div>
    </div>
</template>

<script>
import ConversationList from './ConversationList.vue';
import ChatBox from './ChatBox.vue';

export default {
    name: 'Chat',
    components: {
        ConversationList,
        ChatBox,
    },
    props: {
        currentUserId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            selectedConversationId: null,
        };
    },
    methods: {
        onConversationSelected(conversationId) {
            this.selectedConversationId = conversationId;
        },

        onCreateNewConversation() {
            // TODO: Show modal to select user to chat with
            console.log('Create new conversation');
        },
    },
};
</script>

<style scoped>
.chat-main-container {
    display: flex;
    height: 100vh;
    background-color: #f5f5f5;
}

ConversationList {
    width: 30%;
    flex-shrink: 0;
}

ChatBox,
.no-conversation-selected {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-conversation-selected {
    color: #999;
    font-size: 18px;
}
</style>