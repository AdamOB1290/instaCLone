<template>
    <div class="contact_list_wrapper">
        <ul>
            <li v-for="contact in sortedContacts" :key="contact.id" :id="'li'+contact.id" @click="selectContact(contact)" :class="{ 'selected': contact == selected}" >
                <div class="avatar">
                    <img class=""
                  :src="contact.pfp" :alt="contact.name"/>
                </div>
                <div class="contact">
                    <p class="name">{{contact.name}}</p>
                    <p class="email">{{contact.email}}</p>
                </div>
                <!-- <span v-if="contact.unread" class="">{{contact.unread}}</span> -->
                <div class="notif_count unread" >
                    <span  v-if="contact.unread" class="relative inline-flex rounded-full h-4 w-4 bg-green-500 text-center" >
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span> 
                        <span class="mx-auto text-white">{{contact.unread}}</span>
                    </span>
                </div>

            </li>
        </ul>
    </div>
    
</template>
<script>
export default {
    props: {
        contacts:{
            type: Array,
            default: [],
        }
    },

    data(){
        return {
            selected: this.contacts.length ? this.contacts[0]: null,
        }
    },
    methods: {
        selectContact(contact) {
            this.selected = contact
            this.$emit('selected', contact)
        }
    },

    computed: {
        sortedContacts() {
            return _.sortBy(this.contacts, [(contact) => {
                // selected contact goes to the top of the contact list
                // if (contact == this.selected) {
                //     return Infinity;
                // }
                return contact.unread;
            }]).reverse();
        }
    },

    mounted() {
    },
}
</script>

<style lang="scss" scoped>
.contact_list_wrapper {
    flex: 2;
    min-width: 320px;
    height: 100%;
    overflow-y: scroll;
    border-left: 1px solid #a6a6a6;
    border-right: 1px solid #a6a6a68c;
    
    ul {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
        li {
            display: flex;
            padding: 2px;
            border-bottom: 1px solid #aaaaaa;
            height: 80px;
            position: relative;
            cursor: pointer;
            &.selected {
                background: #dfdfdf;
            }
            .notif_count.unread{
                top: 20px;
                right: 15px;
                font-size: 10px;
            }
            .avatar {
                flex: 1;
                display: flex;
                align-items: center;
                img {
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    margin: 0 auto;
                }
            }
            .contact {
                flex: 2;
                font-size: 13px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: center;
                p {
                    margin: 0;
                    &.name {
                        font-weight: bold;
                    }
                }
            }
        }
    }
}

.contact_list_wrapper::-webkit-scrollbar {
    width: 6px!important;
    height: 6px!important;
}


</style>