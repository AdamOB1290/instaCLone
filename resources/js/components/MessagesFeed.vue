<template>
    <div class="message_feed_wrapper" ref="message_feed_wrapper">
        <ul v-if="contact">
            <li v-for="message in messages" :key="message.id" :class="`message ${message.receiver_id == contact.id ? 'sent' : 'received'}`">
                <div class="text">
                    {{message.content}}
                </div>
            </li>
        </ul>
    </div>
    
</template>
<script>
export default {
    props: {
        contact: {
            type: Object,
        },
        messages: {
            type: Array,
            required: true,
        },
    },

    data(){
        return {
        }
    },

    methods:{
        scrollToBottom() {
            setTimeout(() => {
                this.$refs.message_feed_wrapper.scrollTop = this.$refs.message_feed_wrapper.scrollHeight - this.$refs.message_feed_wrapper.clientHeight 
            }, 50)
        },
    },

    watch:  {
        contact(contact){
            this.scrollToBottom()
        },
        messages(messages){
            this.scrollToBottom()
        },
    },

    mounted() {
    }
}
</script>

<style lang="scss" scoped>
.message_feed_wrapper {
    background: #f0f0f0;
    height: 100%;
    max-height: 470px;
    overflow-y: scroll;
    ul {
        list-style-type: none;
        padding: 5px;
        li {
            &.message {
                margin: 10px 0;
                width: 100%;
                .text {
                    max-width: 200px;
                    border-radius: 12px;
                    padding: 10px 17px 14px;
                    display: inline-block;
                }
                &.received {
                    text-align: left;
                    .text {
                        background: #81c4f9;

                    }
                }
                &.sent {
                    text-align: right;
                    .text {
                        background: #b2b2b2;
                        text-align: left;
                    }
                }
            }
        }
    }
}
</style>