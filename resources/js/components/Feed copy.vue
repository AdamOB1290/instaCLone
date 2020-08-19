<template>
  <div>

    <div class="story-slider border-down pb-1">
      <div class="story-wrapper">
        <div class="">
          <div>
            <img v-if="sessionUser.pfp_type == 'imageUrl'" class=" slider-image self-align-center" :src="sessionUser.pfp"/>
            <img v-else class=" slider-image  " :src="'storage/'+sessionUser.pfp"/>  
          </div>
                      
          <span class="story-username ">{{sessionUser.username}}</span>

        </div>

        <div v-for="(page, key) in storyFeed" :key="key" class="d-flex glider px-2">
                  

            <div class="mx-1 d-flex flex-column align-items-center" v-for="(story, key) in page" :key="key">
              <div class="">
                <img v-if="story.user.pfp_type == 'imageUrl'" class=" slider-image self-align-center " :src="story.user.pfp"/>
              <img v-else class="slider-image " :src="'storage/'+story.user.pfp"/>
              </div>
              
              <span class="story-username ">{{story.user.username}}</span>
            </div>


        </div>
      </div>
    </div>

    <div class="feed_wrapper" v-for="(page, key) in postFeed" :key="key">
      <div class="post_wrapper" v-for="(post, key) in page" :key="key">
        <div class="card rounded-0">
          <div class="card-head d-flex align-items-center border-down py-1">
            <img
              v-if="post.user.pfp_type == 'imageUrl'"
              class="pfp card-img-top rounded-circle mr-2"
              :src="post.user.pfp"
            />
            <img
              v-else
              class="pfp card-img-top rounded-circle mr-2"
              :src="'storage/'+post.user.pfp"
            />
            <span class="username font-weight-bold">{{post.user.username}}</span>
          </div>

          <div class="card-body">
            <video v-if="post.media_type == 'localVideo'" controls muted class>
              <source :src="'storage/'+post.media_file" type="video/mp4" />
            </video>
            <img
              v-else-if="post.media_type == 'imageUrl'"
              class="card-img-top rounded-0"
              :src="post.media_file"
            />
            <img v-else class="card-img-top rounded-0" :src="'storage/'+post.media_file" />
            <div class="px-2">
              <div class="interaction_buttons pt-2 d-flex">

                <!-- like icon -->
                <svg :id="'postLikeId'+post.id" :data-postId="post.id" @click="likeUnlike" aria-label="Like" class="mr-3" :fill="post.likeColor" height="24" viewBox="0 0 48 48" width="24">
                  <path :d="post.likePath"></path>
                </svg>

                <!-- comment icon -->
                  <svg aria-label="Comment" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                    <path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"></path>
                  </svg>

                <!-- direct message icon -->
                  <svg aria-label="Share Post" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                    <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path>
                  </svg>

                <!-- save icon -->
                <svg :id="'postSaveId'+post.id" :data-postId="post.id" @click="saveUnsave" aria-label="Save" class="ml-auto" :fill="post.saveColor" height="24" viewBox="0 0 48 48" width="24">
                  <path :d="post.savePath"></path>
                </svg>

              </div>
              <span class="font-weight-bold">{{post.likes}} likes</span>

              <div class="card-text">
                <span class="description show_more">
                  <span class="username font-weight-bold">{{post.user.username}}</span>
                  {{post.description}}
                </span>
                <!-- this button shows/hides the description -->
                <span class="show_hide" @click="showHideDescription">{{ post.showStatus }}</span>
              </div>
            </div>
          </div>

          <div class="card-footer px-2 py-0">
            <a href class="show_hide" v-if="post.comments.length > 2">
              View All {{post.comments.length}} Comments
            </a>
            <div class="comments_wrapper" v-for="(comment, key) in post.comments.slice(0, 2)" :key="key"
              @click="showHideComment">
              <span class="comments show_more">
                <span class="username font-weight-bold">{{comment.username}}</span>
                {{comment.content}}
              </span>
            </div>          
            <span class="text-secondary ">{{post.created_at}}</span>
          </div>
        </div>
      </div>
      <!-- on 'intersect' event trigger, apply 'intersected' function -->
      <observer v-on:intersect="intersected" />
    </div>
  </div>
</template>

<script>
import Observer from "./Observer"
window.axios = require("axios")
var moment = require('moment');

// require('readmore-js');
// var sortedObjs = _.sortBy( [23,246,14354,67543,2345,12,1,3,5,], function(num) {return num;} );
// console.log(sortedObjs.reverse());

export default {
  data() {
    return {
      posts: [],
      postsType:[],
      storiesType:[],
      postFeed: [],
      storyFeed: [],
      commentCount: 0,
      observer: null,
      sliceIndex: 10,
      iterations: 0,
      sessionUser: [],
      sessionUserId: this.$sessionUserId,
      likedPosts: [],
      savedPosts: [],
      
    };
  },

  created: function () {
    axios
      .get("users/"+this.sessionUserId )
      .then((data) => {
        
        this.sessionUser=data.data
        this.likedPosts.push(...this.sessionUser.liked.posts)
        this.savedPosts.push(...this.sessionUser.favorites)


      })
      .catch((err) => {})

    axios
      .get("posts")
      .then((data) => {
        this.posts = data.data
        this.posts.forEach((post) => {
          post.created_at=moment(post.created_at).fromNow()
          post.showStatus = "Show More"
           if (this.likedPosts.includes(post.id)) {
             post.likePath = 'M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
             post.likeColor = '#ed4956'
           } else {
             post.likePath = 'M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
             post.likeColor = '#262626'
           }

           if (this.savedPosts.includes(post.id)) {
             post.savePath = 'M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z'
             post.saveColor = '#ffbb00'
           } else {
             post.savePath = 'M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z'
             post.saveColor = '#262626'
           }
            
            if (post.type == 'post') {
             this.postsType.push(post)
           } else if (post.type == 'story') {
            this.storiesType.push(post)
           }
        
        })
        this.postFeed.push(this.postsType.slice(0, 10))
        this.storyFeed.push(this.storiesType.slice(0, 10))
      })
      .catch((err) => {});

    
    
  },

  updated: function () {

    new Glider(document.querySelector('.glider'), {

      // `auto` allows automatic responsive
      // width calculations
      slidesToShow: 'auto',
      slidesToScroll: 'auto',

      // should have been named `itemMinWidth`
      // slides grow to fit the container viewport
      // ignored unless `slidesToShow` is set to `auto`
      itemWidth: undefined,

      // if true, slides wont be resized to fit viewport
      // requires `itemWidth` to be set
      // * this may cause fractional slides
      exactWidth: false,

      // speed aggravator - higher is slower
      duration: .5,

      // dot container element or selector
      dots: 'CSS Selector',

      // arrow container elements or selector
      arrows: {
        prev: 'CSS Selector',
        // may also pass element directly
        next: document.querySelector('CSS Selector')
      },

      // allow mouse dragging
      draggable: false,
      // how much to scroll with each mouse delta
      dragVelocity: 3.3,

      // use any custom easing function
      // compatible with most easing plugins
      easing: function (x, t, b, c, d) {
        return c*(t/=d)*t + b;
      },

      // event control
      scrollPropagate: false,
      eventPropagate: true,

      // Force centering slide after scroll event
      scrollLock: false,
      // how long to wait after scroll event before locking
      // if too low, it might interrupt normal scrolling
      scrollLockDelay: 150,

      // Force centering slide after resize event
      resizeLock: true,

      // Glider.js breakpoints are mobile-first
      responsive: [
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 575,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3
          }
        }
      ]
    });
  },

  mounted: function () {

    
  },

  methods: {
    // by default vues js @click has an event parameter from which we can access elements
    showHideDescription(event) {
      // check if the inner text of the element clicked equals 'show more'
      if ($(event.target).html() == "Show More") {
        // replace it by 'show less'
        $(event.target).html("Show Less")

        //  remove the 'show_more' class from the sibling 'description'
        $(event.target.previousElementSibling).toggleClass("show_more")

        // check if the inner text of the element clicked equals 'show less'
      } else if ($(event.target).html() == "Show Less") {
        // replace it by 'show more'
        $(event.target).html("Show More")

        //  add the 'show_more' class from the sibling 'description'
        $(event.target.previousElementSibling).toggleClass("show_more")
      }
    },

    // by default vues js @click has an event parameter from which we can access elements
    showHideComment(event) {
      // check if the element clicked has the class 'show more'
      if ($(event.target).hasClass("show_more")) {
        // remove the class
        $(event.target).removeClass("show_more")
      }
    },

    intersected() {
      //count how many pages (a page has 10 posts) of posts we have then execute
      // the code inside that many times
      // it considers 5<5 true ???
      
      if (this.iterations < Math.floor(this.posts.length / 10)) {
        // push the next page ( of 10 posts) into the array feed
        this.feed.push(this.posts.slice(this.sliceIndex, this.sliceIndex + 10))

        // increment the slice index to get the next page on the next iteration
        this.sliceIndex += 10

        // increment the iteration
        this.iterations++

        // console.log('iteration :'+this.iterations);
        // console.log('limit :'+Math.ceil(this.posts.length / 10));
        // console.log(this.iterations < Math.ceil(this.posts.length / 10));
        // console.log(this.feed);
      }
      
    },


    likeUnlike(event) {
      
      let postLikeId = $(event.currentTarget).attr('id')

      //  check if the post is already liked by the user
      if (this.likedPosts.includes(parseInt($('#'+postLikeId)[0].attributes[1].nodeValue))) {
  
        // apply the laravel unlike function
        axios.get('posts/'+$('#'+postLikeId)[0].attributes[1].nodeValue+'/'+this.$sessionUserId+'/unlike')
        .then((response) => {

          // get the index of the post id we want to delete
          let index = this.likedPosts.indexOf(parseInt($('#'+postLikeId)[0].attributes[1].nodeValue))
          
          //  remove it from the likedPosts array
          this.likedPosts.splice(index, 1)

        })
        $('#'+postLikeId)[0].attributes[3].nodeValue = '#262626'
        $('#'+postLikeId)[0].firstChild.attributes[0].nodeValue = 'M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
        


      } else {

        // apply the laravel like function
        axios.get('posts/'+$('#'+postLikeId)[0].attributes[1].nodeValue+'/'+this.$sessionUserId+'/like')
        .then((response) => {

          // add the post id to the likedPosts array
          this.likedPosts.push(parseInt($('#'+postLikeId)[0].attributes[1].nodeValue))
          
        })

        $('#'+postLikeId)[0].attributes[3].nodeValue = '#ed4956'
        $('#'+postLikeId)[0].firstChild.attributes[0].nodeValue = 'M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
     
        }
    },


    saveUnsave(event) {
      
      let postSaveId = $(event.currentTarget).attr('id')

      //  check if the post is already saved by the user
      if (this.savedPosts.includes(parseInt($('#'+postSaveId)[0].attributes[1].nodeValue))) {
  
        // apply the laravel unSave function
        axios.get('posts/'+$('#'+postSaveId)[0].attributes[1].nodeValue+'/'+this.$sessionUserId+'/unfavorite')
        .then((response) => {

          // get the index of the post id we want to delete
          let index = this.savedPosts.indexOf(parseInt($('#'+postSaveId)[0].attributes[1].nodeValue))
          
          //  remove it from the savedPosts array
          this.savedPosts.splice(index, 1)

        })
        $('#'+postSaveId)[0].attributes[3].nodeValue = '#262626'
        $('#'+postSaveId)[0].firstChild.attributes[0].nodeValue = 'M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z'


      } else {

        // apply the laravel Save function
        axios.get('posts/'+$('#'+postSaveId)[0].attributes[1].nodeValue+'/'+this.$sessionUserId+'/favorite')
        .then((response) => {

          // add the post id to the savedPosts array
          this.savedPosts.push(parseInt($('#'+postSaveId)[0].attributes[1].nodeValue))
          
        })

        $('#'+postSaveId)[0].attributes[3].nodeValue = '#ffbb00'
        $('#'+postSaveId)[0].firstChild.attributes[0].nodeValue = 'M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z'
     
        }
    },

    
  },

  components: {
    Observer,
  },
};
</script>