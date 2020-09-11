<template>
    <div key="storyKey" class=" carousel_wrapper h-100" >
      <div class="carousel_max_width">
       
      <b-carousel
      id="carousel-1"
      v-model="slide"
      :interval="0"
      indicators
      controls
      background="#262626"
      style="text-shadow: 1px 1px 2px #333;"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >
      <b-carousel-slide v-for="(story, key) in storyUser.stories" :key="key">
         <template v-slot:img class="position-relative">
           <div class="storyHeader d-flex align-items-center border-down py-1 px-2">
                <img @click="goToProfile" :data-userId="storyUser.id" class="pfp card-img-top rounded-circle mr-2" :src="storyUser.pfp" />
                <span @click="goToProfile" :data-userId="storyUser.id" class="username font-weight-bold">{{storyUser.username}}</span>
                <i class="fas fa-ellipsis-h ml-auto mr-2 pt-2 storySettings" v-b-modal="'my_storyModal'+story.id"></i>
                <a :href="publicPath+'feeds'"><span class="close_icon"></span></a>
            </div>
            <b-modal modal-class="settings_Modal" :id="'my_storyModal'+story.id" :ref="'my_storyModal'+story.id"  hide-header hide-footer >
              <button :id="'storyShareStoryId'+story.id" :data-storyId="story.id" @click="sharePost"  class="w-100 settings_btn px-5 py-2">Share as post</button>
              <button :id="'storyDeleteId'+story.id" :data-storyId="story.id" @click="deleteStory" class="w-100 settings_btn text-danger px-5 py-2 border-0">Delete</button>
            </b-modal> 
            <div class="img_carousel_wrapper">
          <span class="helper"></span><img class="stories_images"  :src="story.media_file"/></div>
            <!-- <cld-image dpr="auto" responsive="width" width="auto" height="auto" crop="scale" :src="story.media_file" publicId="" class="stories_images my-2">
              <cld-transformation border="3px_solid_rgb:00390b" radius="20" />
              <cld-transformation quality="auto" fetchFormat="auto" />
            </cld-image> -->
            
        </template>
      </b-carousel-slide>

      
    </b-carousel>

   </div>
    </div>
</template>

<script>
  export default {
    data() {
        return {
          storyKey:0,
          publicPath: 'http://localhost:8000/',
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

    updated: function () {
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
      forceRerender () {
      this.storyKey += 1;  
      },

      onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      },

      sharePost (event) {
        var targetId = event.target.attributes[1].nodeValue
        var updatedType = 'story/post'
        axios({
          method: 'patch',
          url: this.publicPath+'posts/'+targetId,
          data: {
            type: updatedType,
            },
        }).then((response) => {
        $(this.$refs)[0]['my_storyModal'+targetId][0].hide() 
        })



      },

      deleteStory (event) {
        var targetId = event.target.attributes[1].nodeValue;
        axios({
          method: 'delete',
          url: this.publicPath+'posts/'+targetId,
          data: {
            type:'story'
          },
        }).then((response) => {
          console.log(response);
          this.storyUser.stories.forEach(story => {
              if (targetId == story.id) {
                let index = this.storyUser.stories.indexOf(story)
                if (this.storyUser.stories.length == 1) {
                  this.storyUser.stories.splice(index, 1)
                  window.location.href = this.publicPath+'feeds'; 
                } else {
                  this.storyUser.stories.splice(index, 1)
                }
              }
            
          });
        $(this.$refs)[0]['my_storyModal'+targetId][0].hide() 

        })
      },

      goToProfile(event) {
        var userId = event.target.attributes[0].nodeValue;
        window.location.replace(this.publicPath+userId+'/profile')
      },
    },

    
  }
</script>