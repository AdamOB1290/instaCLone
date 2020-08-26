<template>
    <div>
      <div class="comments_wrapper" v-for="(commentPage, key) in commentFeed" :key="key" >
        <div v-for="(comment, key) in commentPage" :key="key">
          <div class="comments d-flex show_more p-2" >
            <img v-if="comment.user.pfp_type == 'imageUrl'" class="pfp card-img-top rounded-circle mr-2"
              :src="comment.user.pfp"/>
            <img v-else class="pfp card-img-top rounded-circle mr-2" :src="'storage/'+comment.user.pfp"/>
            <div>
              <span class="username font-weight-bold">{{comment.user.username}}</span>
              {{comment.content}}
              <br>
              <span class="text-secondary">{{comment.created_at}}</span>
              <span class="text-secondary ml-2">{{comment.likes}} likes</span>
              <span class="text-secondary ml-2">Reply</span>
            </div>
            <!-- like icon -->
            <svg :id="'commentLikeId'+comment.id" :data-commentId="comment.id" @click="likeUnlike" :fill="comment.likeColor"  class="comment_like_icon" aria-label="Like" viewBox="0 0 48 48" >
              <path :d="comment.likePath"></path>
            </svg>
          </div>
          <div v-if="comment.replies.length > 0" class="text-secondary text-center" @click="Expand_Collapse">View replies</div>
          <div v-if="comment.replies.length > 0" class="reply_wrapper ml-4">
            <div  v-for="(reply, key) in comment.replyFeed" :key="key">
              <div class="replys d-flex show_more p-2">
                <img v-if="reply.user.pfp_type == 'imageUrl'" class="pfp card-img-top rounded-circle mr-2"
                  :src="reply.user.pfp"/>
                <img v-else class="pfp card-img-top rounded-circle mr-2" :src="'storage/'+reply.user.pfp"/>
                <div>
                  <span class="username font-weight-bold">{{reply.user.username}}</span>
                  {{reply.content}}
                  <br>
                  <span class="text-secondary">{{reply.created_at}}</span>
                  <span class="text-secondary ml-2">{{reply.likes}} likes</span>
                  <span class="text-secondary ml-2">Reply</span>
                </div>
                <!-- like icon -->
                <svg :id="'commentLikeId'+reply.id" :data-replyId="reply.id" @click="likeUnlike" :fill="reply.likeColor"  class="comment_like_icon" aria-label="Like" viewBox="0 0 48 48" >
                  <path :d="reply.likePath"></path>
                </svg>
              </div>
            </div>
            <div v-if="comment.replies.length > 0" :data-commentId="comment.id" class="text-secondary text-center" @click="showMoreReplies">Show More</div>
          </div>
          
          <observer v-on:intersect="commentIntersected" />
        </div>
        
        
      </div>  
    </div>
</template>
<script>
import Observer from "./Observer";
window.axios = require("axios");
var moment = require("moment");

export default {
  data() {
    return {
      comments: [],
      originalComments: [],
      commentFeed: [],
      postId:null,
      sessionUser: this.$sessionUser,
      likedComments: [],
      observer: null,
      commentSliceIndex: 10,      
      commentIterations: 0,
      replySliceIndex: 5,      

      
    };
  },

  created: function () {
      this.likeUnlike = _.debounce(this.likeUnlike, 300)

    this.postId = window.location.href.split("/")[4];
    axios
      .get("comments/"+this.postId)
      .then((data) => {
        this.likedComments.push(...data.data.session_user.liked.comments);
      this.sessionUser = data.data.session_user
        this.comments = data.data.comments;
        
        this.comments.forEach((comment) => {
         comment.replies = []
          var dateArray = moment(comment.created_at).fromNow().split(" ")
          var timeLetter = dateArray[1].charAt(0)
          comment.created_at = dateArray[0]+timeLetter


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
                  originalComment.replies.push(comment);
            }
          });
          originalComment.replyFeed.push(...originalComment.replies.slice(0, 5))
        });

        this.commentFeed.push(this.originalComments.slice(0, 10))
        
      })
      .catch((err) => {});

    

    
    
  },

  updated: function () {
    axios
      .get("comments/"+this.postId)
      .then((data) => {  
        
          
        this.comments = data.data.comments;
         
          
        
        
      })
      .catch((err) => {});
  },

  mounted: function () {
    // this.$store.commit("changeName", "New Name");
    // console.log(this.$store.state.user.username);

  },

  methods: {

    likeUnlike(event) {

      let commentLikeId;

      if (typeof $(event.target).attr("id") == 'undefined') {
        commentLikeId = $(event.target.parentElement).attr("id");
      } else {
        commentLikeId = $(event.target).attr("id");
      }

        //  check if the comment is already liked by the user
        if (this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))) {
            // apply the laravel unlike function
            axios.get("comments/" +$("#" + commentLikeId)[0].attributes[1].nodeValue +"/" + this.$sessionUser.id + "/unlike")
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

              }

              });
        } else {
          // apply the laravel like function
          axios
            .get("comments/" + $("#" + commentLikeId)[0].attributes[1].nodeValue + "/" + this.$sessionUser.id + "/like")
            .then((response) => {
            if (!this.likedComments.includes(parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue))){

              // add the comment id to the likedComments array
              this.likedComments.push(
                parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
              );
              
              $("#" + commentLikeId)[0].attributes[2].nodeValue = "#ed4956";
              $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
              "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
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
        

      if ($(event.target).html() == 'View replies') {
        
        $(event.target).html('Collapse replies');
      } else {
        
        $(event.target).html('View replies');
      }
    },

    showMoreReplies(event) {
      let targetCommentId = event.target.attributes[0].nodeValue
      console.log(targetCommentId);
      this.commentFeed.forEach(commentPage => {
        commentPage.forEach(comment => {
          if (comment.id == targetCommentId) {
            console.log(comment.replyFeed);
          // push the next page ( of 10 replys) into the array feed
          comment.replyFeed.push(...comment.replies.slice(this.replySliceIndex, this.replySliceIndex + 5))
          // increment the slice index to get the next page on the next iteration
          this.replySliceIndex += 5;
          console.log(comment.replyFeed);
        }
        });
        
      });
    }
    
  },



  components: {
    Observer,
  },
};
</script>