<template>
    <div class="message_composer_wrapper">
    <textarea v-model="message" @keydown.enter="send" placeholder="Send Message..."></textarea>
    </div>
    
</template>
<script>
export default {
    props: {
    },

    data(){
        return {
            message: '',
        }
    },

    methods:{
        send(event) {
            event.preventDefault();
            if (this.message == '') {
                return;
            }
            this.$emit('send', this.message);
            this.message= '';
        }
    },

    mounted() {
    }
}
</script>

<style lang="scss" scoped>
.message_composer_wrapper textarea {
    width: 100%;
    padding: 10px;
    resize: none;
    border-radius: 3px;
    border: 1px solid lightgray;
    padding: 6px;
}
</style>