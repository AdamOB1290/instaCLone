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
    
    mounted() {
        // IF ERROR : Failed to connect to Pusher -----> SOLUTION = https://github.com/beyondcode/laravel-websockets/issues/152

        // listens and receives data from MessageSent laravel event
        // when a message is sent, laravel Echo displays it dynamically to the receiver
         Echo.private('messages.'+this.user.id)
         .listen('MessageSent', (e) => {
            this.handleIncoming(e.chat);
        })

        axios.get('/contacts')
        .then((response) => {
            this.contacts = response.data
        })
    },

    methods: {
        startConversationWith(contact) {
            this.updateUnreadCount(contact.id, true)
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
            // checks if selected contact is not null
            // and if it's id matches message.sender_id
            if (this.selectedContact && (message.sender_id == this.selectedContact.id)) {
                this.saveNewMessage(message)
                return;
            }
            console.log(message);
            this.updateUnreadCount(message.sender_id, false)
        },

        updateUnreadCount(selectedContactId, reset) {
            this.contacts = this.contacts.map((contact) => {
                if (contact.id !== selectedContactId) {
                    return contact;
                }

                if (reset) {
                    contact.unread = 0;
                } else {
                    contact.unread += 1;
                }
                
                return contact
            })
        },

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