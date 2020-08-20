<template>
    <div class="container">
        
        <slick ref="slick" :options="slickOptions">
        <div>1</div>
        <div>2</div>
        <div>3</div>
        <div>4</div>
        </slick>
            
    </div>
</template>

<script>
import Slick from 'vue-slick';

    export default {
        data() {
        return {
            slickOptions: {
                slidesToShow: 1,
                // Any other options that can be got from plugin documentation
            },
        };
    },
        mounted() {
            console.log('Component mounted.')
        },
        components: {
    Slick,
  },
    }
</script>
