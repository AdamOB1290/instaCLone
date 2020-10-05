<template>
    <div class="chat_wrapper">
        <div class="hidden w-full h-full sm:flex">
            <contactList  :contacts="contacts" @selected="startConversationWith"/>
            <conversation :contact="selectedContact" :messages="messages" @changeTabTo='activateContactList' @newMessage="saveNewMessage"/>
        </div>
        <b-tabs v-model="tabIndex" content-class="" class="w-full sm:hidden" active-nav-item-class="font-weight-bold text-danger" fill lazy>
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
    // props: {
    //     user: {
    //         type: Object,
    //         required: true,
    //     }
    // },
 
    watch: {
        getSessionUser: {
            handler() {
                if (this.$store.getters.getCurrentSessionUser) {
                    this.setSessionUser();
                }
            },
            immediate: true
        }
    },
    data(){
        return {
            sessionUserId: this.$sessionUserId,
            user:'',
            selectedContact: null,
            messages: [],
            contacts: [],
            tabIndex: 0,
        }
    },

    created: function () {
    },
    
    mounted() {
        // IF ERROR : Failed to connect to Pusher -----> SOLUTION = https://github.com/beyondcode/laravel-websockets/issues/152

        // listens and receives data from MessageSent laravel event
        // when a message is sent, laravel Echo displays it dynamically to the receiver

        setTimeout(()=> {
            Echo.private('messages.'+this.user.id)
            .listen('MessageSent', (e) => {
            this.handleIncoming(e.messages);
        })
        }, 1000)
        axios.get('/contacts')
        .then((response) => {
            this.contacts = response.data
        })
        
        
    },

    updated: function(){
    },

    methods: {

        setSessionUser() {
        this.user = this.$store.getters.getCurrentSessionUser;
        },

        startConversationWith(contact) {
            axios.get('/conversation/'+contact.id)
            .then((response) => {
                this.tabIndex = 1
                this.messages = response.data
                this.selectedContact= contact
            })

            this.updateUnreadCount(contact, true)
        },

        saveNewMessage(message) {
            // DEPRECATED// if sessionUser is not sending msg to himself display Msg 
            // if (message.sender_id != message.receiver_id) {
            //     this.messages.push(message);
            // }     
            this.messages.push(message);
            // $('.contact_list_wrapper').animate({scrollTop: 0}, 100);

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

    computed : {
        getSessionUser() {
                return this.$store.getters.getCurrentSessionUser           
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
    height: calc(100vh - 80px);    
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
@media only screen and (min-width: 640px) {

  .chat_wrapper {
    height: calc(100vh - 40px);  
  }
}

</style>