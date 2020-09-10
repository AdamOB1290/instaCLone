<template>
    <div>
        <div class="d-flex align-items-center justify-content-around my-3">
            <div class="d-flex flex-column justify-content-center">
            <div class="profile_avatar d-flex position-relative">
                <img class="slider-image" :src="user.pfp" />
                <i class="plusStory fas fa-plus-circle position-absolute text-primary"></i>
            </div>
            <span class="username font-weight-bold">{{user.username}}</span>
            </div>
            <div class="d-flex align-items-center justify-content-center flex-column">
                <span class=" font-weight-bold">{{user.posts.length}}</span>
                <span>Posts</span>
            </div>
            <div class="d-flex align-items-center justify-content-center flex-column">
                <span v-b-modal="'my-followers'" class=" font-weight-bold">{{user.following.length}}</span>
                <span>Followers</span>
            </div>
            <div class="d-flex align-items-center justify-content-center flex-column">
                <span v-b-modal="'my-following'" class=" font-weight-bold">{{user.followedUsers.length}}</span>
                <span>Following</span>
            </div>
            <b-modal id="my-followers" centered >
                <ul class="sharePostUl">
                    <li v-for="(follower, key) in user.following" :key="key" class="d-flex justify-content-between" >
                        <div  class="">
                        <img class="pfp card-img-top rounded-circle mr-2" :src="follower.pfp"/>
                        <span class="username font-weight-bold">{{follower.username}}</span>
                        </div>
                        <button :id="'userId'+follower.id" :data-followerId="follower.id" @click="followUnfollow" class="btn btn-primary" 
                            v-text="`${user.followed.includes(follower.id) ? 'Unfollow' : 'Follow'}`">
                        </button>
                    </li>
                </ul>
            </b-modal>
            <b-modal id="my-following" centered >
                <ul class="sharePostUl">
                    <li v-for="(followedUser, key) in user.followedUsers" :key="key" class="d-flex justify-content-between" >
                        <div  class="">
                        <img class="pfp card-img-top rounded-circle mr-2" :src="followedUser.pfp"/>
                        <span class="username font-weight-bold">{{followedUser.username}}</span>
                        </div>
                        <button :id="'userId'+followedUser.id" :data-followerId="followedUser.id" @click="followUnfollow" class="btn btn-primary" 
                            v-text="`${user.followed.includes(followedUser.id) ? 'Unfollow' : 'Follow'}`">
                        </button>
                    </li>
                </ul>            
            </b-modal>
        </div>
        <div v-if="sessionUserId == userId" @click="goToProfile" class="w-100 border border-secondary text-center my-3 "> Edit profile </div>
        <div class="">
                <b-tabs content-class="" active-nav-item-class="font-weight-bold text-danger" fill lazy>
                    <b-tab title="Posts" active >
                        <div class="post_history_wrapper">
                            <div v-for="(post, key) in user.postsType" :key="key" class="post_history" >
                                <img  v-if="post.media_type == 'image'"  :src="post.media_file" class="w-100 h-100"/>
                                <video v-else-if="post.media_type == 'video'" controls muted class="w-100 h-100">
                                <source :src="post.media_file"  />
                                </video>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="Stories">
                        <div class="post_history_wrapper">
                            <div v-for="(story, key) in user.storiesType" :key="key" class="post_history" >
                                <img  v-if="story.media_type == 'image'"  :src="story.media_file" class="w-100 h-100"/>
                                <video v-else-if="story.media_type == 'video'" controls muted class="w-100 h-100">
                                <source :src="story.media_file"  />
                                </video>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
        </div>
        <div class="mt-3">
            <b-tabs content-class="" active-nav-item-class="font-weight-bold text-danger" fill lazy>
                <b-tab title="Saved" active>
                    <div class="post_history_wrapper">
                        <div v-for="(favoritedPost, key) in user.favorite_posts" :key="key" class="post_history" >
                            <img  v-if="favoritedPost.media_type == 'image'"  :src="favoritedPost.media_file" class="w-100 h-100"/>
                            <video v-else-if="favoritedPost.media_type == 'video'" controls muted class="w-100 h-100">
                            <source :src="favoritedPost.media_file"  />
                            </video>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Liked">
                    <b-tabs content-class="" active-nav-item-class="font-weight-bold text-danger" fill lazy>
                        <b-tab title="Liked Posts" active >
                            <div class="post_history_wrapper">
                                <div v-for="(likedPost, key) in user.liked_posts" :key="key" class="post_history" >
                                    <img  v-if="likedPost.media_type == 'image'"  :src="likedPost.media_file" class="w-100 h-100"/>
                                    <video v-else-if="likedPost.media_type == 'video'" controls muted class="w-100 h-100">
                                    <source :src="likedPost.media_file"  />
                                    </video>
                                </div>
                            </div>
                        </b-tab>
                        <b-tab title="Liked Comments">
                            <div class="liked_comments d-flex"  v-for="(likedComment, key) in user.liked_comments" :key="key"  >
                                <img class="pfp rounded-circle" :src="likedComment.user.pfp"/>
                                <div class="pr-3 pl-1">
                                    <span class="username font-weight-bold pb-2">{{likedComment.user.username}}</span>
                                    <span >{{likedComment.content}}</span>  
                                </div> 
                            </div>                     
                        </b-tab>
                    </b-tabs>
                </b-tab>
            </b-tabs>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            publicPath: 'http://localhost:8000/',
            user:'',
            sessionUserId: this.$sessionUserId,
            userId: '',
        }
    },

    created: function () {
        this.userId = window.location.href.split("/")[4];
        this.followUnfollow = _.debounce(this.followUnfollow, 300)
        axios
        .get(this.publicPath+"users/"+this.userId)
        .then((data) => { 
            
            this.user= data.data
                this.user.storiesType = [];
                this.user.postsType = [];
            this.user.posts.forEach(post => {
            if (post.type == "post") {
              this.user.postsType.push(post);
            } else if (post.type == "story") {
              this.user.storiesType.push(post);
            } else if (post.type == "post/story" || post.type == "story/post") {
              this.user.postsType.push(post);
              this.user.storiesType.push(post);
            }
                
            });
            console.log(this.user);
        })
        .catch((err) => {});
    },
    

    methods: {
        followUnfollow(event) {
            let userFollowId;

            if (typeof $(event.target).attr("id") == 'undefined') {
                userFollowId = $(event.target.parentElement).attr("id");
            } else {
                userFollowId = $(event.target).attr("id");
            }

            //  check if the post is already liked by the user
            if (this.user.followed.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))) {
                // apply the laravel unlike function
                axios
                    .get(
                    "users/" +
                        $("#" + userFollowId)[0].attributes[1].nodeValue +
                        "/" +
                        this.user.id +
                        "/unfollow"
                    )
                    .then((response) => {
                    
                    // get the index of the user id we want to delete
                    let index = this.user.followed.indexOf(
                        parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                    );

                    if (this.user.followed.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                        //  remove it from the followedUsersId array
                        this.user.followed.splice(index, 1)
                        $("#" + userFollowId)[0].innerHTML = 'Follow' 
                    
                    }
                    
                    
                    });
            } else {
                // apply the laravel follow function
                axios
                .get("users/" + $("#" + userFollowId)[0].attributes[1].nodeValue + "/" + this.user.id + "/follow" )
                .then((response) => {
                    
                    if (!this.user.followed.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                        // add the user id to the followedUsersId array
                        this.user.followed.push(
                        parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                        )
                        $("#" + userFollowId)[0].innerHTML = 'Unfollow' 
                                    

                    }
                    
                });
                        
                
                
            }
        },

        goToProfile(){
            window.location.replace('http://localhost:8000/profile/edit')
        }
        
    },

    computed: {
        
    }

}
</script>