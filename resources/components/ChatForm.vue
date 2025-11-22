<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
import ChatBox from './ChatBox.vue';
import ChatMessages from './ChatMessages.vue';
import ConversationList from './ConversationList.vue';

    export default {
        components: { ChatMessages, ChatBox, ConversationList },
        props: ['sender'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    sender: this.sender,
                    content: this.newMessage
                });

                this.newMessage = ''
            }
        }    
    }
</script>
