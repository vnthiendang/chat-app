import './bootstrap'
import { createApp } from 'vue'
import Chat from '../components/Chat.vue'
import ChatMessages from '../components/ChatMessages.vue'
import ChatBox from '../components/ChatBox.vue'
import ChatForm from '../components/ChatForm.vue'
import ConversationList from '../components/ConversationList.vue'
import HelloWorld from '../components/HelloWorld.vue'
import VueAxios from 'vue-axios';
import axios from 'axios';
import App from '../App.vue'

// Create a new Vue application instance
const app = createApp(App);

app.use(VueAxios, axios);

// app.component('Chat', Chat);
// app.component('ChatMessages', ChatMessages);
// app.component('ChatForm', ChatForm);
// app.component('ChatBox', ChatBox);
// app.component('ConversationList', ConversationList);
app.component('HelloWorld', HelloWorld);
// Make axios globally available
app.config.globalProperties.$http = axios

// Configure axios default headers
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
axios.defaults.headers.common['Accept'] = 'application/json'

// const router = new VueRouter({ mode: 'history', routes: routes});
// const app = new Vue(Vue.util.extend({ router }, App)).$mount('#app');
app.mount('#app')
