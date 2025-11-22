<template>
    <ul class="chat">
        <li class="left clearfix" v-for="message in messages">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.sender.name }}
                    </strong>
                </div>
                <p>
                    {{ message.content }}
                </p>
            </div>
        </li>
    </ul>
</template>

<script>
export default {
  props: ['messages']
};
</script>