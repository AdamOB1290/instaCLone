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
                <span v-if="contact.unread" class="unread">{{contact.unread}}</span>
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
            $('.contact_list_wrapper').animate({scrollTop: 0}, 100);
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
    height: 100%;
    overflow-y: scroll;
    border-left: 1px solid #a6a6a6;
    
    ul {
        list-style-type: none;
        padding-left: 0;
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
            span.unread {
                background: #82e0a8;
                color: #fff;
                position: absolute;
                right: 11px;
                top: 20px;
                display: flex;
                font-weight: 700;
                justify-content: center;
                align-items: center;
                line-height: 12px;
                font-size: 7px;
                padding: 0 4px;
                border-radius: 50%;
            }
            .avatar {
                flex: 1;
                display: flex;
                align-items: center;
                img {
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                    margin: 0 auto;
                }
            }
            .contact {
                flex: 3;
                font-size: 10px;
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
</style>