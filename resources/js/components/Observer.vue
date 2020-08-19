<template>
    <!-- this is the element that will be observed in feed.vue -->
    <div class="observer"></div>
</template>

<script>
    export default {
        // ask iliass about the name
        name:'observer',
        data() {
            return{
                observer: null,
            }
        },
        mounted() {

            this.observer = new IntersectionObserver(([entry]) => {

                //check if an element is being observed and if the observed target has class 'observed' 
                //and if the element is instersecting
                if ( entry && !$(entry.target).hasClass('observed')&& entry.isIntersecting) {

                    // link the intersection event to the observer.vue component
                    this.$emit('intersect');

                    // add a class "observed" to the element
                    $(entry.target).addClass('observed');
                } 
            });

            
            this.observer.observe(this.$el)

        }
    }
</script>
