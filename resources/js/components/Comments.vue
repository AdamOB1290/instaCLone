<template>
    <div v-if="post.user" class="post_comment_wrapper sm:mx-auto sm:w-3/4 md:w-7/12" :key = "commentKey">
      <div  class="post-head d-flex align-items-center border-down py-1">
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
        <div class="px-2 mt-1">
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
                      <searchComponent :users="followedUsers" :post="post"></searchComponent>
                    </ul>
              </b-modal> 

            <!-- save icon -->
            <svg :id="'postSaveId'+post.id" :data-postId="post.id" @click="saveUnsave" aria-label="Save" class="ml-auto" :fill="post.saveColor" height="24" viewBox="0 0 48 48" width="24">
              <path :d="post.savePath" />
            </svg>
          </div>
          
          <div v-if=" post.likes" class="mt-1">
            <span v-if=" post.likes.length != 0" class="font-weight-bold ">{{post.likes.length}} likes</span>
            <span v-if=" post.likes.length == 0" class="font-weight-bold ">0 likes</span>
          </div>

          <div class="card-text position-relative mt-2">
            <span v-if="post.editState" :id="'cancelPostEditId'+post.id" :data-postId="post.id" @click="cancelEditPostDescription" class="cancel_edit post_cancel text-danger cursor-pointer">Cancel</span>
            <span class="description show_more">
              <span @click="goToProfile" :data-userId="post.user.id" class="username font-weight-bold">{{post.user.username}}</span>
              <textarea v-if="post.editState"  v-model="post.description" @keydown.enter.exact.prevent 
                @keyup.enter.exact="submitEditPost" :data-postId="post.id" cols="47" rows="5" class="mt-2 editTxt">
              </textarea>
              <span v-else>{{post.description}}</span>
              
            </span>
            <!-- this button shows/hides the description -->
            <span :ref="'showMoreLess'+post.id" class="show_hide" @click="showHideDescription">{{ post.showStatus }}</span>
          </div>
        </div>
      </div>
      <div class="comments_wrapper mt-3" v-for="(commentPage, key) in commentFeed" :key="key">
        <div  v-for="(comment, key) in commentPage" :key="key">
          <div :ref="'comment'+comment.id" :id="'comment'+comment.id" :class="`comments d-flex position-relative show_more ${comment.delete_state == 1 ? 'px-2 pt-2 pb-0' : 'p-2'}`" >
            <img v-if="!comment.delete_state == 1" @click="goToProfile" :data-userId="comment.user.id" class="pfp rounded-circle mr-2" :src="comment.user.pfp"/>
            <img v-else src="https://res.cloudinary.com/resize/image/upload/v1599491685/default_pic.png" alt="" class="pfp rounded-circle mr-2" >
            <div class="pr-3 pl-1">
              <span @click="goToProfile" :data-userId="comment.user.id" class="username font-weight-bold pb-2">{{comment.user.username}}</span>
              <textarea v-if="comment.editState"  v-model="comment.content" @keydown.enter.exact.prevent 
              @keyup.enter.exact="submitEdit" :data-commentId="comment.id"
               cols="35" rows="5" class="mt-2 editTxt">
              </textarea>
              <div v-else :class="`${comment.delete_state == 1 ? 'text-secondary font-italic deleted_comment' : 'break-all'}`">{{comment.content}}</div>
              <br>
              <div v-if="comment.delete_state == 0">
                <span class="text-secondary">{{comment.created_at}}</span>
                <span v-if=" comment.likes.length == 0" class="text-secondary ml-2">0 likes</span>
                <span v-else class="text-secondary ml-2">{{comment.likes.length}} likes</span>
                <span :id="'commentReplyId'+comment.id" :data-commentId="comment.id" :data-username="comment.user.username" :data-originalCommentId="comment.id" @click="commentReply" class="text-secondary ml-2 cursor-pointer">Reply</span>
                <i class="fas fa-ellipsis-h text-secondary ml-2 cursor-pointer"  v-b-modal="'my_commentModal'+comment.id"></i>
                <b-modal :id="'my_commentModal'+comment.id" :ref="'my_commentModal'+comment.id" modal-class="settings_Modal" hide-header hide-footer centered>
                  <button :id="'commentEditId'+comment.id" :data-commentId="comment.id" @click="editComment"  class="w-100 settings_btn px-5 py-2">Edit</button>
                  <button :id="'commentDeleteId'+comment.id" :data-commentId="comment.id" @click="deleteComment" class="w-100 settings_btn text-danger px-5 py-2 border-0" >Delete</button>
                </b-modal>
              </div>
            </div>
            <span v-if="comment.editState" :id="'cancelCommentEditId'+comment.id" :data-commentId="comment.id" @click="cancelEditComment" class="cancel_edit text-danger ml-2 cursor-pointer">Cancel</span>
            <!-- like icon -->
            <svg v-if="comment.delete_state == 0" :id="'commentLikeId'+comment.id" :data-commentId="comment.id" @click="likeUnlikeComments" :fill="comment.likeColor"  class="comment_like_icon commentfeed" aria-label="Like" viewBox="0 0 48 48" data-objectType='post/comments'>
              <path :d="comment.likePath"></path>
            </svg>
          </div>
          <div :ref="'showReplies'+comment.id" :id="'showReplies'+comment.id" v-if="comment.replies.length > 0" class="text-secondary text-center cursor-pointer" @click="Expand_Collapse">View Replies</div>
          <div v-if="comment.replies.length > 0" class="reply_wrapper mx-4" :class="reply_display">
            <div  v-for="(reply, key) in comment.replyFeed" :key="key">
              <div :ref="'comment'+reply.id" :id="'comment'+reply.id"  class="replies d-flex position-relative show_more p-2">
                <img class="pfp rounded-circle mr-2" :src="reply.user.pfp"/>
                <div>
                  <span class="username font-weight-bold pb-2">{{reply.user.username}}</span>
                  <span @click="goToParent" :data-parentId="'#comment'+reply.parent_comment_id" class="text-gray-600 hover:underline hover:text-red-600 cursor-pointer">{{'replied to comment#'+reply.parent_comment_id}}</span>
                  <textarea  v-if="reply.editState" v-model="reply.content" @keydown.enter.exact.prevent 
                  @keyup.enter.exact="submitEdit" :data-replyId="reply.id" 
                  cols="30" rows="5" class="mt-2 editTxt">
                  </textarea>
                  <div v-else :class="`${reply.delete_state == 1 ? 'text-secondary font-italic deleted_comment' : 'break-all'}`">{{reply.content}}</div>
                  <br>
                  <span class="text-secondary">{{reply.created_at}}</span>
                  <span v-if=" reply.likes.length != 0" class="text-secondary ml-2">{{reply.likes.length}} likes</span>
                  <span v-if=" reply.likes.length == 0" class="text-secondary ml-2">0 likes</span>
                  <span :id="'commentReplyId'+comment.id" :data-replyId="reply.id" :data-username="reply.user.username" :data-originalCommentId="reply.original_comment_id" @click="commentReply" class="text-secondary ml-2 cursor-pointer">Reply</span>
                  <i class="fas fa-ellipsis-h text-secondary ml-2 cursor-pointer"  v-b-modal="'my_replyModal'+reply.id"></i>
                  <b-modal :id="'my_replyModal'+reply.id" :ref="'my_commentModal'+reply.id" modal-class="settings_Modal" hide-header hide-footer centered>
                    <button :id="'replyEditId'+reply.id" :data-replyId="reply.id" @click="editComment"  class="w-100 settings_btn px-5 py-2">Edit</button>
                    <button :id="'replyDeleteId'+reply.id" :data-replyId="reply.id" @click="deleteComment" class="w-100 settings_btn text-danger px-5 py-2 border-0" >Delete</button>
                  </b-modal> 
                </div>
                <span v-if="reply.editState" :id="'cancelReplyEditId'+comment.id" :data-replyId="reply.id" @click="cancelEditComment" class="cancel_edit text-danger ml-2 cursor-pointer">Cancel</span>
                <!-- like icon -->
                <svg :id="'commentLikeId'+reply.id" :data-replyId="reply.id" @click="likeUnlikeComments" :fill="reply.likeColor"  class="comment_like_icon replyfeed" aria-label="Like" viewBox="0 0 48 48" data-objectType='post/comments'>
                  <path :d="reply.likePath"></path>
                </svg>
              </div>
            </div>
            <div v-if="comment.replies.length != comment.replyFeed.length" :ref="'showMore'+comment.id" @click="showReplies" :data-commentId="comment.id" class="text-secondary text-center cursor-pointer" >Show More</div>
          </div>
          
          <observer v-on:intersect="commentIntersected" />
        </div>
      </div>
      <form class="addComment_form" @submit.prevent="addComment" method="post" action="">
        
        <input type="hidden" name="parent_comment_id" id="parent_comment_id" v-model.trim="addCommentForm.parentCommentId">
        <input type="hidden" name="original_comment_id" id="parent_comment_id" v-model.trim="addCommentForm.originalCommentId">
        <input type="hidden" name="post_id" id="post_id" v-model.trim="postId">
        <div class="form-group position-relative d-flex justify-content-center mb-0"> 
            <img class="pfp_form" :src="sessionUser.pfp"/>
            <b-form-textarea ref="commentForm" placeholder="Add a comment ..." v-model="addCommentForm.commentBody" cols="30" max-rows="10" class="form-control " name="content" id="content" ></b-form-textarea>
            <button type="submit" class="btn btn-primary-outline submitComment p-0">Post</button>
            <p class="text-danger"></p>
        </div>
      </form>  
    </div>
</template>
<script>
import Observer from "./Observer";
import SearchComponent from './SearchComponent';
window.axios = require("axios");
var moment = require("moment");

export default {
  data() {
    return {
      response: [],
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
      executeScroll: true,

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

  watch: {
        '$route': {
            handler: 'update_data',
            immediate: true
        }
    },

  created () {
      // this.saveUnsave = _.debounce(this.saveUnsave, 300)
      // this.likeUnlikePosts = _.debounce(this.likeUnlikePosts, 300)
      // this.likeUnlikeComments = _.debounce(this.likeUnlikeComments, 300)
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
        this.post.editState = false

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
        this.originalComments = []
        this.commentFeed = []
        this.comments = []
        this.comments = data.data.comments;
        this.comments.forEach((comment) => {
         comment.replies = []
         comment.editState = false
         if (comment.delete_state == 1) {
           comment.user.username=""
           comment.content= "(Comment Deleted)"
         }
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
          // push the next page ( of 10 replies) into the array feed
          comment.replyFeed.push(...comment.replies.slice(this.replySliceIndex, this.replySliceIndex + 5))
          // increment the slice index to get the next page on the next iteration
          this.replySliceIndex += 5;

          // updating our data
          this.update_data()
        }
        });
        
      });
    })

    
    
  
  },

  updated: function () {
    
    
  },

  mounted: function () {
  },

  methods: {
    scrollToTarget(){
       const urlArray= window.location.href.split("/")
        // console.log('Scroll state', this.executeScroll);
        let commentId
        let parentId
        let targetComment
        let showReplies
        let showMore
        let originalCommentId

        if (urlArray.length > 5 && this.executeScroll) {
          setTimeout(() => {
          commentId = urlArray[5]
          targetComment = document.getElementById("comment"+commentId)
          // console.log('commentId then targetComment then scroll state', commentId, targetComment, this.executeScroll);

          // check if it's a reply
          if (urlArray.length == 7) {
              originalCommentId = urlArray[6]
              showReplies = this.$refs['showReplies'+originalCommentId]
              // finds the comment and pushes it into the replyfeed
              this.originalComments.forEach(originalComment => {
                if (originalComment.id == originalCommentId) {
                  originalComment.replyFeed.forEach(replyInFeed => {
                    if (replyInFeed.id == commentId) {
                      let index = originalComment.replyFeed.indexOf(replyInFeed)
                      originalComment.replyFeed.splice(index, 1)
                    }
                  });
                  originalComment.replies.forEach(reply => {
                    if (reply.id == commentId) {
                      originalComment.replyFeed.splice(0, 0, reply)
                    }
                  });
                }
              });
              // this.executeScroll =false
            
              targetComment = document.getElementById("comment"+commentId)
              // console.log(showReplies[0]);
              showReplies[0].click()
              // console.log('comment THEN showrepliesEl THEN its sibling', targetComment, showReplies[0], showReplies[0].nextElementSibling);
             $(showReplies[0].nextElementSibling).removeClass('highlight')
          }      
               
              targetComment.className += " highlight";
              targetComment.scrollIntoView({behavior: "smooth"})

                      }, 1000);             
            this.forceRerender() 

        }

  

    },
    
    update_data() {
      this.postId = window.location.href.split("/")[4];
       axios
      .get(this.publicPath+"posts/"+this.postId)
      .then((data) => { 
         // POST
        this.post = data.data;
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

        // Comment
        this.originalComments = []
        this.commentFeed = []
        this.comments = []
        this.comments = data.data.comments
        
        this.comments.forEach((comment) => {
         comment.replies = []
         comment.editState = false
         if (comment.delete_state == 1) {
           comment.user.username=""
           comment.content= "(Comment Deleted)"
         }
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
          originalComment.replyFeed.push(...originalComment.replies.slice(0, 10))
        });

        this.commentFeed.push(this.originalComments.slice(0, 10))
        this.scrollToTarget()
      })
      .catch((err) => {});
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
        if (target.hasClass('animate__animated')) {
          target.removeClass('animate__animated animate__bounceIn');  
        }
          target.addClass('reduce_like_animation');  
        // apply the laravel like function
        axios
          .get(this.publicPath+"posts/" + $("#" + postLikeId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/like")
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

              this.post.likes.push(this.sessionUser.id)
                  
              
            }

          });

      }
    },

    likeUnlikeComments(event) {
      let target
      let commentLikeId
      let dataPostId
      let dataCommentId
      

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
                        if (reply.id == dataCommentId ) {
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
        if (target.hasClass('animate__animated')) {
          target.removeClass('animate__animated animate__bounceIn');  
        }
        target.addClass('reduce_comment_like_animation')
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
            if (target.hasClass('reduce_comment_like_animation')) {
              target.removeClass('reduce_comment_like_animation')
            }
            target.addClass('animate__animated animate__bounceIn');  
            $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
            "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";

            this.commentFeed.forEach(commentPage => {
                commentPage.forEach(comment => {
                    if (comment.id == dataCommentId ) {
                      let index = comment.likes.indexOf(this.sessionUser.id)
                      comment.likes.push(this.sessionUser.id)
                    } else if (comment.replies.length > 0) {
                      comment.replyFeed.forEach(reply => {
                        if (reply.id == dataCommentId ) {
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
          response.data.replyFeed = []
          response.data.replies = []
          this.commentFeed[0].push(response.data)
          this.originalComments.push(response.data)
        } else {
          this.originalComments.forEach(originalComment => {
            
            if (originalComment.id == this.addCommentForm.originalCommentId) {
                originalComment.replyFeed.push(response.data)
                originalComment.replies.push(response.data)
              } 
              
              // it doesn't make sense to compare the reply id to the original comment ID
              //  by definition they will nerver match 
              // else {
              //   originalComment.replyFeed.forEach(reply => {
              //     if (reply.id == this.addCommentForm.originalCommentId) {
              //       originalComment.replyFeed[0].push(response.data)
              //       originalComment.replies.push(response.data)
              //     } 
              //   });
              // }
            
          });
        } 
        
        this.addCommentForm.commentBody = ''
        this.addCommentForm.originalCommentId = 0
        this.addCommentForm.parentCommentId = 0
        this.update_data()
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
                  if (response.data.delete_state == undefined) {
                    let index = page.indexOf(comment)
                    page.splice(index, 1)
                  } else {
                    comment.delete_state = 1
                    comment.user.username=""
                    comment.content= "(Comment Deleted)"
                  }
                  
                } else {
                  this.originalComments.forEach(originalComment => {
                    originalComment.replyFeed.forEach(reply => {
                      if (reply.id == targetId) {
                        if (response.data.delete_state == undefined) {
                          let index = originalComment.replyFeed.indexOf(reply)
                          originalComment.replyFeed.splice(index, 1)
                          let index2 = originalComment.replies.indexOf(reply)
                          originalComment.replies.splice(index2, 1)
                        } else {
                          reply.delete_state = 1
                          reply.user.username=""
                          reply.content= "(Comment Deleted)"
                        }
                        
                      }
                    })
                  })
                }
            });
          });
        
        $(this.$refs)[0]['my_commentModal'+targetId][0].hide() 
        this.forceRerender()
        this.update_data()
        
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
      return dateArray
      // moment.updateLocale('en', {
      //     relativeTime: {
      //         future: "in %s",
      //         past: "%s ago",
      //         s: number=>number + "s ago",
      //         ss: '%ds ago',
      //         m: "1m ago",
      //         mm: "%dm ago",
      //         h: "1h ago",
      //         hh: "%dh ago",
      //         d: "1d ago",
      //         dd: "%dd ago",
      //         M: "a month ago",
      //         MM: "%d months ago",
      //         y: "a year ago",
      //         yy: "%d years ago"
      //     }
      // });

      // let secondsElapsed = moment().diff(createdAt, 'seconds');
      // let dayStart = moment("2018-01-01").startOf('day').seconds(secondsElapsed);

      // if (secondsElapsed > 300) {
      //     return moment(createdAt).fromNow(true);
      // } else if (secondsElapsed < 60) {
      //     return dayStart.format('s') + 's ago';
      // } else {
      //     return dayStart.format('m:ss') + 'm ago';
      // }
    },
    
    goToProfile(event) {
      var userId = event.target.attributes[0].nodeValue
      this.$router.push({path : '/'+userId+'/profile'})
      // window.location.replace(this.publicPath+userId+'/profile')
    },  
    
    editPostDescription(event) {
      this.post.editState = true
      $('.modal').modal('hide');
      this.forceRerender()
      // this.forceRerender()
    },

    cancelEditPostDescription(event) {
      this.post.editState = false
      $('.modal').modal('hide');      
      this.forceRerender()
    },

    submitEditPost (event) {
      var updatedPost = $(event.target)[0].value
      // console.log(updatedPost);
      axios({
        method: 'patch',
        url: this.publicPath+'posts/'+this.post.id,
        data: {
          description: updatedPost,
          },
      }).then((response) => {
        this.post.editState = false
        this.forceRerender()
      })
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
        if (target.hasClass('animate__animated')) {
            target.removeClass('animate__animated animate__rubberBand');  
        }
        // apply the laravel Save function
        axios.get(this.publicPath+"posts/" + $("#" + postSaveId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/favorite")
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

    goToParent(event){
      var targetCommentId= event.target.attributes[0].nodeValue
      var targetOffset = $(targetCommentId).offset().top;
      var targetHeight = $(targetCommentId).height();
      var windowHeight = $(window).height();
      var offset;

      if (targetHeight < windowHeight) {
        offset = targetOffset - ((windowHeight / 2) - (targetHeight / 2));
      }
      else {
        offset = targetOffset;
      }
      $('html, body').animate({scrollTop:offset}, 700);

      if ($(targetCommentId).hasClass('highlight')) {
        $(targetCommentId).removeClass('highlight');
      }
      setTimeout(()=>{
      $(targetCommentId).addClass('highlight'),1000
      })

    }

    
  },

  computed:{
    // goToComment(){
    //     if (this.$route.name == "comments") {
    // }
    // }
  },

  components: {
    Observer,
    SearchComponent,
  },
};
</script>