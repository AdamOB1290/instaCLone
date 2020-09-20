<template>
    <div class="post_comment_wrapper" :key = "commentKey">
      <div class="post-head d-flex align-items-center border-down py-1">
        <img @click="goToProfile" :data-userId="post.user.id" class="pfp  rounded-circle mr-2" :src="post.user.pfp"/>
        <span @click="goToProfile" :data-userId="post.user.id" class="username font-weight-bold">{{post.user.username}}</span>
        <i class="fas fa-ellipsis-h text-secondary ml-auto mr-2 pt-2" v-b-modal="'my_postModal'+post.id"></i>
        <b-modal modal-class="settings_Modal" :id="'my_postModal'+post.id" :ref="'my_postModal'+post.id"  hide-header hide-footer centered >
          <button :id="'postEditId'+post.id" :data-postId="post.id" @click="editPostDescription"  class="w-100 settings_btn px-5 py-2">Edit</button>
          <button :id="'postShareStoryId'+post.id" :data-postId="post.id" @click="shareStory"  class="w-100 settings_btn px-5 py-2">Share as story</button>
          <button :id="'postDeleteId'+post.id" :data-postId="post.id" @click="deletePost" class="w-100 settings_btn text-danger px-5 py-2 border-0">Delete</button>
        </b-modal> 
      </div>
      <div class="post-body" >
        <img  v-if="post.media_type == 'image'" class=" rounded-0" :src="post.media_file"/>
        <video v-else-if="post.media_type == 'video'" class="post_feed_video" controls muted>
          <source :src="post.media_file"  />
        </video>
        <div class="px-2">
          <div class="interaction_buttons pt-2 d-flex">
            <!-- like icon -->
            <svg :id="'postLikeId'+post.id" :data-postId="post.id" @click="likeUnlikePosts" :fill="post.likeColor" aria-label="Like" class="mr-3" height="24" viewBox="0 0 48 48" width="24" data-objectType='posts'>
              <path :d="post.likePath" />
            </svg>

            <!-- comment icon -->
              <svg @click="focusCommentInput" :id="'postCommentId'+post.id" :data-postId="post.id" aria-label="Comment" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                <path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"/>
              </svg> 
           
            <!-- direct message icon -->
            <svg v-b-modal="'my_sharePostModal'+post.id" aria-label="Share Post" class="_8-yf5 mr-3" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
              <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"/>
            </svg>

            <b-modal :id="'my_sharePostModal'+post.id" :ref="'my_sharePostModal'+post.id"  modal-class="sharePost_Modal"  hide-header hide-footer >
                <ul class="sharePostUl position-relative">
                  <span  @click="$bvModal.hide('my_sharePostModal'+post.id)" class="close_icon"></span> 
                  <li v-for="(followedUser, key) in followedUsers" :key="key" class="d-flex justify-content-between">
                    <div  class="" >
                      <img @click="goToProfile" :data-userId="post.user.id" class="pfp  rounded-circle mr-2" :src="followedUser.pfp"/>
                      <span @click="goToProfile" :data-userId="post.user.id" class="username font-weight-bold">{{followedUser.username}}</span>
                    </div>
                    <button :id="'postShareId'+post.id" :data-contactId="followedUser.id" :data-postId="post.id" @click="sendPost" class="btn-primary py-1 px-4  border-0 rounded  h-25 align-self-center">Send</button>
                  </li>
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
            <span v-if="post.editState" :id="'cancelPostEditId'+post.id" :data-postId="post.id" @click="cancelEditPostDescription" class="cancel_edit post_cancel text-danger">Cancel</span>
            <span class="description show_more">
              <span @click="goToProfile" :data-userId="post.user.id" class="username font-weight-bold">{{post.user.username}}</span>
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
      <div class="comments_wrapper" v-for="(commentPage, key) in commentFeed" :key="key">
        <div  v-for="(comment, key) in commentPage" :key="key">
          <div :ref="'comment'+comment.id" :id="'comment'+comment.id" class="comments d-flex position-relative show_more p-2" >
            <img @click="goToProfile" :data-userId="comment.user.id" class="pfp rounded-circle mr-2" :src="comment.user.pfp"/>
           
            <div class="pr-3 pl-1">
              <span @click="goToProfile" :data-userId="comment.user.id" class="username font-weight-bold pb-2">{{comment.user.username}}</span>
              <textarea v-if="comment.editState"  v-model="comment.content" @keydown.enter.exact.prevent 
              @keyup.enter.exact="submitEdit" :data-commentId="comment.id"
               cols="35" rows="5" class="mt-2 editTxt">
              </textarea>
              <span v-else>{{comment.content}}</span>
              <br>
              <span class="text-secondary">{{comment.created_at}}</span>
              <span v-if=" comment.likes.length != 0" class="text-secondary ml-2">{{comment.likes.length}} likes</span>
              <span v-if=" comment.likes.length == 0" class="text-secondary ml-2">0 likes</span>
              <span :id="'commentReplyId'+comment.id" :data-commentId="comment.id" :data-username="comment.user.username" :data-originalCommentId="comment.id" @click="commentReply" class="text-secondary ml-2">Reply</span>
              <i class="fas fa-ellipsis-h text-secondary ml-2"  v-b-modal="'my_commentModal'+comment.id"></i>
              <b-modal :id="'my_commentModal'+comment.id" :ref="'my_commentModal'+comment.id" modal-class="settings_Modal" hide-header hide-footer centered>
                <button :id="'commentEditId'+comment.id" :data-commentId="comment.id" @click="editComment"  class="w-100 settings_btn px-5 py-2">Edit</button>
                <button :id="'commentDeleteId'+comment.id" :data-commentId="comment.id" @click="deleteComment" class="w-100 settings_btn text-danger px-5 py-2 border-0" >Delete</button>
              </b-modal>
              
            </div>
            <span v-if="comment.editState" :id="'cancelCommentEditId'+comment.id" :data-commentId="comment.id" @click="cancelEditComment" class="cancel_edit text-danger ml-2">Cancel</span>
            <!-- like icon -->
            <svg :id="'commentLikeId'+comment.id" :data-commentId="comment.id" @click="likeUnlikeComments" :fill="comment.likeColor"  class="comment_like_icon commentfeed" aria-label="Like" viewBox="0 0 48 48" data-objectType='post/comments'>
              <path :d="comment.likePath"></path>
            </svg>
          </div>
          <div v-if="comment.replies.length > 0" class="text-secondary text-center" @click="Expand_Collapse">View Replies</div>
          <div v-if="comment.replies.length > 0" class="reply_wrapper mx-4" :class="reply_display">
            <div  v-for="(reply, key) in comment.replyFeed" :key="key">
                  <div class="replys d-flex position-relative show_more p-2">
                    <img class="pfp rounded-circle mr-2" :src="reply.user.pfp"/>
                  <div>
                  <span class="username font-weight-bold pb-2">{{reply.user.username}}</span>
                  <textarea  v-if="reply.editState" v-model="reply.content" @keydown.enter.exact.prevent 
                  @keyup.enter.exact="submitEdit" :data-commentId="reply.id" 
                  cols="30" rows="5" class="mt-2 editTxt">
                  </textarea>
                  <span v-else>{{reply.content}}</span>
                  <br>
                  <span class="text-secondary">{{reply.created_at}}</span>
                  <span v-if=" reply.likes.length != 0" class="text-secondary ml-2">{{reply.likes.length}} likes</span>
                  <span v-if=" reply.likes.length == 0" class="text-secondary ml-2">0 likes</span>
                  <span :id="'commentReplyId'+comment.id" :data-commentId="reply.id" :data-username="reply.user.username" :data-originalCommentId="reply.original_comment_id" @click="commentReply" class="text-secondary ml-2">Reply</span>
                  <i class="fas fa-ellipsis-h text-secondary ml-2"  v-b-modal="'my_replyModal'+reply.id"></i>
                  <b-modal :id="'my_replyModal'+reply.id" :ref="'my_commentModal'+reply.id" modal-class="settings_Modal" hide-header hide-footer centered>
                    <button :id="'replyEditId'+reply.id" :data-replyId="reply.id" @click="editComment"  class="w-100 settings_btn px-5 py-2">Edit</button>
                    <button :id="'replyDeleteId'+reply.id" :data-replyId="reply.id" @click="deleteComment" class="w-100 settings_btn text-danger px-5 py-2 border-0" >Delete</button>
                  </b-modal> 
                </div>
                <span v-if="reply.editState" :id="'cancelReplyEditId'+comment.id" :data-replyId="reply.id" @click="cancelEditComment" class="cancel_edit text-danger ml-2">Cancel</span>
                <!-- like icon -->
                <svg :id="'commentLikeId'+reply.id" :data-replyId="reply.id" @click="likeUnlikeComments" :fill="reply.likeColor"  class="comment_like_icon replyfeed" aria-label="Like" viewBox="0 0 48 48" data-objectType='post/comments'>
                  <path :d="reply.likePath"></path>
                </svg>
              </div>
            </div>
            <div v-if="comment.replies.length != comment.replyFeed.length" @click="showReplies" :data-commentId="comment.id" class="text-secondary text-center" >Show More</div>
          </div>
          
          <observer v-on:intersect="commentIntersected" />
        </div>
      </div>
      <form class="addComment_form" @submit.prevent="addComment" method="post" action="">
        
        <input type="hidden" name="parent_comment_id" id="parent_comment_id" v-model.trim="addCommentForm.parentCommentId">
        <input type="hidden" name="original_comment_id" id="parent_comment_id" v-model.trim="addCommentForm.originalCommentId">
        <input type="hidden" name="post_id" id="post_id" v-model.trim="postId">
        <div class="form-group position-relative d-flex justify-content-center"> 
            <img class="pfp_form" :src="sessionUser.pfp"/>
            <b-form-textarea ref="commentForm" placeholder="Add a comment ..." v-model="addCommentForm.commentBody" cols="30" rows="1" max-rows="10" class="form-control addCommentTxt" name="content" id="content" ></b-form-textarea>
            <button type="submit" class="btn btn-primary-outline submitComment p-0">Post</button>
            <p class="text-danger"></p>
        </div>
      </form>  
    </div>
    
</template>
<script>
import Observer from "./Observer";
window.axios = require("axios");
var moment = require("moment");

export default {
  data() {
    return {
      post: [],
      comments: [],
      originalComments: [],
      commentFeed: [],
      postId:null,
      observer: null,
      commentSliceIndex: 10,      
      commentIterations: 0,
      replySliceIndex: 5, 
      targetCommentId: null,
      commentKey: 0,  
      reply_display : 'd-none',  
      replyStatus: 'View replies',
      publicPath: 'http://localhost:8000/',
      addCommentForm: {
        parentCommentId: 0,
        originalCommentId: 0,
        commentBody: '',
      },

      // SESSIONUSER      
      sessionUser: [],
      likedPosts: [],      
      savedPosts: [],
      likedComments: [],
      followedUsers: [],
      followedUsersId: [],
      followUnfollowHtml:null,      

      
    };
  },

  created () {
    // this.likeUnlikePosts = _.debounce(this.likeUnlikePosts, 300)
      // this.likeUnlikeComments = _.debounce(this.likeUnlikeComments, 300)
      this.saveUnsave = _.debounce(this.saveUnsave, 300)
      this.likeUnlikePosts = _.debounce(this.likeUnlikePosts, 300)
      this.likeUnlikeComments = _.debounce(this.likeUnlikeComments, 300)
      this.postId = window.location.href.split("/")[4];

      axios
      .get(this.publicPath+"posts/"+this.postId)
      .then((data) => {
        // SESSION USER
        this.sessionUser = data.data.session_user

        this.likedPosts.push(...this.sessionUser.liked.posts)

        this.likedPosts.push(...this.sessionUser.liked.posts)
        this.likedComments.push(...data.data.session_user.liked.comments);
        if (this.sessionUser.favorites !== null) {
          this.savedPosts.push(...this.sessionUser.favorites);
        }
        if (this.sessionUser.followed !== null) {
          this.followedUsersId.push(...this.sessionUser.followed);        
        }
          this.followedUsers.push(...this.sessionUser.followed_users)
          this.likedComments.push(...this.sessionUser.liked.comments);

          // POST

        this.post= data.data;
        if (this.post.likes == null) {
          this.post.likes = []
        }

        if (this.likedPosts.includes(this.post.id)) {
          this.post.likePath =
            "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
          this.post.likeColor = "#ed4956";
        } else {
          this.post.likePath =
            "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
          this.post.likeColor = "#262626";
        }
        
        if (this.savedPosts.includes(this.post.id)) {
          this.post.savePath =
            "M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z";
          this.post.saveColor = "#ffbb00";
        } else {
          this.post.savePath =
            "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
          this.post.saveColor = "#262626";
        }

        // COMMENTS
        this.comments = data.data.comments;
        this.comments.forEach((comment) => {
         comment.replies = []
         comment.editState = false
          if (comment.likes == null) {
              comment.likes = []
          }
          
          comment.created_at=this.dateReformat(comment.created_at)

          if (this.likedComments.includes(comment.id)) {
              comment.likePath =
                "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              comment.likeColor = "#ed4956";
            } else {              
              comment.likePath =
                "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
              comment.likeColor = "#262626";
            }

            if (comment.parent_comment_id == 0) {
              this.originalComments.push(comment);
            } 
        })

        this.originalComments.forEach(originalComment => {
          originalComment.replies = []
          originalComment.replyFeed = []
          this.comments.forEach(comment => {
            if (comment.original_comment_id == originalComment.id) {
              if (comment.likes == null) {
                comment.likes = []
              }
              originalComment.replies.push(comment);
            }
          });
          originalComment.replyFeed.push(...originalComment.replies.slice(0, 5))
        });

        this.commentFeed.push(this.originalComments.slice(0, 10))
        
      })
      .catch((err) => {});


    Fire.$on('showMoreReplies',() => {
      
      this.commentFeed.forEach(commentPage => {
        commentPage.forEach(comment => {
          if (comment.id == this.targetCommentId ) {
          // push the next page ( of 10 replys) into the array feed
          comment.replyFeed.push(...comment.replies.slice(this.replySliceIndex, this.replySliceIndex + 5))
          // increment the slice index to get the next page on the next iteration
          this.replySliceIndex += 5;

          // forcing vue to rerender
          this.forceRerender() 
        }
        });
        
      });
    })

    
    
  
  },

  updated: function () {
   
  },

  mounted: function () {
    // this.$store.commit("changeName", "New Name");
    // console.log(this.$store.state.user.username);
    const commentId = window.location.href.split("?")[1]
    console.log(commentId);
    const el = this.$el
    this.$nextTick(() => {
      console.log(this.$refs.comment10);
    })
      // console.log(this.$refs);
      // el.scrollIntoView({behavior: "smooth"});

  },

  methods: {
    update_data() {
       axios
      .get(this.publicPath+"posts/"+this.postId)
      .then((data) => {    
        this.comments = data.data.comments;  
      })
      .catch((err) => {});
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
              .get(this.publicPath+"posts/" +$("#" + postLikeId)[0].attributes[1].nodeValue +"/" +this.sessionUser.id +"/unlike")
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
                  
                  if (this.post.id == dataPostId ) {
                    let index = this.post.likes.indexOf(this.sessionUser.id)
                    this.post.likes.splice(index, 1)
                  } 
            
                }

              });
        } else {
          // apply the laravel like function
          axios
            .get(this.publicPath+"posts/" + $("#" + postLikeId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/like")
            .then((response) => {
              if (!this.likedPosts.includes(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue))) {
                // add the post id to the likedPosts array
                this.likedPosts.push(parseInt($("#" + postLikeId)[0].attributes[1].nodeValue));
                $("#" + postLikeId)[0].attributes[2].nodeValue = "#ed4956";
                $("#" + postLikeId)[0].firstChild.attributes[0].nodeValue =
                  "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";

                this.post.likes.push(this.sessionUser.id)
                    
                
              }

            });

        }
    },

    likeUnlikeComments(event) {

      let commentLikeId
      let dataCommentId
      let dataReplyId

      if (typeof $(event.target).attr("id") == 'undefined') {
        commentLikeId = $(event.target.parentElement).attr("id");
        dataCommentId = $(event.target.parentElement).attr("data-commentId");
        dataReplyId = $(event.target.parentElement).attr("data-replyId");
      } else {
        commentLikeId = $(event.target).attr("id");
        dataCommentId = $(event.target).attr("data-commentId");
        dataReplyId = $(event.target).attr("data-replyId");
      }
      
        //  check if the comment is already liked by the user
        if (this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {
            // apply the laravel unlike function
            axios.get(this.publicPath+"post/comments/" +$("#" + commentLikeId)[0].attributes[1].nodeValue +"/" + this.sessionUser.id + "/unlike")
              .then((response) => {
              if (this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))){
                // get the index of the comment id we want to delete
                let index = this.likedComments.indexOf(
                  parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
                );
                //  remove it from the likedComments array
                this.likedComments.splice(index, 1);
                $("#" + commentLikeId)[0].attributes[2].nodeValue = "#262626";
                $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
                "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";

                this.commentFeed.forEach(commentPage => {
                  commentPage.forEach(comment => {
                      if (comment.id == dataCommentId ) {
                        let index = comment.likes.indexOf(this.sessionUser.id)
                        comment.likes.splice(index, 1)
                      } else if (comment.replies.length > 0) {
                        comment.replyFeed.forEach(reply => {
                          if (reply.id == dataReplyId ) {
                            let index = reply.likes.indexOf(this.sessionUser.id)
                            reply.likes.splice(index, 1)
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
            .get(this.publicPath+"post/comments/" + $("#" + commentLikeId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/like")
            .then((response) => {
            if (!this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))){

              // add the comment id to the likedComments array
              this.likedComments.push(
                parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
              );
              $("#" + commentLikeId)[0].attributes[2].nodeValue = "#ed4956";
              $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
              "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";

              this.commentFeed.forEach(commentPage => {
                  commentPage.forEach(comment => {
                      if (comment.id == dataCommentId ) {
                        let index = comment.likes.indexOf(this.sessionUser.id)
                        comment.likes.push(this.sessionUser.id)
                      } else if (comment.replies.length > 0) {
                        comment.replyFeed.forEach(reply => {
                          if (reply.id == dataReplyId ) {
                            let index = reply.likes.indexOf(this.sessionUser.id)
                            reply.likes.push(this.sessionUser.id)
                          }
                        });
                      }

                      
                  });
        
              });
              
            }
          });
          
        }
    },

    commentIntersected() {
      //count how many pages (a page has 10 comments) of comments we have then execute
      // the code inside that many times
      if (this.commentIterations < Math.floor(this.comments.length / 10)) {
        // push the next page ( of 10 comments) into the array feed
        this.commentFeed.push(this.comments.slice(this.commentSliceIndex, this.commentSliceIndex + 10));

        // increment the slice index to get the next page on the next iteration
        this.commentSliceIndex += 10;

        // increment the iteration
        this.commentIterations++;

      }
    },

    Expand_Collapse(event) {
      if ($(event.target).html() == 'View Replies') {
        $(event.target).html('Collapse Replies')
        $(event.target.nextElementSibling).toggleClass('highlight d-none') 
      } else {
        $(event.target).html('View Replies')
        $(event.target.nextElementSibling).toggleClass('highlight d-none') 
      }
    },

    showReplies (event) {
      this.targetCommentId = event.target.attributes[0].nodeValue;
      Fire.$emit('showMoreReplies')
    },

    forceRerender () {
      this.commentKey += 1;  
    },

    commentReply (event) {
      this.addCommentForm.parentCommentId = event.target.attributes[1].nodeValue
      this.addCommentForm.commentBody = '@'+event.target.attributes[2].nodeValue+' '
      this.addCommentForm.originalCommentId = event.target.attributes[3].nodeValue
      // console.log($(this.$refs.comment123[0]));
      // $(this.$refs.comment123[0])[0].classList.value='comments d-flex position-relative show_more p-2 bg-danger'
      // $(this.$refs.comment123[0])[0]
      // console.log($(this.$refs.comment123[0])[0].html());
      // console.log(this.$refs.commentForm);
      this.$refs.commentForm.focus()
    },

    focusCommentInput() {
      this.$refs.commentForm.focus()
    },

    addComment () {
      const params = new URLSearchParams();
      params.append('parent_comment_id', this.addCommentForm.parentCommentId);
      params.append('original_comment_id', this.addCommentForm.originalCommentId);
      params.append('post_id', this.postId);
      params.append('user_id', this.sessionUser.id);
      params.append('content', this.addCommentForm.commentBody);
      axios({
        method: 'post',
        url: this.publicPath+'comments',
        data: params
      }).then((response) => {

        if (this.likedComments.includes(response.data.id)) {
            response.data.likePath =
              "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
            response.data.likeColor = "#ed4956";
          } else {              
            response.data.likePath =
              "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
            response.data.likeColor = "#262626";
          }
          
        response.data.created_at= this.dateReformat(response.data.created_at)

        if (this.addCommentForm.originalCommentId == 0) {
          this.commentFeed[0].push(response.data)
        } else {
          this.originalComments.forEach(originalComment => {
            if (originalComment.id == this.addCommentForm.originalCommentId) {
                originalComment.replyFeed.push(response.data)
                originalComment.replies.push(response.data)
              } else {
                originalComment.replyFeed.forEach(reply => {
                  if (reply.id == this.addCommentForm.originalCommentId) {
                    originalComment.replyFeed[0].push(response.data)
                    originalComment.replies.push(response.data)
                  } 
                });
              }
            
          });
        } 
        
        this.addCommentForm.commentBody = ''
        this.addCommentForm.originalCommentId = 0
        this.addCommentForm.parentCommentId = 0
      })
      
    },
    
    deleteComment (event) {
      var targetId = event.target.attributes[1].nodeValue;
      axios({
        method: 'delete',
        url: `${this.publicPath}comments/`+targetId,
      }).then((response) => {
        this.commentFeed.forEach(page => {
          page.forEach(comment => {
            if (targetId == comment.id) {
              let index = page.indexOf(comment)
              page.splice(index, 1)
            } else {
              this.originalComments.forEach(originalComment => {
                originalComment.replyFeed.forEach(reply => {
                  if (reply.id == targetId) {
                    let index = originalComment.replyFeed.indexOf(reply)
                    originalComment.replyFeed.splice(index, 1)
                    let index2 = originalComment.replies.indexOf(reply)
                    originalComment.replies.splice(index2, 1)
                  }
                })
              })
            }
          });
          
        });
        
        $(this.$refs)[0]['my_commentModal'+targetId][0].hide() 
        this.forceRerender()
        
      })
    },

    cancelEditComment(event) {
      var id = event.target.attributes[1].nodeValue
      this.commentFeed.forEach(page => {
        page.forEach(comment => {
          if (comment.id == id) {
            comment.editState = false
            
          } else {
            comment.replies.forEach(reply => {
              if (reply.id == id) {
                reply.editState = false
              }
            });
          }
        });
      });
      $(this.$refs)[0]['my_commentModal'+id][0].hide() 
      this.forceRerender()
    },

    editComment(event) {
      var id = event.target.attributes[1].nodeValue
      this.commentFeed.forEach(page => {
        page.forEach(comment => {
          if (comment.id == id) {
            comment.editState = true
            
          } else {
            comment.replies.forEach(reply => {
              if (reply.id == id) {
                reply.editState = true
              }
            });
          }
        });
      });
      $(this.$refs)[0]['my_commentModal'+id][0].hide() 
      this.forceRerender()
    },

    submitEdit (event) {
      var targetId = event.target.attributes[0].nodeValue
      var updatedComment = $(event.target)[0].value
      axios({
        method: 'patch',
        url: `${this.publicPath}comments/${targetId}`,
        data: {
          content: updatedComment,
          },
      }).then((response) => {
         
        this.commentFeed.forEach(page => {
          page.forEach(comment => {
            if (comment.id == targetId) {
              comment.editState = false
              
            } else {
              comment.replies.forEach(reply => {
                if (reply.id == targetId) {
                  reply.editState = false
                }
              });
            }
          });
        });
        this.forceRerender()
      })
    },
    
    dateReformat (createdAt) {
      var dateArray = moment(createdAt).fromNow()
      // console.log(dateArray);
          
      //     if (dateArray[0]=='a') {
      //       dateArray[0]= 1
      //     }
      //     var timeLetter = dateArray[1].charAt(0)
      //     if (timeLetter=='f') {
      //       timeLetter= 'm'
      //     }
           
          return dateArray
    },
    
    goToProfile(event) {
      var userId = event.target.attributes[0].nodeValue
      window.location.replace(this.publicPath+userId+'/profile')
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
      this.forceRerender()
    },

    sendPost (event) {
      var targetcontactId = event.target.attributes[1].nodeValue
      var targetPostId = event.target.attributes[2].nodeValue

      axios.post('/conversation/send', {
          contactId: targetcontactId,
          sharedPostId: targetPostId
      }).then((response)=>{
        $(event.target).html('Sent').addClass('btn-success').attr('disabled', 'disabled')
        // $(this.$refs)[0]['my_sharePostModal'+targetPostId][0].hide() 
      }).catch((err) => {
      });

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
        axios.get(this.publicPath+"posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/unfavorite")
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
        axios.get(this.publicPath+"posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/favorite")
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



    
  },



  components: {
    Observer,
  },
};
</script>