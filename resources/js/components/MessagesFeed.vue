<template>
    <div class="message_feed_wrapper" ref="message_feed_wrapper">
        <ul v-if="contact">
            <li v-for="(message, key) in messages" :key="key" :class="`message_wrapper ${message.receiver_id == contact.id ? 'sent' : 'received'}`">
                <div class="message">
                    <div @click="goToPost" class="cursor-pointer" :data-postId="message.shared_post.id" v-if="message.shared_post">
                        <div class="d-flex align-items-center pb-1 px-3">
                            <img class="pfp rounded-circle mr-2" :src="message.shared_post.user.pfp" />
                            <span class="username font-weight-bold">{{message.shared_post.user.username}}</span>
                        </div>
                        <div class="sharedPost">
                            <video v-if="message.shared_post.media_type == 'video'" controls muted class>
                                <source :src="'storage/'+message.shared_post.media_file"  />
                            </video>
                            <img  v-else-if="message.shared_post.media_type == 'image'" class="card-img-top rounded-0" :src="message.shared_post.media_file"/>
                                <span class="msg_description px-3 pt-1">
                                    <!-- <span class="username font-weight-bold float-left">{{message.shared_post.user.username }}</span> -->
                                    {{message.shared_post.description}}
                                </span>
                        </div>
                    </div>
                    <div v-else class="text px-3" >{{message.content}}</div>
                    
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
            publicPath: 'http://localhost:8000/',
        }
    },

    methods:{
        scrollToBottom() {
            setTimeout(() => {
                this.$refs.message_feed_wrapper.scrollTop = this.$refs.message_feed_wrapper.scrollHeight - this.$refs.message_feed_wrapper.clientHeight 
            }, 50)
        },
        goToPost(event) {
            let postId= event.target.parentElement.parentElement.attributes[1].nodeValue
            // window.location.replace(this.publicPath+'post/'+postId)
            this.$router.push({path: '/post/'+postId})
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
    flex: 1;
    overflow-y: scroll;
    ul {
        list-style-type: none;
        padding: 5px;
        li {
            &.message_wrapper {
                margin: 10px 0;
                width: 100%;
                .message {
                    max-width: 200px;
                    border-radius: 12px;
                    padding: 7px 0 10px;
                    display: inline-block;

                    .msg_description {
                        overflow: hidden;
                        text-overflow: ellipsis;
                        display: -webkit-box;
                        -webkit-line-clamp: 2; /* number of lines to show */
                        -webkit-box-orient: vertical;
                    }
                }
                

                &.received {
                    text-align: left;
                    .message {
                        background: #219bff;
                        color: white;
                    }
                }
                &.sent {
                    text-align: right;
                    .message {
                        background: #636363;
                        text-align: left;
                        color: white;
                    }
                }
            }
        }
    }
}
</style>