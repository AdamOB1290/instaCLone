<template>
    <div>
        <div  class="glider story_slider border-down pb-1" >
            <div  class="story_wrapper d-flex px-2 my-1">
                <div @click="openWidget" class="float-left d-flex flex-column align-items-center mr-1">
                    <div class="gradiant_background d-flex">
                        <!-- why do we need both m-auto and dflex to center the image  -->
                        <div class="m-auto d-flex align-items-center justify-content-center position-relative">
                        <img class="slider-image" :src="sessionUser.pfp" />
                        <i class="plusStory fas fa-plus-circle position-absolute text-primary"></i>
                        </div>
                    </div>
                    <span class="story-username text-center">Your Story</span>
                </div>
                <div v-for="(page, key) in storyFeed" :key="key" class="d-flex position-relative">
                <div @click="storyHref" v-for="(user, key) in page" :key="key" class="mx-1 d-flex flex-column align-items-center">
                    <div class="gradiant_background d-flex flex-column">
                    <div class="m-auto align-items-center d-flex justify-content-center">
                        <img :data-userId="user.id" class="slider-image" :src="user.pfp"/>
                    </div>
                    </div> 
                    <span class="story-username">{{user.username}}</span>
                </div>
                <div class="storyObserver"><observer v-on:intersect="storyIntersected"/></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Observer from "./Observer";

export default {
    props: ['sessionUser', 'storyFeed', 'storyUsers', 'widget'],

    data(){
        return {
            publicPath: 'http://localhost:8000/',

            observer: null,
            
            // Story Data
            storiesType: [],
            storySliceIndex: 10,
            storyIterations: 0,
        }
    },

    created: function () {
    },

    methods: {
        openWidget() {
            this.widget.open();
        },
        
        storyHref (event) {
            var targetId = event.target.attributes[0].nodeValue
            window.location.replace(this.publicPath+'story/'+targetId);  
        },

        storyIntersected () {
            if (this.storyIterations < Math.floor(this.storyUsers.length / 10)) {
                // push the next page ( of 10 stories) into the array feed
                this.storyFeed.push(this.storyUsers.slice(this.storySliceIndex, this.storySliceIndex + 10));

                // increment the slice index to get the next page on the next iteration
                this.storySliceIndex += 10;

                // increment the iteration
                this.storyIterations++;

                // console.log('iteration :'+this.iterations);
                // console.log('limit :'+Math.ceil(this.posts.length / 10));
                // console.log(this.iterations < Math.ceil(this.posts.length / 10));
            }
        },
    
    },

    computed: {
    },

    mounted: function() {
       
    },

    component: {
        Observer,
    },

}
</script>