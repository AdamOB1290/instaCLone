<template>
    <div class="h-100">
        <b-carousel
        class="h-100"
      id="carousel-1"
      v-model="slide"
      :interval="0"
      indicators
      background="#ababab"
      style="text-shadow: 1px 1px 2px #333;"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >
      <b-carousel-slide v-for="(story, key) in storyUser.stories"
        :key="key"
        :caption="'test'"
        >
         <template v-slot:img>
      <img   :src="story.media_file"/>
        </template>
      </b-carousel-slide>

      
    </b-carousel>

   
    </div>
</template>

<script>
  export default {
    data() {
        return {
        sessionUser: null,
        posts: [],
        storyUsers: [],
        storyUser: [],
        publicPath: 'http://localhost:8000/',
        storyUserId: null,
        slide: 0,
        sliding: null,
        

      // Any other options that can be got from plugin documentation
     }
        
    },

    created: function () {
      
      

        this.storyUserId = window.location.href.split("/")[4];
        axios
        .get(this.publicPath+'posts')
        .then((data) => { 
            this.sessionUser = data.data[0].session_user 

            this.posts = data.data;
            this.storyUsers.push(...this.posts[0]['story_users'])
            this.storyUsers.forEach(storyUser => {
                storyUser.stories = []
            })
                    
            this.posts.forEach((post) => {

                if (post.type == "story" || post.type == "post/story" || post.type == "story/post") {
                this.storyUsers.forEach(storyUser => {
                    if (storyUser.id == post.user.id) {
                        if (this.storyUserId == storyUser.id) {
                             storyUser.stories.push(post)
                             this.storyUser = storyUser
                        }
                    }
                });
                }

            });
            
           
        })
        .catch((err) => {});
    },
    
    mounted: function () {
      this.$store.commit("changeState", false);

      $('.main').addClass('stories_main')
    },

    methods: {
      onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      },
    },

    
  }
</script>