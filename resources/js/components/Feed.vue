<template>
  <div key="feedKey" :class="`h-100 mt-1 ${sessionUser.followed.length != 0 ? 'mb-5' : ''}`">
    <div v-if="sessionUser.followed.length != 0" class="feed_content flex justify-between pb-12 sm:pb-5 text-xs mx-auto sm:w-3/4 lg:text-sm  ">
      <div class="w-full md:w-8/12 lg:w-7/12 xl:w-8/12 ">
        <storyGlider 
        :sessionUser="sessionUser" 
        :storyFeed="storyFeed" 
        :storyUsers="storyUsers" 
        v-if="posts.length > 0" 
        
        :widget="widget">
        </storyGlider>
        
        <div  class="feed_wrapper" v-for="(page, key) in postFeed" :key="key" >
          
          <div class="post_wrapper pt-2" v-for="(post, key) in page" :key="key">
            <div class="card rounded-0">
              <div class="card-head d-flex align-items-center border-down py-1">
                <img @click="goToProfile" :data-userId="post.user.id" class="pfp  rounded-circle mr-2" :src="post.user.pfp"/>
                <span @click="goToProfile" :data-userId="post.user.id" class="username font-weight-bold">{{post.user.username}}</span>
                <i class="fas fa-ellipsis-h text-secondary ml-auto mr-2 pt-2" v-b-modal="'my_postModal'+post.id"></i>
                <b-modal modal-class="settings_Modal" :id="'my_postModal'+post.id" :ref="'my_postModal'+post.id"  hide-header hide-footer centered>
                  <button :id="'postEditId'+post.id" :data-postId="post.id" @click="editPostDescription"  class="w-100 settings_btn px-5 py-2">Edit</button>
                  <button :id="'postShareStoryId'+post.id" :data-postId="post.id" @click="shareStory"  class="w-100 settings_btn px-5 py-2">Share as story</button>
                  <button :id="'postDeleteId'+post.id" :data-postId="post.id" @click="deletePost" class="w-100 settings_btn text-danger px-5 py-2 border-0">Delete</button>
                </b-modal> 
              </div>

              <div class="card-body">
                <router-link :to="'post/'+post.id">
                  <img  v-if="post.media_type == 'image'" class=" rounded-0" :src="post.media_file"/>
                  <video v-else-if="post.media_type == 'video'" class="post_feed_video" controls muted>
                    <source :src="post.media_file"  />
                  </video>   
                </router-link>
                
                <div class="px-2">
                  <div class="interaction_buttons pt-2 d-flex">
                    <!-- like icon -->
                    <div class="w-10">
                    <svg :id="'postLikeId'+post.id" :data-postId="post.id" @click="likeUnlikePosts" :fill="post.likeColor" aria-label="Like" class="mr-3" height="24" viewBox="0 0 48 48" width="24" >
                      <path :d="post.likePath" />
                    </svg>
                    </div>

                    <!-- comment icon -->
                      <router-link @click="jelloAnimation" :to="'post/'+post.id">
                        <svg :id="'postCommentId'+post.id" :data-postId="post.id" aria-label="Comment" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                          <path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"/>
                        </svg> 
                      </router-link>
                    <!-- direct message icon -->
                    <svg v-b-modal="'my_sharePostModal'+post.id" aria-label="Share Post" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                      <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"/>
                    </svg>

                    <b-modal :id="'my_sharePostModal'+post.id" :ref="'my_sharePostModal'+post.id"  modal-class="sharePost_Modal"  hide-header hide-footer >
                        <ul class="sharePostUl position-relative">
                          <span  @click="$bvModal.hide('my_sharePostModal'+post.id)" class="close_icon"></span> 
                          <searchComponent :users="followedUsers" :post="post"></searchComponent>
                        </ul>
                    </b-modal> 

                    <!-- save icon -->
                    <svg :id="'postSaveId'+post.id" :data-postId="post.id" @click="saveUnsave" aria-label="Save" class="ml-auto" :fill="post.saveColor" height="24" viewBox="0 0 48 48" width="24">
                      <path :d="post.savePath" />
                    </svg>
                  </div>
                  
                  <span v-if=" post.likes.length != 0" class="font-weight-bold">{{post.likes.length}} likes</span>
                  <span v-if=" post.likes.length == 0" class="font-weight-bold">0 likes</span>
                  

                  <div class="card-text position-relative">
                    <span v-if="post.editState" :id="'cancelPostEditId'+post.id" :data-postId="post.id" @click="cancelEditPostDescription" class="cursor-pointer cancel_edit post_cancel text-danger">Cancel</span>
                    <span class="description show_more">
                      <span @click="goToProfile" :data-userId="post.user.id" class="username font-weight-bold">{{post.user.username}}</span>
                      <textarea v-if="post.editState"  v-model="post.description" @keydown.enter.exact.prevent 
                        @keyup.enter.exact="submitEdit" :data-postId="post.id" cols="47" rows="5" class="mt-2 editTxt">
                      </textarea>
                      <span v-else :ref="'postDescription'+post.id" class="post_description" >{{post.description}}</span>
                      
                    </span>
                    <!-- this button shows/hides the description -->
                    <span :ref="'showMoreLess'+post.id" class="show_hide" @click="showHideDescription">{{ post.showStatus }}</span>
                  </div>
                </div>
              </div>

              <div class="card-footer px-2 py-0 bg-white">
                <div class="w-full flex justify-center ">
                  <router-link :to="'post/'+post.id" class="show_hide" v-if="post.comments.length > 2" >View All {{post.comments.length}} Comments</router-link>
                </div>
                <div class="comments_wrapper" v-for="(comment, key) in post.comments.slice(0, 2)" :key="key" @click="showHideComment">
                  <div class="d-flex position-relative">
                    <span class="comments show_more pr-3">
                      <span @click="goToProfile" :data-userId="comment.user_id" class="username font-weight-bold">{{comment.username}}</span>
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
        <b-modal id="modal_post_form" ref="modal_post_form" class="settings_Modal" title="Add a Post" hide-footer centered>
        <validation :errors="validationErrors" v-if="validationErrors"></validation>
        <form class="addPost_form" @submit.prevent="addPost" method="post" action="" enctype="multipart/form-data">
          <b-form-group>
            <div class="form-group d-flex flex-column">
              <input type="hidden" name="type" v-model="postForm.mediaType">
              <cld-image v-if="postForm.resourceType == 'image'" dpr="auto" responsive="width" width="auto" crop="scale" :publicId="postForm.publicId" class="my-2">
                <cld-transformation quality="auto" fetchFormat="auto" />
              </cld-image>
              <video class="video img ml-auto bg-dark" v-else-if="postForm.resourceType == 'video'" controls muted>
                <source :src="postForm.urlImg"/>
              </video>
              <div class="px-2">
                <b-form-select v-model="postForm.mediaType" :options="postForm.options" size="sm" class="mt-2 mb-1"></b-form-select>
                <b-form-textarea v-model="postForm.postCaption" name="description" placeholder="Add a caption ..."  cols="30" rows="1" max-rows="10" class="form-control addPostTxt mb-2"></b-form-textarea>
              </div>
              <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
          </b-form-group>
        </form>
        </b-modal> 
      </div>
      <div class=" search_component_wrapper hidden md:inline-block md:w-4/12 lg:w-5/12 xl:w-4/12">
        <h1 class="text-gray-800 text-xs mt-2 text-center">Suggestions for you :</h1>
        <searchComponent :sessionUser="sessionUser" ></searchComponent>
        <div class="p-3 text-xs text-gray-500" style="font-size: 9px;">
          <div>
            About &#8226; Help &#8226; Press &#8226; API &#8226; Jobs &#8226; Privacy &#8226; Terms &#8226; Locations &#8226; Top Accounts &#8226; Hashtags &#8226; Language &#8226; English

          </div>
          <br>
          <div>
            © 2020 INSTAGRAM FROM FACEBOOK
          </div>

        </div>
      </div>
    </div>
    <followUsers 
    :sessionUser="sessionUser" 
    :userFeed="userFeed" 
    :users="users" 
    :followedUsersId="followedUsersId"  
    :followUnfollowHtml="followUnfollowHtml"
    v-if=" sessionUser.followed.length == 0">
    </followUsers>
  </div>
</template>

<script>
import Slick from 'vue-slick';
import Observer from "./Observer";
import VueAvatar from '../vue-avatar-editor/src/components/VueAvatar.vue';
import VueAvatarScale from '../vue-avatar-editor/src/components/VueAvatarScale.vue';
import ValidationErrors from './ValidationErrors';
import StoryGlider from './StoryGlider';
import FollowUsers from './FollowUsers';
import SearchComponent from './SearchComponent';

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
      widget: '',
      updateFeed: 0,

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
        postSliceIndex: 10,      
        postIterations: 0,
        likedPosts: [],
        savedPosts: [],
         postForm: {
            postMedia: null,
            publicId: null,
            resourceType:null,
            imgPreview: null,
            videoPreview: null,
            postCaption: '',
            urlImg:null,
            mediaType: '',
            options: [
              { value: '' , text: 'Select an option' },
              { value: 'post', text: 'Publish as a Post' },
              { value: 'story', text: 'Publish as a Story' },
            ]
          },
          validationErrors: '',


      // Comment Data
        commentCount: 0,
        likedComments: [],

      // Story Data
        storyUsers: [],
        storiesType: [],
        storyFeed: [],


      // User Data
        users: [],
        userFeed: [],
        followedUsers: [],
        followedUsersId: [],
        followUnfollowHtml:null,      
    }
  },

  // watch: {
  //   '$route': {
  //       handler: 'uploadWidget',
  //       immediate: true
  //   }
  // },


    

  created: function () {
    axios
      .get("posts")
      .then((data) => { 
        this.sessionUser = data.data[0].session_user 
        // this.likeUnlikePosts = _.debounce(this.likeUnlikePosts, 300)
        // this.likeUnlikeComments = _.debounce(this.likeUnlikeComments, 300)
        // this.saveUnsave = _.debounce(this.saveUnsave, 300)
        this.likedPosts.push(...this.sessionUser.liked.posts)
        
        if (this.sessionUser.favorites !== null) {
          this.savedPosts.push(...this.sessionUser.favorites);
        }
        if (this.sessionUser.followed !== null) {
          this.followedUsersId.push(...this.sessionUser.followed);        
        } else {
          this.sessionUser.followed = []
        }

        if (typeof data.data[0].username == "undefined") {
          this.followedUsers.push(...this.sessionUser.followed_users)
          this.likedComments.push(...this.sessionUser.liked.comments);

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

            // We are getting all the posts with type " post "
            if (post.type == "post" || post.type == "post/story" || post.type == "story/post") {
              this.postsType.push(post);
            } 

            // We are getting all the posts with type " story "
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

            if (this.followedUsersId.includes(user.id)) {
              this.followUnfollowHtml = "Unfollow"          
            } else {
              this.followUnfollowHtml = "Follow"         
            }
            
          });

          this.userFeed.push(...this.users.slice(0, 10));

          
        }
      })
      .catch((err) => {});
  },

  updated: function () {
    // this.updatefeed()
  },

  mounted: function () { 
    this.updateFeed =1
    this.$store.commit("changeState", true);
    this.$store.commit("changeHomeIcon", true);

    var widget = cloudinary.createUploadWidget( 
            { 
                cloudName: "resize", 
                uploadPreset: "resize_preset", 
                showAdvancedOptions: true,
                googleApiKey: "<image_search_google_api_key>",
                cropping: true,
                multiple: false,
                sources: [
                  "local",
                  "url",
                  "camera",
                  "image_search",
                  "google_drive",
                  "facebook",
                  "dropbox",
                  "instagram",
                  "shutterstock"
                ],
                defaultSource: "local",
                styles: {
                    palette: {
                        window: "#000000",
                        sourceBg: "#000000",
                        windowBorder: "#8E9FBF",
                        tabIcon: "#FFFFFF",
                        inactiveTabIcon: "#8E9FBF",
                        menuIcons: "#2AD9FF",
                        link: "#08C0FF",
                        action: "#336BFF",
                        inProgress: "#00BFFF",
                        complete: "#33ff00",
                        error: "#EA2727",
                        textDark: "#000000",
                        textLight: "#FFFFFF"
                    },
                    fonts: {
                        default: null,
                        "'Space Mono', monospace": {
                            url: "https://fonts.googleapis.com/css?family=Space+Mono",
                            active: true
                        }
                    }
                }
            },
              (error, result) => { 
                      this.postForm.postMedia = result
                  if (typeof result.info.files != 'undefined') {
                    this.postForm.publicId = result.info.files[0].uploadInfo.public_id
                    this.postForm.resourceType = result.info.files[0].uploadInfo.resource_type
                    this.postForm.fileName = result.info.files[0].name
                    this.postForm.urlImg = result.info.files[0].uploadInfo.url
                    this.$bvModal.show('modal_post_form')
                  }
              

                
                

                  // this.publicId = result.info.uploadInfo.publicId
                      //  console.log(result.info.files[0].uploadInfo.resource_type);

              });
              
    this.widget = widget

    setTimeout(()=>{
      $('.openWidget').click(function() {
            
        widget.open(); 
      });
    },1000)
    

  //   $("#openWidget").addEventListener("click", function(){
  //   widget.open();
  // }, false);
    


    void function $getLines($){    
      function countLines($element){
          var lines          = 0;
          var greatestOffset = void 0;

          $element.find('character').each(function(){
              if(!greatestOffset || this.offsetTop > greatestOffset){
                  greatestOffset = this.offsetTop;
                  ++lines;
              }
          });
          
          return lines;
      }
      
      $.fn.getLines = function $getLines(){
          var lines = 0;
          var clean = this;
          var dirty = this.clone();
          
          (function wrapCharacters(fragment){
              var parent = fragment;
              
              $(fragment).contents().each(function(){                
                  if(this.nodeType === Node.ELEMENT_NODE){
                      wrapCharacters(this);
                  }
                  else if(this.nodeType === Node.TEXT_NODE){
                      void function replaceNode(text){
                          var characters = document.createDocumentFragment();
                          
                          text.nodeValue.replace(/[\s\S]/gm, function wrapCharacter(character){
                              characters.appendChild($('<character>' + character + '</>')[0]);
                          });
                          
                          parent.replaceChild(characters, text);
                      }(this);
                  }
              });
          }(dirty[0]));
          
          clean.replaceWith(dirty);

          lines = countLines(dirty);
          
          dirty.replaceWith(clean);
          
          return lines;
      };
    }(jQuery);

    // $('li').each(function(){console.log($(this).getLines());})
    
    setTimeout(() =>{
      $('.post_description').each(function(){
        if($(this).getLines() < 3) {
          $($(this)[0].parentElement.nextElementSibling).hide()
        }
      })
    }, 5000)


    // need setTimeOut for this.posts to load
    // this.posts.forEach((post) => {
      
    //         if (this.likedPosts.includes(post.id)) {
    //           post.likePath =
    //             "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
    //           post.likeColor = "#ed4956";
    //         } else {
    //           post.likePath =
    //             "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
    //           post.likeColor = "#262626";
    //         }

    //         if (this.savedPosts.includes(post.id)) {
    //           post.savePath =
    //             "M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z";
    //           post.saveColor = "#ffbb00";
    //         } else {
    //           post.savePath =
    //             "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
    //           post.saveColor = "#262626";
    //         }
        
    //       });  
  },

  methods: {

    // uploadWidget(){
    //   var widget = cloudinary.createUploadWidget( 
    //         { 
    //             cloudName: "resize", 
    //             uploadPreset: "resize_preset", 
    //             showAdvancedOptions: true,
    //             googleApiKey: "<image_search_google_api_key>",
    //             cropping: true,
    //             multiple: false,
    //             sources: [
    //               "local",
    //               "url",
    //               "camera",
    //               "image_search",
    //               "google_drive",
    //               "facebook",
    //               "dropbox",
    //               "instagram",
    //               "shutterstock"
    //             ],
    //             defaultSource: "local",
    //             styles: {
    //                 palette: {
    //                     window: "#000000",
    //                     sourceBg: "#000000",
    //                     windowBorder: "#8E9FBF",
    //                     tabIcon: "#FFFFFF",
    //                     inactiveTabIcon: "#8E9FBF",
    //                     menuIcons: "#2AD9FF",
    //                     link: "#08C0FF",
    //                     action: "#336BFF",
    //                     inProgress: "#00BFFF",
    //                     complete: "#33ff00",
    //                     error: "#EA2727",
    //                     textDark: "#000000",
    //                     textLight: "#FFFFFF"
    //                 },
    //                 fonts: {
    //                     default: null,
    //                     "'Space Mono', monospace": {
    //                         url: "https://fonts.googleapis.com/css?family=Space+Mono",
    //                         active: true
    //                     }
    //                 }
    //             }
    //         },
    //           (error, result) => { 
    //                   this.postForm.postMedia = result
    //               if (typeof result.info.files != 'undefined') {
    //                 this.postForm.publicId = result.info.files[0].uploadInfo.public_id
    //                 this.postForm.resourceType = result.info.files[0].uploadInfo.resource_type
    //                 this.postForm.fileName = result.info.files[0].name
    //                 this.postForm.urlImg = result.info.files[0].uploadInfo.url
    //                 this.$bvModal.show('modal_post_form')
    //               }
              

                
                

    //               // this.publicId = result.info.uploadInfo.publicId
    //                   //  console.log(result.info.files[0].uploadInfo.resource_type);

    //           });
              
    //           this.widget = widget

    //           this.widget = widget
    //           $('.openWidget').click(function() {
    //             widget.open(); 
    //           });
    // },
    

    updatefeed() {
      if (this.updateFeed) {
        axios
        .get("posts")
        .then((data) => {  
          this.updateFeed =0
          if (typeof data.data[0].username == "undefined") {
            
            this.posts = data.data;
            
          } else {
            
            this.users = data.data;
            if (this.sessionUser.followed == null) {
              this.sessionUser.followed = []
            }

            
          }
          
        })
        .catch((err) => {});
      }
      
    },
    

    addPost(event) {
      
      // let Blob = this.dataURLToBlob(this.$store.state.image.croppedImage) 
      // this.postForm.postMedia = this.blobToFile(Blob)  
      let formData = new FormData();
      formData.append('media_file', this.postForm.urlImg);
      formData.append('media_type', this.postForm.resourceType);
      formData.append('description', this.postForm.postCaption);
      formData.append('type', this.postForm.mediaType);
      formData.append('user_id', this.sessionUser.id);
      axios({
        method: 'post',
        url: this.publicPath+'posts',
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

      }).catch((error) => {
        if (error.status == 422){
        this.validationErrors = error.data.errors;
        }


      });

      
    },
    
    
    
    // by default @click has an event parameter from which we can access elements
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

    // by default @click has an event parameter from which we can access elements
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

    likeUnlikePosts(event) {
      var target
      var postLikeId
      var dataPostId

      if (typeof $(event.target).attr("id") == 'undefined') {
        target=$(event.target.parentElement)
        postLikeId = $(event.target.parentElement).attr("id");
        dataPostId = $(event.target.parentElement).attr("data-postId");
      } else {
        target=$(event.target)
        postLikeId = $(event.target).attr("id");
        dataPostId = $(event.target).attr("data-postId");
      }
      
        //  check if the post is already liked by the user
      if (this.likedPosts.includes(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue))) {
          // apply the laravel unlike function
          axios
            .get("posts/" +$("#" + postLikeId)[0].attributes[1].nodeValue +"/" +this.sessionUser.id +"/unlike")
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
        
      if (target.hasClass('animate__animated')) {
        target.removeClass('animate__animated animate__bounceIn');  
      }
        target.addClass('reduce_like_animation');  

        // apply the laravel like function
        axios
          .get("posts/" + $("#" + postLikeId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/like")
          .then((response) => {
            if (!this.likedPosts.includes(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue))) {
              // add the post id to the likedPosts array
              this.likedPosts.push(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue));
              $("#" + postLikeId)[0].attributes[2].nodeValue = "#ed4956";
              if (target.hasClass('reduce_like_animation')) {
                target.removeClass('reduce_like_animation');  
              }
              target.addClass('animate__animated animate__bounceIn');  
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
      var target
      var commentLikeId
      var dataPostId
      var dataCommentId
      

      if (typeof $(event.target).attr("id") == 'undefined') {
        target=$(event.target.parentElement)
        commentLikeId = $(event.target.parentElement).attr("id");
        dataPostId = $(event.target.parentElement).attr("data-postId");
        dataCommentId = $(event.target.parentElement).attr("data-commentId");
      } else {
        target=$(event.target)
        commentLikeId = $(event.target).attr("id");
        dataPostId = $(event.target).attr("data-postId");
        dataCommentId = $(event.target).attr("data-commentId");
      }
        //  check if the post is already liked by the user
        if (this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {
            // apply the laravel unlike function
            axios
              .get("post/comments/" +$("#" + commentLikeId)[0].attributes[1].nodeValue +"/" +this.sessionUser.id +"/unlike")
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
          if (target.hasClass('animate__animated')) {
            target.removeClass('animate__animated animate__bounceIn');  
          }
          target.addClass('reduce_comment_like_animation')
          // apply the laravel like function
          axios
            .get("post/comments/" + $("#" + commentLikeId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/like")
            .then((response) => {
              if (!this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {
                // add the post id to the this.likedComments array
                this.likedComments.push(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue));
                $("#" + commentLikeId)[0].attributes[2].nodeValue = "#ed4956";
                if (target.hasClass('reduce_comment_like_animation')) {
                  target.removeClass('reduce_comment_like_animation')
                }
                target.addClass('animate__animated animate__bounceIn');  
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

    saveUnsave(event) {
      var postSaveId; 
      var target

      if (typeof $(event.target).attr("id") == 'undefined') {
        target=$(event.target.parentElement)
        postSaveId = $(event.target.parentElement).attr("id");
      } else {
        target=$(event.target)
        postSaveId = $(event.target).attr("id");
      }

      //  check if the post is already saved by the user
      if ( this.savedPosts.includes( parseInt($("#" + postSaveId)[0].attributes[1].nodeValue))) {
        // apply the laravel unSave function
        axios.get("posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/unfavorite")
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
          if (target.hasClass('animate__animated')) {
            target.removeClass('animate__animated animate__rubberBand');  
          }
        // apply the laravel Save function
        axios.get("posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/favorite")
          .then((response) => {
            if ( !this.savedPosts.includes( parseInt($("#" + postSaveId)[0].attributes[1].nodeValue))){
              // add the post id to the savedPosts array
              this.savedPosts.push(
                parseInt($("#" + postSaveId)[0].attributes[1].nodeValue)
              );
              $("#" + postSaveId)[0].attributes[3].nodeValue = "#ffbb00";
              target.addClass('animate__animated animate__rubberBand');  
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

    /*NOT USED End Utility function to convert a canvas to a BLOB */
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
     /*NOT USED A Blob() is almost a File() - it's just missing the two properties below which we will add https://stackoverflow.com/questions/27159179/how-to-convert-blob-to-file-in-javascript */ 
    blobToFile(theBlob, fileName) {
      fileName= this.$store.state.image.srcFile.name
      // theBlob.lastModifiedDate = new Date();
      // theBlob.name = fileName;
      var theFile = new File([theBlob], fileName, {lastModified: new Date()});
      return theFile;
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
      this.updatefeed()
      // this.forceRerender()
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
      this.$forceUpdate();
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
        this.$forceUpdate();
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

    goToProfile(event) {
      var userId = event.target.attributes[0].nodeValue;
      this.$router.push({path : userId+'/profile'})
      // window.location.replace(this.publicPath+userId+'/profile')
    },

    jelloAnimation(event) {
      $(event.target).addClass('animate__animated  animate__jello animate__slow')
    },

    sendAnimation(event){
      $(event.target).addClass('animate__animated  animate__fadeOutTopRigh animate__slow')
    }

    
  },

  // computed: {
  //     sessionUser() {
  //             return this.$store.getters.getCurrentSessionUser           
  //     }
  //   },
    
  components: {
    Slick,
    Observer,
    VueAvatar,
    VueAvatarScale,
    ValidationErrors,
    StoryGlider,
    FollowUsers,
    SearchComponent,
  },
};
</script>