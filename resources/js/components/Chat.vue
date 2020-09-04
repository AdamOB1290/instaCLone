<template>
    <div class="chat_wrapper">
        <contactList  :contacts="contacts" @selected="startConversationWith"/>
        <conversation :contact="selectedContact" :messages="messages" @newMessage="saveNewMessage"/>
    </div>
    
</template>
<script>
import Conversation from "./Conversation";
import ContactList from "./ContactList";
export default {
    props: {
        user: {
            type: Object,
            required: true,
        }
    },

    data(){
        return {
            selectedContact: null,
            messages: [],
            contacts: [],
        }
    },

    methods: {
        startConversationWith(contact) {
            axios.get('/conversation/'+contact.id)
            .then((response) => {
                this.messages = response.data
                this.selectedContact= contact
            })
        },

        saveNewMessage(message) {
            this.messages.push(message);

        },

        handleIncoming (message) {
            if (this.selectedContact && message.sender_id == this.selectedContact.id) {
                this.saveNewMessage(message)
                return;
            }

            alert(message.content);
        }

    },

    mounted() {
        // console.log(`messages${this.user.id}`);
        // console.log('messages'+this.user.id);
        // IF ERROR : Failed to connect to Pusher -----> SOLUTION = https://github.com/beyondcode/laravel-websockets/issues/152
        
         Echo.private('messages.'+this.user.id)
         .listen('MessageSent', (e) => {
            this.handleIncoming(e.chat);

        })

        axios.get('/contacts')
        .then((response) => {
            this.contacts = response.data
        })
    },
    components : {
        Conversation,
        ContactList,
    }
}
</script>
<style lang="scss" scoped>
.chat_wrapper {
    display: flex;
    height: 100%;
}
</style>