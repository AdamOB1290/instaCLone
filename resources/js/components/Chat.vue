<template>
    <div class="chat_wrapper">
          <b-tabs v-model="tabIndex"  content-class="" class="w-100" active-nav-item-class="font-weight-bold text-danger" fill lazy>
                <b-tab>
                        <contactList  :contacts="contacts" @selected="startConversationWith"/>
                </b-tab>
                <b-tab>
                    <conversation :contact="selectedContact" :messages="messages" @changeTabTo='activateContactList' @newMessage="saveNewMessage"/>
                </b-tab>
            </b-tabs>
       
        
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
            tabIndex: 0,
        }
    },
    
    mounted() {
        // IF ERROR : Failed to connect to Pusher -----> SOLUTION = https://github.com/beyondcode/laravel-websockets/issues/152

        // listens and receives data from MessageSent laravel event
        // when a message is sent, laravel Echo displays it dynamically to the receiver
        Echo.private('messages.'+this.user.id)
         .listen('MessageSent', (e) => {
            this.handleIncoming(e.messages);
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
                this.tabIndex = 1
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
            
            this.updateUnreadCount(message.sender, false)
        },

        updateUnreadCount(selectedContact, reset) {
            this.contacts = this.contacts.map((contact) => {
                if (contact.id !== selectedContact.id) {
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

       activateContactList() {
           this.tabIndex=0
       }


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
    .tabs{
        > :nth-child(1) {
            background: green;
            display: none;
        }
        > :nth-child(2) {
            height: 100%;
            .tab-pane {
                height: 100%;
            }
        }
    }
    
}

</style>