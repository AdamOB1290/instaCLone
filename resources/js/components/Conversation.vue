<template>
    <div class="conversation_wrapper">
        <div class="d-flex">
            <i @click="goToContactListTrigger" class="fas fa-arrow-left align-self-center left pl-3 cursor-pointer"></i>
            <h1 v-if="!contact" class="center">Select a Contact</h1>
            <h1 v-else @click="goToProfile" :data-userId="contact.id">{{contact.name}} </h1>
            <i @click="goToContactListTrigger" class="align-self-center right invisible"></i>
        </div>
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
            publicPath: 'http://localhost:8000/',
        }
    },

    methods:{
        goToContactListTrigger() {
            this.$emit('changeTabTo');
        },
        
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

        },

         goToProfile(event) {
            var userId = event.target.attributes[1].nodeValue;
            window.location.replace(this.publicPath+userId+'/profile')
        },

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
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex: 5;
    height: 100%;
    h1 {
        text-align: center;
        font-size: 20px;
        padding: 10px;
        margin: 0;
        border-bottom: 1px dashed lightgray;
    }
    .fa-arrow-left{
        font-size: 20px;
        // cursor: pointer;
    }
    .left,.right{
        flex: 1;
    }
}
</style>