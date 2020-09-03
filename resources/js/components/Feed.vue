<template>
  <div key="feedKey" class="h-100">
    <div v-if="posts.length > 0" class="glider story_slider border-down pb-1" :class="post_display">
      <div class="story_wrapper d-flex px-2 my-1">
        <div class="float-left d-flex flex-column align-items-center mr-1">
          <div class="gradiant_background d-flex">
            <!-- why do we need both m-auto and dflex to center the image  -->
            <div class="m-auto d-flex align-items-center justify-content-center position-relative">
              <img
                v-if="sessionUser.pfp_type == 'imageUrl'"
                class="slider-image"
                :src="sessionUser.pfp"
              />
              <img v-else-if=" sessionUser.pfp_type == 'localImage'" class="slider-image" :src="'storage/'+sessionUser.pfp" />
              <i class="plusStory fas fa-plus-circle position-absolute text-primary"></i>
            </div>
          </div>
          <span class="story-username text-center">Your Story</span>
        </div>
        <div v-for="(page, key) in storyFeed" :key="key" class="d-flex position-relative">
          <div @click="storyHref" v-for="(user, key) in page" :key="key" class="mx-1 d-flex flex-column align-items-center">
            <div class="gradiant_background d-flex flex-column">
              <div class="m-auto align-items-center d-flex justify-content-center">
                <img  v-if="user.pfp_type == 'imageUrl'" :data-userId="user.id" class="slider-image" :src="user.pfp"/>
                <img v-else class="slider-image" :data-userId="user.id" :src="'storage/'+user.pfp" />
              </div>
            </div> 
            <span class="story-username">{{user.username}}</span>
          </div>
          <div class="storyObserver"><observer v-on:intersect="storyIntersected"/></div>
        </div>
      </div>
    </div>

    <div class="feed_wrapper " v-for="(page, key) in postFeed" :key="key" :class="post_display">
      
      <div class="post_wrapper " v-for="(post, key) in page" :key="key">
        <div class="card rounded-0">
          <div class="card-head d-flex align-items-center border-down py-1">
            <img v-if="post.user.pfp_type == 'imageUrl'" class="pfp card-img-top rounded-circle mr-2"
              :src="post.user.pfp" />
            <img v-else class="pfp card-img-top rounded-circle mr-2" :src="'storage/'+post.user.pfp"/>
            <span class="username font-weight-bold">{{post.user.username}}</span>
            <i class="fas fa-ellipsis-h text-secondary ml-auto mr-2 pt-2" v-b-modal="'my_postModal'+post.id"></i>
            <b-modal :id="'my_postModal'+post.id" :ref="'my_postModal'+post.id" class="settings_Modal" hide-header hide-footer >
              <button :id="'postEditId'+post.id" :data-postId="post.id" @click="editPostDescription"  class="w-100 settings_btn px-5 py-2">Edit</button>
              <button :id="'postShareStoryId'+post.id" :data-postId="post.id" @click="shareStory"  class="w-100 settings_btn px-5 py-2">Share as story</button>
              <button :id="'postDeleteId'+post.id" :data-postId="post.id" @click="deletePost" class="w-100 settings_btn text-danger px-5 py-2 border-0">Delete</button>
            </b-modal> 
          </div>

          <div class="card-body">
            <video v-if="post.media_type == 'localVideo'" controls muted class>
              <source :src="'storage/'+post.media_file"  />
            </video>
            <img  v-else-if="post.media_type == 'imageUrl'" class="card-img-top rounded-0" :src="post.media_file"/>
            <img v-else class="card-img-top rounded-0" :src="'storage/'+post.media_file" />
            <div class="px-2">
              <div class="interaction_buttons pt-2 d-flex">
                <!-- like icon -->
                <svg :id="'postLikeId'+post.id" :data-postId="post.id" @click="likeUnlikePosts" :fill="post.likeColor" aria-label="Like" class="mr-3" height="24" viewBox="0 0 48 48" width="24" >
                  <path :d="post.likePath" />
                </svg>

                <!-- comment icon -->
                  <a :href="'http://localhost:8000/post/'+post.id">
                    <svg :id="'postCommentId'+post.id" :data-postId="post.id" aria-label="Comment" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                      <path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"/>
                    </svg> 
                  </a>
                <!-- direct message icon -->
                <svg aria-label="Share Post" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                  <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"/>
                </svg>

                <!-- save icon -->
                <svg :id="'postSaveId'+post.id" :data-postId="post.id" @click="saveUnsave" aria-label="Save" class="ml-auto" :fill="post.saveColor" height="24" viewBox="0 0 48 48" width="24">
                  <path :d="post.savePath" />
                </svg>
              </div>
              
              <span v-if=" post.likes.length != 0" class="font-weight-bold">{{post.likes.length}} likes</span>
              <span v-if=" post.likes.length == 0" class="font-weight-bold">0 likes</span>
              

              <div class="card-text position-relative">
                <span v-if="post.editState" :id="'cancelPostEditId'+post.id" :data-postId="post.id" @click="cancelEditPostDescription" class="cancel_edit post_cancel text-danger">Cancel</span>
                <span class="description show_more">
                  <span class="username font-weight-bold">{{post.user.username}}</span>
                  <textarea v-if="post.editState"  v-model="post.description" @keydown.enter.exact.prevent 
                    @keyup.enter.exact="submitEdit" :data-postId="post.id" cols="47" rows="5" class="mt-2 editTxt">
                  </textarea>
                  <span v-else>{{post.description}}</span>
                  
                </span>
                <!-- this button shows/hides the description -->
                <span :ref="'showMoreLess'+post.id" class="show_hide" @click="showHideDescription">{{ post.showStatus }}</span>
              </div>
            </div>
          </div>

          <div class="card-footer px-2 py-0">
            <a href class="show_hide" v-if="post.comments.length > 2" >View All {{post.comments.length}} Comments</a>
            <div class="comments_wrapper" v-for="(comment, key) in post.comments.slice(0, 2)" :key="key" @click="showHideComment">
              <div class="d-flex position-relative">
                <span class="comments show_more pr-3">
                  <span class="username font-weight-bold">{{comment.username}}</span>
                  {{comment.content}}
                </span>
                <!-- like icon -->
                <svg :id="'commentLikeId'+comment.id" :data-commentId="comment.id" @click="likeUnlikeComments" :fill="comment.likeColor"  class="comment_like_icon postfeed" aria-label="Like" viewBox="0 0 48 48" >
                  <path :d="comment.likePath"></path>
                </svg>
              </div> 
            </div>
            <span class="text-secondary">{{post.created_at}}</span>
          </div>
        </div>
      </div>
      <!-- on 'intersect' event trigger, apply 'intersected' function -->
      <observer v-on:intersect="postIntersected" />
    </div>

    <div class="welcome follow_suggestions text-center mb-0" :class="user_display">
      <h5>Welcome to Instaclone</h5>
      <p class="welcome_message mx-4 my-0">Follow people to start seeing the photos and videos they share.</p>
    </div>

    
    <!-- <div  class="d-flex">
      <slick ref="slick" :options="slickOptions" v-for="(user, key) in userFeed" :key="key" ><span>1</span></slick>
    </div>
     -->
    <slick ref="slick" :options="slickOptions"  v-for="(page, key) in slickForLoop " :key="key" :class="user_display" class="pt-3 pb-5 follow_suggestions d-flex justify-content-center align-items-center">
      
      <div v-for="(user, key) in userFeed" :key="key" class="user_wrapper py-2 px-1 position-relative">
        <!-- on 'intersect' event trigger, apply 'intersected' function -->
        <observer class="position-absolute" :id="'slickOsberver'+user.id" :data-slickIndex="key" v-on:intersect="reInit" />
        <div class="card py-2 px-1 border-0 mx-1">

          <div class="card-head d-flex flex-column align-items-center justify-content-center" >
            <img v-if="user.pfp_type == 'imageUrl'" class="suggestion_pfp rounded-circle"  :src="user.pfp"/>
            <img v-else class="suggestion_pfp rounded-circle"  :src="'storage/'+user.pfp"/>
            <span class="suggestion_username font-weight-bold">{{user.username}}</span>
            
          </div>

          <div class="card-body">
            <p  class="suggestion_text">{{user.bio}}</p>
            <div class="d-flex justify-content-center align-items-center image_wrapper">
              <div v-for="(post, key) in user.top_posts.slice(0,3)" :key="key" class="image_div">
                <img v-if="post.media_type == 'imageUrl'" class="suggestion_images rounded-0"  :src="post.media_file"/>
                <img v-else-if="post.media_type == 'localImage'"  class="suggestion_images rounded-0"   :src="'storage/'+post.media_file"/>
              </div>
            </div>
            
          </div>

          <div class="card-footer">
            <span class="suggestion_text">Instaclone recommended</span>

            <button :id="'userId'+user.id" :data-userId="user.id" @click="followUnfollow" class="btn btn-primary" v-text="followUnfollowHtml"></button>
          </div>
                  
        </div>
        
      </div>
      
    </slick>
    
    <b-modal id="modal_post_form" ref="modal_post_form" class="settings_Modal" title="Add a Post" hide-footer>
      <form class="addPost_form" @submit.prevent="addPost" method="post" action="" enctype="multipart/form-data">
        <b-form-group>
          <div class="form-group">
            <input type="hidden" name="type" v-model="postForm.mediaType">
            <cld-image v-if="postForm.publicId" dpr="auto" responsive="width" width="auto" crop="scale" :publicId="postForm.publicId" class="my-2">
              <cld-transformation border="3px_solid_rgb:00390b" radius="20" />
              <cld-transformation quality="auto" fetchFormat="auto" />
            </cld-image>
            <!-- <b-form-file v-model="postForm.postMedia" @change="postPreview" name="media_file" id="file-default"></b-form-file> -->
            <!-- <div class="previewWrapper d-flex justify-content-center align-items-center ">
              <i class="fas fa-cloud-upload-alt"></i>
              <div>
                <video class="video img" v-if="postForm.videoPreview" controls muted>
                  <source :src="postForm.videoPreview"/>
                </video>
              </div>
              <div>
                <img ref="imagePrev" class="preview img" v-if="postForm.imgPreview" :src="postForm.imgPreview"/>
              </div>
            </div> -->
            <!-- <ImgCropper></ImgCropper> -->
            <b-form-select v-model="postForm.mediaType" :options="postForm.options" size="sm" class="my-2"></b-form-select>
            <b-form-textarea v-model="postForm.postCaption" name="description" placeholder="Add a caption ..."  cols="30" rows="1" max-rows="10" class="form-control addPostTxt my-2"></b-form-textarea>
            <button type="submit" class="btn btn-primary float-right">Submit</button>
          </div>
        </b-form-group>
      </form>
    </b-modal> 
    
  </div>
</template>

<script>
import Slick from 'vue-slick';
import Observer from "./Observer";
import VueAvatar from '../vue-avatar-editor/src/components/VueAvatar.vue'
import VueAvatarScale from '../vue-avatar-editor/src/components/VueAvatarScale.vue'

// import ImgCropper from "ImgCropper";
var moment = require("moment");

// require('readmore-js');
// var sortedObjs = _.sortBy( [23,246,14354,67543,2345,12,1,3,5,], function(num) {return num;} );
// console.log(sortedObjs.reverse());

export default {
  // props:{
  //   src:'storage/uploads/2ik06RaJYry8xcJXAW3cf9GWSbON3SaoIiHKjjUb.jpeg',
  // },
  data() {
    return {
      // General Data
        slide: 0,
        sliding: null,
        publicPath: 'http://localhost:8000/',
        observer: null,
        sessionUser: null,

      // Post Data
        feedKey: 0,
        postFeed: [],
        posts: [],
        postsType: [],
        postForm: {
          postMedia: null,
          publicId: null,
          imgPreview: null,
          videoPreview: null,
          postCaption: null,
          urlImg:null,
          mediaType: null,
          options: [
            { value: null , text: 'Select an option' },
            { value: 'post', text: 'Publish as a Post' },
            { value: 'story', text: 'Publish as a Story' },
          ]
        },
        postSliceIndex: 10,      
        postIterations: 0,
        likedPosts: [],
        savedPosts: [],


      // Comment Data
        commentCount: 0,
        likedComments: [],

      // Story Data
        storyUsers: [],
        storiesType: [],
        storyFeed: [],
        storySliceIndex: 10,
        storyIterations: 0,


      // User Data
        users: [],
        userFeed: [],
        slickForLoop: [],
        post_display: '',
        user_display: '',
        followedUsers: [],
        followUnfollowHtml:null,
        userSliceIndex: 10,
        userIterations: 0,
        userSlickIterations: 0,
        userTarget: 7,
      

      // Slick Options
        slickOptions: {
          slidesToShow: 'auto',
          slidesToScroll: 1,
          arrows: false,
          slickSetOption: true,
          autoplay: false,
          autoplaySpeed: 3000,
          centerMode: false,
          infinite: false,
          responsive: [

            // cards needs to be bigger through media queries

              // {
              // breakpoint: 1024,
              // settings: {
              //   centerMode: true,
              //   centerPadding: '40px',
              //     slidesToShow: 3,
              //     slidesToScroll: 3,
              //     infinite: true,
              // }
              // },
              // {
              // breakpoint: 900,
              // settings: {
              //   centerMode: true,
              //   centerPadding: '60px',
              //     slidesToShow: 4,
              //     slidesToScroll: 4
              // }
              // },
              // {
              // breakpoint: 800,
              // settings: {
              //   centerMode: true,
              //   centerPadding: '60px',
              //     slidesToShow: 3,
              //     slidesToScroll: 3
              // }
              // },
              // {
              // breakpoint: 700,
              // settings: {
              //   centerMode: true,
              //   centerPadding: '10px',
              //     slidesToShow: 3,
              //     slidesToScroll: 3
              // }
              // },
              {
              breakpoint: 600,
              settings: {
                centerMode: true,
                centerPadding: '80px',
                  slidesToShow: 2,
                  slidesToScroll: 2
              }
              },
              {
              breakpoint: 560,
              settings: {
                centerMode: true,
                centerPadding: '65px',
                  slidesToShow: 2,
                  slidesToScroll: 2
              }
              },
              {
              breakpoint: 520,
              settings: {
                centerMode: true,
                centerPadding: '50px',
                  slidesToShow: 2,
                  slidesToScroll: 2
              }
              },
              {
              breakpoint: 480,
              settings: {
                centerMode: true,
                centerPadding: '120px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              },
              {
              breakpoint: 430,
              settings: {
                centerMode: true,
                centerPadding: '105px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              },
              {
              breakpoint: 400,
              settings: {
                centerMode: true,
                centerPadding: '90px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              },
              {
              breakpoint: 370,
              settings: {
                centerMode: true,
                centerPadding: '75px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              },
              {
              breakpoint: 340,
              settings: {
                centerMode: true,
                centerPadding: '60px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              },
              {
              breakpoint: 310,
              settings: {
                centerMode: true,
                centerPadding: '50px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              },
              {
              breakpoint: 290,
              settings: {
                centerMode: true,
                centerPadding: '45px',
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
              }
          ]
      
            // Any other options that can be got from plugin documentation
        },
    };
  },

  created: function () {
    axios
      .get("posts")
      .then((data) => { 
        this.sessionUser = data.data[0].session_user 
        this.likeUnlikePosts = _.debounce(this.likeUnlikePosts, 300)
        this.likeUnlikeComments = _.debounce(this.likeUnlikeComments, 300)
        this.saveUnsave = _.debounce(this.saveUnsave, 300)
        this.followUnfollow = _.debounce(this.followUnfollow, 300)
        this.likedPosts.push(...this.sessionUser.liked.posts);
        
        
        if (this.sessionUser.favorites !== null) {
          this.savedPosts.push(...this.sessionUser.favorites);
        }
        if (this.sessionUser.followed !== null) {
          this.followedUsers.push(...this.sessionUser.followed);        
        }

        if (typeof data.data[0].username == "undefined") {
          this.likedComments.push(...this.sessionUser.liked.comments);
          this.user_display = "d-none";
          this.posts = data.data;
          this.storyUsers.push(...this.posts[0]['story_users'])
          this.storyUsers.forEach(storyUser => {
            storyUser.stories = []
          })
                
          this.posts.forEach((post) => {
            post.editState = false
            if (post.likes == null) {
              post.likes = []
            }
            post.created_at = moment(post.created_at).fromNow();
            post.showStatus = "Show More";
            if (this.likedPosts.includes(post.id)) {
              post.likePath =
                "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              post.likeColor = "#ed4956";
            } else {
              post.likePath =
                "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              post.likeColor = "#262626";
            }

            if (this.savedPosts.includes(post.id)) {
              post.savePath =
                "M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z";
              post.saveColor = "#ffbb00";
            } else {
              post.savePath =
                "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
              post.saveColor = "#262626";
            }

            if (post.type == "post" ) {
              this.postsType.push(post);
            } else if (post.type == "story") {
              this.storiesType.push(post);
            } else if (post.type == "post/story" || post.type == "story/post") {
              this.postsType.push(post);
              this.storiesType.push(post);
            }

            if (post.type == "story" || post.type == "post/story" || post.type == "story/post") {
              this.storyUsers.forEach(storyUser => {
                if (storyUser.id == post.user.id) {
                  storyUser.stories.push(post)
                }
              });
            }
            
            

            post.comments.forEach(comment => {
              if (this.likedComments.includes(comment.id)) {
              comment.likePath =
                "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              comment.likeColor = "#ed4956";
            } else {              
              comment.likePath =
                "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              comment.likeColor = "#262626";
            }

            });
          });

          this.postFeed.push(this.postsType.slice(0, 10));
          this.storyFeed.push(this.storyUsers.slice(0, 10));

       

        } else {
          this.post_display = "d-none";
          this.users = data.data;

          this.users.forEach(user => {
            if (user.top_posts.length == 0) {
              user.top_posts.push(
                {},{},{}
                )
            } 
            else if (user.top_posts.length == 1) {
                user.top_posts.push(
                {},{}
                )
              
            } else if (user.top_posts.length == 2) {
                user.top_posts.push(
                {}
                )
            }

            if (this.followedUsers.includes(user.id)) {
              this.followUnfollowHtml = "Unfollow"          
            } else {
              this.followUnfollowHtml = "Follow"         
            }
            
          });

          this.userFeed.push(...this.users.slice(0, 10));

          // to fire the slick forloop once
          this.slickForLoop=[1]
          
        }
        
      })
      .catch((err) => {});
  },

  updated: function () {
    axios
      .get("posts")
      .then((data) => {  
        if (typeof data.data[0].username == "undefined") {
          
          this.posts = data.data;
          
        } else {
          
          this.users = data.data;
          
        }
        
      })
      .catch((err) => {});
  },

  mounted: function () {
    this.$store.commit("changeState", true);

    var widget = cloudinary.createUploadWidget( 
    { cloudName: "resize", uploadPreset: "resize_preset", cropping: true },
     (error, result) => { 
         this.postForm.postMedia = result
         if (typeof this.postForm.postMedia.info.files != 'undefined') {
          this.postForm.publicId = this.postForm.postMedia.info.files[0].uploadInfo.public_id
          this.postForm.fileName = this.postForm.postMedia.info.files[0].name
          this.postForm.urlImg = this.postForm.postMedia.info.files[0].uploadInfo.url
          this.$bvModal.show('modal_post_form')
         }
        
        //  console.log(this.postForm.postMedia.info.files[0].uploadInfo.public_id);
          // this.publicId = result.info.uploadInfo.publicId
      
     });

    $('#openWidget').click(function() {
      widget.open();
    });
    // THIS.REFS.SHOWMORELESS NOT ACCESSIBLE
    //  console.log(this.postFeed);            
    //     console.log(this.$refs);
    //     console.log(this.$refs['modal_post_form']);

    //     this.postFeed.forEach(page => {
    //       page.forEach(post => {
    //       console.log(this.$refs['showMoreLess3']);
    //       console.log('showMoreLess'+post.id);

    //       });
    //     });
    this.posts.forEach((post) => {
            if (this.likedPosts.includes(post.id)) {
              post.likePath =
                "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              post.likeColor = "#ed4956";
            } else {
              post.likePath =
                "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              post.likeColor = "#262626";
            }

            if (this.savedPosts.includes(post.id)) {
              post.savePath =
                "M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z";
              post.saveColor = "#ffbb00";
            } else {
              post.savePath =
                "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
              post.saveColor = "#262626";
            }

           
          });
          
  },

  methods: {
    
    // by default vues js @click has an event parameter from which we can access elements
    showHideDescription(event) {
      // check if the inner text of the element clicked equals 'show more'
      if ($(event.target).html() == "Show More") {
        // replace it by 'show less'
        $(event.target).html("Show Less");

        //  remove the 'show_more' class from the sibling 'description'
        $(event.target.previousElementSibling).toggleClass("show_more");

        // check if the inner text of the element clicked equals 'show less'
      } else if ($(event.target).html() == "Show Less") {
        // replace it by 'show more'
        $(event.target).html("Show More");

        //  add the 'show_more' class from the sibling 'description'
        $(event.target.previousElementSibling).toggleClass("show_more");
      }
    },

    // by default vues js @click has an event parameter from which we can access elements
    showHideComment(event) {
      // check if the element clicked has the class 'show more'
      if ($(event.target).hasClass("show_more")) {
        // remove the class
        $(event.target).removeClass("show_more");
      }
    },

    postIntersected() {
      //count how many pages (a page has 10 posts) of posts we have then execute
      // the code inside that many times
      // it considers 5<5 true ???

      if (this.postIterations < Math.floor(this.postsType.length / 10)) {
        // push the next page ( of 10 posts) into the array feed
        this.postFeed.push(this.postsType.slice(this.postSliceIndex, this.postSliceIndex + 10));

        // increment the slice index to get the next page on the next iteration
        this.postSliceIndex += 10;

        // increment the iteration
        this.postIterations++;

        // console.log('iteration :'+this.iterations);
        // console.log('limit :'+Math.ceil(this.posts.length / 10));
        // console.log(this.iterations < Math.ceil(this.posts.length / 10));
        
      }

      
      
    },

    reInit() {
      if (this.userSlickIterations == this.userTarget  ) {
        this.userTarget +=10
        if (this.userIterations < Math.floor(this.users.length / 10)) {
        // push the next page ( of 10 users) into the array feed
        this.userFeed.push(...this.users.slice(this.userSliceIndex, this.userSliceIndex + 10))

        // increment the slice index to get the next page on the next iteration
        this.userSliceIndex += 10

        // // increment the iteration
        this.userIterations++

        
        
        
      }
      let currIndex = this.$refs.slick[0].currentSlide()
      this.$refs.slick[0].destroy();
      this.$nextTick(() => {
        this.$refs.slick[0].create();
        let slickElement = this.$refs.slick[0];
        slickElement.goTo(currIndex, true);

      });
      }
      
      this.userSlickIterations++
    },

    userIntersected () {
      
      if (this.userIterations < Math.floor(this.users.length / 10)) {
        // push the next page ( of 10 users) into the array feed
        this.userFeed.push(...this.users.slice(this.userSliceIndex, this.userSliceIndex + 1));

        // increment the slice index to get the next page on the next iteration
        this.userSliceIndex += 1;

        // increment the iteration
        this.userIterations++;
       
   
        
      // console.log(this.$refs);
        // console.log('iteration :'+this.iterations);
        // console.log('limit :'+Math.ceil(this.posts.length / 10));
        // console.log(this.iterations < Math.ceil(this.posts.length / 10));
        
        
      }
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

    likeUnlikePosts(event) {

      let postLikeId
      let dataPostId
      

      if (typeof $(event.target).attr("id") == 'undefined') {
        postLikeId = $(event.target.parentElement).attr("id");
        dataPostId = $(event.target.parentElement).attr("data-postId");
      } else {
        postLikeId = $(event.target).attr("id");
        dataPostId = $(event.target).attr("data-postId");
      }
        //  check if the post is already liked by the user
        if (this.likedPosts.includes(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue))) {
            // apply the laravel unlike function
            axios
              .get("posts/" +$("#" + postLikeId)[0].attributes[1].nodeValue +"/" +this.$sessionUser.id +"/unlike")
              .then((response) => {
                if (this.likedPosts.includes(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue))) {

                  // get the index of the post id we want to delete
                  let index = this.likedPosts.indexOf(
                    parseInt($("#" + postLikeId)[0].attributes[1].nodeValue)
                  );
                  //  remove it from the likedPosts array
                  this.likedPosts.splice(index, 1);
          
                  $("#" + postLikeId)[0].attributes[2].nodeValue = "#262626";
                  $("#" + postLikeId)[0].firstChild.attributes[0].nodeValue =
                    "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
                  
                  this.postFeed.forEach(postPage => {
                    postPage.forEach(post => {
                        if (post.id == dataPostId ) {
                          let index = post.likes.indexOf(this.sessionUser.id)
                          post.likes.splice(index, 1)
                        } 
                    });
                  });
                }

              });
        } else {
          // apply the laravel like function
          axios
            .get("posts/" + $("#" + postLikeId)[0].attributes[1].nodeValue + "/" + this.$sessionUser.id + "/like")
            .then((response) => {
              if (!this.likedPosts.includes(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue))) {
                // add the post id to the likedPosts array
                this.likedPosts.push(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue));
                $("#" + postLikeId)[0].attributes[2].nodeValue = "#ed4956";
                $("#" + postLikeId)[0].firstChild.attributes[0].nodeValue =
                  "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";

                this.postFeed.forEach(postPage => {
                  postPage.forEach(post => {
                      if (post.id == dataPostId ) {
                        post.likes.push(this.sessionUser.id)
                      } 
                  });
                });
              }

            });

        }
    },

    likeUnlikeComments(event) {

      let commentLikeId
      let dataPostId
      let dataCommentId
      

      if (typeof $(event.target).attr("id") == 'undefined') {
        commentLikeId = $(event.target.parentElement).attr("id");
        dataPostId = $(event.target.parentElement).attr("data-postId");
        dataCommentId = $(event.target.parentElement).attr("data-commentId");
      } else {
        commentLikeId = $(event.target).attr("id");
        dataPostId = $(event.target).attr("data-postId");
        dataCommentId = $(event.target).attr("data-commentId");
      }
        //  check if the post is already liked by the user
        if (this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {
            // apply the laravel unlike function
            axios
              .get("post/comments/" +$("#" + commentLikeId)[0].attributes[1].nodeValue +"/" +this.$sessionUser.id +"/unlike")
              .then((response) => {
                if (this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {

                  // get the index of the post id we want to delete
                  let index = this.likedComments.indexOf(
                    parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
                  );
                  //  remove it from the this.likedComments array
                  this.likedComments.splice(index, 1);
          
                  $("#" + commentLikeId)[0].attributes[2].nodeValue = "#262626";
                  $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
                    "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
                  
                  this.postFeed.forEach(postPage => {
                    postPage.forEach(post => {
                        if (post.comments.length > 0) {
                          post.comments.forEach(comment => {
                            if (comment.id == dataCommentId ) {
                              let index = comment.likes.indexOf(this.sessionUser.id)
                              comment.likes.splice(index, 1)
                            }
                          });
                        }
                    });
                  });
                }

              });
        } else {
          // apply the laravel like function
          axios
            .get("post/comments/" + $("#" + commentLikeId)[0].attributes[1].nodeValue + "/" + this.$sessionUser.id + "/like")
            .then((response) => {
              if (!this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {
                // add the post id to the this.likedComments array
                this.likedComments.push(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue));
                $("#" + commentLikeId)[0].attributes[2].nodeValue = "#ed4956";
                $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
                  "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";

                this.postFeed.forEach(postPage => {
                  postPage.forEach(post => {
                      if (post.comments.length > 0) {
                        post.comments.forEach(comment => {
                          if (comment.id == dataCommentId ) {
                            comment.likes.push(this.sessionUser.id)
                          }
                        });
                      }
                  });
                });
              }

            });

        }
    },

    followUnfollow(event) {
      let userFollowId;

      if (typeof $(event.target).attr("id") == 'undefined') {
        userFollowId = $(event.target.parentElement).attr("id");
      } else {
        userFollowId = $(event.target).attr("id");
      }

      //  check if the post is already liked by the user
      if (this.followedUsers.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))) {
          // apply the laravel unlike function
          axios
            .get(
              "users/" +
                $("#" + userFollowId)[0].attributes[1].nodeValue +
                "/" +
                this.sessionUser.id +
                "/unfollow"
            )
            .then((response) => {
              
              // get the index of the user id we want to delete
              let index = this.followedUsers.indexOf(
                parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
              );

              if (this.followedUsers.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                //  remove it from the followedUsers array
                this.followedUsers.splice(index, 1)
                $("#" + userFollowId)[0].innerHTML = 'Follow' 
               
              }
              
              
            });
      } else {
        // apply the laravel follow function
        axios
          .get("users/" + $("#" + userFollowId)[0].attributes[1].nodeValue + "/" + this.$sessionUser.id + "/follow" )
          .then((response) => {
            
            if (!this.followedUsers.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                // add the user id to the followedUsers array
                this.followedUsers.push(
                  parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                )
                $("#" + userFollowId)[0].innerHTML = 'Unfollow' 
                               

              }
            
          });
                 
         
          
      }
    },

    saveUnsave(event) {
      let postSaveId; 


      if (typeof $(event.target).attr("id") == 'undefined') {
        postSaveId = $(event.target.parentElement).attr("id");
      } else {
        postSaveId = $(event.target).attr("id");
      }

      //  check if the post is already saved by the user
      if ( this.savedPosts.includes( parseInt($("#" + postSaveId)[0].attributes[1].nodeValue))) {
        // apply the laravel unSave function
        axios.get("posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.$sessionUser.id + "/unfavorite")
          .then((response) => {
            if ( this.savedPosts.includes( parseInt($("#" + postSaveId)[0].attributes[1].nodeValue))){
              // get the index of the post id we want to delete
              let index = this.savedPosts.indexOf(
                parseInt($("#" + postSaveId)[0].attributes[1].nodeValue)
              );

              //  remove it from the savedPosts array
              this.savedPosts.splice(index, 1);
              $("#" + postSaveId)[0].attributes[3].nodeValue = "#262626";
              $("#" + postSaveId)[0].firstChild.attributes[0].nodeValue =
                "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
              
            }
          });
      } else {
        // apply the laravel Save function
        axios.get("posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.$sessionUser.id + "/favorite")
          .then((response) => {
            if ( !this.savedPosts.includes( parseInt($("#" + postSaveId)[0].attributes[1].nodeValue))){
              // add the post id to the savedPosts array
              this.savedPosts.push(
                parseInt($("#" + postSaveId)[0].attributes[1].nodeValue)
              );
              $("#" + postSaveId)[0].attributes[3].nodeValue = "#ffbb00";
              $("#" + postSaveId)[0].firstChild.attributes[0].nodeValue =
                "M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z";
              
            }
          });

      }
    },

    postPreview(event) {
      const file = event.target.files[0];
      if (file.type.indexOf('video/') !== -1) {
        this.postForm.videoPreview = URL.createObjectURL(file);
      } else {
        this.postForm.imgPreview = URL.createObjectURL(file);
      }
      
      
    },

    forceRerender () {
      this.feedKey += 1;  
    },

    onChangeScale (scale) {
      this.$refs.vueavatar.changeScale(scale)
    },

    saveClicked(){
      var img = this.$refs.vueavatar.getImageScaled()
      // use img
    },

    onImageReady(scale){
      this.$refs.vueavatarscale.setScale(scale)
    },

    /* End Utility function to convert a canvas to a BLOB */
    dataURLToBlob(dataURL) {
        var BASE64_MARKER = ';base64,';
        if (dataURL.indexOf(BASE64_MARKER) == -1) {
            var parts = dataURL.split(',');
            var contentType = parts[0].split(':')[1];
            var raw = parts[1];

            return new Blob([raw], {type: contentType});
        }

        var parts = dataURL.split(BASE64_MARKER);
        var contentType = parts[0].split(':')[1];
        var raw = window.atob(parts[1]);
        var rawLength = raw.length;

        var uInt8Array = new Uint8Array(rawLength);

        for (var i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }

        return new Blob([uInt8Array], {type: contentType});
    },
     /* A Blob() is almost a File() - it's just missing the two properties below which we will add https://stackoverflow.com/questions/27159179/how-to-convert-blob-to-file-in-javascript */ 
    blobToFile(theBlob, fileName) {
      fileName= this.$store.state.image.srcFile.name
      // theBlob.lastModifiedDate = new Date();
      // theBlob.name = fileName;
      var theFile = new File([theBlob], fileName, {lastModified: new Date()});
      return theFile;
    },

    addPost(event) {
      
      // let Blob = this.dataURLToBlob(this.$store.state.image.croppedImage) 
      // this.postForm.postMedia = this.blobToFile(Blob)  
      let formData = new FormData();
      formData.append('media_file', this.postForm.urlImg);
      formData.append('description', this.postForm.postCaption);
      formData.append('type', this.postForm.mediaType);
      formData.append('user_id', this.sessionUser.id);
          
      axios({
        method: 'post',
        url: 'posts',
        data: formData,
      }).then((response) => {
        let post = response.data
        post.likes = []
        post.created_at = moment(post.created_at).fromNow();
        post.showStatus = "Show More";
        post.likePath = "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
        post.likeColor = "#262626";
        post.savePath = "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
        post.saveColor = "#262626";
      
        this.$refs['modal_post_form'].hide()
        this.postFeed[0].splice(0, 0, post)

        this.postForm.imgPreview = null;
        this.postForm.videoPreview = null;
        this.postForm.postCaption = ''

      }).catch((err) => {
        console.log("ERRRR:: ",err.response.data);

      });

      
    },

    deletePost (event) {
      var targetId = event.target.attributes[1].nodeValue;
      axios({
        method: 'delete',
        url: 'posts/'+targetId,
        data: {
          'type':'post'
        },
      }).then((response) => {
        this.postFeed.forEach(page => {
          page.forEach(post => {
            if (targetId == post.id) {
              let index = page.indexOf(post)
              page.splice(index, 1)
            }
          });
          
        });
        
        $(this.$refs)[0]['my_postModal'+targetId][0].hide()         
      })
    },

    editPostDescription(event) {
      var targetId = event.target.attributes[1].nodeValue
      this.postFeed.forEach(page => {
        page.forEach(post => {
          if (post.id == targetId) {
            post.editState = true
          } 
        });
      });
      $(this.$refs)[0]['my_postModal'+targetId][0].hide() 
      this.forceRerender()
    },

    cancelEditPostDescription(event) {
      var targetId = event.target.attributes[1].nodeValue
      this.postFeed.forEach(page => {
        page.forEach(post => {
          if (post.id == targetId) {
            post.editState = false
            
          } 
        });
      });
      $(this.$refs)[0]['my_postModal'+targetId][0].hide() 
      this.forceRerender()
    },

    submitEdit (event) {
      var targetId = event.target.attributes[0].nodeValue
      var updatedPost = $(event.target)[0].value
      axios({
        method: 'patch',
        url: 'posts/'+targetId,
        data: {
          description: updatedPost,
          },
      }).then((response) => {
        this.postFeed.forEach(page => {
          page.forEach(post => {
            if (post.id == targetId) {
              post.editState = false
              
            } 
          });
        });
        this.forceRerender()
      })
    },

    shareStory (event) {
      var targetId = event.target.attributes[1].nodeValue
      var updatedType = 'post/story'
      axios({
        method: 'patch',
        url: 'posts/'+targetId,
        data: {
          type: updatedType,
          },
      }).then((response) => {
        $(this.$refs)[0]['my_postModal'+targetId][0].hide() 
      })



    },

    storyHref (event) {
      var targetId = event.target.attributes[0].nodeValue
      window.location.replace(this.publicPath+'story/'+targetId);   
      
      // console.log('dfqsdfsd');
    }
  },


  components: {
    Observer,
    Slick,
    VueAvatar,
    VueAvatarScale
  },
};
</script>