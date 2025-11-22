<template>
  <div>
    <a href="https://vite.dev" target="_blank">
      <img src="/home/danvswe/vuejs/vite-project/public/vite.svg" class="logo" alt="Vite logo" />
    </a>
  </div>
  <div>
    <Chat></Chat>
  </div>
</template>
<script>
import ChatBox from './components/ChatBox.vue';
import ConversationList from './components/ConversationList.vue';
import HelloWorld from './components/HelloWorld.vue';
import Chat from './components/Chat.vue';

    export default {
      // Component logic
      components: {
        ChatBox, ConversationList, Chat
      },
      // data() {
      //   return {
      //       messages: "xin chao cac ban ne",
      //       error: null,
      //   }
      // },
    };
    </script>
<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
