<template>
    <div class="conversation_wrapper">
        <h1>{{ contact ? contact.name : 'Select a Contact'}}</h1>
        <MessagesFeed :contact="contact" :messages="messages"/>
        <MessageComposer @send="sendMessage"/>
    </div>
    
</template>
<script>
import MessagesFeed from "./MessagesFeed";
import MessageComposer from "./MessageComposer";
export default {
    props: {
        contact: {
            type: Object,
            default: null,
        },
        messages:{
            type: Array,
            default: [],
        }
    },

    data(){
        return {
        }
    },

    methods:{
        sendMessage(text) {
            if (!this.contact) {
                return;
            }

            axios.post('/conversation/send', {
                contactId: this.contact.id,
                text: text
            }).then((response)=>{
                this.$emit('newMessage', response.data);
            }).catch((err) => {
                console.log(err);
            });

        }
    },

    mounted() {
    },

    components:{
        MessagesFeed,
        MessageComposer,
    }
}
</script>

<style lang="scss" scoped>
.conversation_wrapper {
    flex: 5;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    h1 {
        text-align: center;
        font-size: 20px;
        padding: 10px;
        margin: 0;
        border-bottom: 1px dashed lightgray;
    }
}
</style>