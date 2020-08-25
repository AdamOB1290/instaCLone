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
            <svg :id="'commentLikeId'+comment.id" :data-commentID="comment.id" @click="likeUnlike" :fill="comment.likeColor"  class="comment_like_icon" aria-label="Like" viewBox="0 0 48 48" >
              <path :d="comment.likePath"></path>
            </svg>
          </div>
          <a href class="show_hide text-center">View All {{comment.replies.length}} Replies</a>
          <div class="reply_wrapper ml-4">
            <div v-for="(reply, key) in comment.replies" :key="key">
              <div class="replys d-flex show_more p-2" >
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
                <svg :id="'commentLikeId'+reply.id" :data-commentID="comment.id" @click="likeUnlike" :fill="comment.likeColor"  class="comment_like_icon" aria-label="Like" viewBox="0 0 48 48" >
                  <path :d="comment.likePath"></path>
                </svg>
              </div>
            </div>
          </div>
          

        </div>
        
        
      </div>  
    </div>
</template>
<script>

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
      
      
    };
  },

  created: function () {
    this.likedComments.push(...this.sessionUser.liked.comments);
    this.postId = window.location.href.split("/")[4];
    axios
      .get("comments/"+this.postId)
      .then((data) => {
        this.comments = data.data.comments;
        
        this.comments.forEach((comment) => {
          
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
          this.comments.forEach(comment => {
            if (comment.original_comment_id == originalComment.id) {
              
              if (typeof originalComment.replies=='undefined') {
                originalComment.replies = [comment]
              } else {
                originalComment.replies.push(comment);
              }
              
              
            }
          });
        });

        this.commentFeed.push(this.originalComments.slice(0, 10))
        console.log(this.commentFeed);
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
        let commentLikeId = $(event.currentTarget).attr("id");
        
        //  check if the comment is already liked by the user
        if (
            this.likedComments.includes(
              parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
            )
          ) {
            // apply the laravel unlike function
            axios
              .get(
                "comments/" +
                  $("#" + commentLikeId)[0].attributes[1].nodeValue +
                  "/" +
                  this.$sessionUser.id +
                  "/unlike"
              )
              .then((response) => {
                // get the index of the comment id we want to delete
                let index = this.likedComments.indexOf(
                  parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
                );

                //  remove it from the likedComments array
                this.likedComments.splice(index, 1);
              });
            $("#" + commentLikeId)[0].attributes[2].nodeValue = "#262626";
            $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
              "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
        } else {
          // apply the laravel like function
          axios
            .get(
              "comments/" +
                $("#" + commentLikeId)[0].attributes[1].nodeValue +
                "/" +
                this.$sessionUser.id +
                "/like"
            )
            .then((response) => {
              // add the comment id to the likedComments array
              this.likedComments.push(
                parseInt($("#" + commentLikeId)[0].attributes[1].nodeValue)
              );
            });

          $("#" + commentLikeId)[0].attributes[2].nodeValue = "#ed4956";
          $("#" + commentLikeId)[0].firstChild.attributes[0].nodeValue =
            "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
        }
    },
  },

  components: {
   
  },
};
</script>