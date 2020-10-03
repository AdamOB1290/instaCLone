<template>
    <div class="follow_suggestions_wrapper">
        <div class="welcome follow_suggestions text-center mb-0 ">
            <h5>Welcome to Instaclone</h5>
            <p class="welcome_message mx-4 my-0">Follow people to start seeing the photos and videos they share.</p>
        </div>

            <slick ref="slick" :options="slickOptions"  v-for="(page, key) in slickForLoop " :key="key" class="pt-6 pb-10 lg:pb-0 follow_suggestions d-flex justify-content-center align-items-center">

                <div v-for="(user, key) in userFeed" :key="key" class="user_wrapper pt-4 pb-2 px-1 position-relative">
                    <observer class="position-absolute" :id="'slickOsberver'+user.id" :data-slickIndex="key" v-on:intersect="reInit" />
                    <div class="card py-2 px-1 border-0 mx-1">

                    <div class="card-head d-flex flex-column align-items-center justify-content-center" >
                        <img @click="goToProfile" :data-userId="user.id" class="suggestion_pfp rounded-circle"  :src="user.pfp"/>
                        <span @click="goToProfile" :data-userId="user.id" class="suggestion_username font-weight-bold">{{user.username}}</span>
                        
                    </div>

                    <div class="card-body">
                        <p  class="suggestion_text">{{user.bio}}</p>
                        <div class="d-flex justify-content-center align-items-center image_wrapper">
                        <div v-for="(post, key) in user.top_posts.slice(0,3)" :key="key" class="image_div">
                            <img v-if="post.media_type == 'image'" class="cursor-pointer suggestion_images rounded-0"  :src="post.media_file"/>
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


      
                <div v-if="sessionUser" id="SLICKNONE" class="bg-dark text-center text-white" >{{sessionUser.name}}</div>


    </div>
</template>

<script>
import Slick from 'vue-slick';
import Observer from "./Observer";

export default {
    props: ['sessionUser','userFeed','users','followedUsersId','followUnfollowHtml'],
    // props: {
    //     sessionUser: {
    //         type: Object,
    //     },
    //     userFeed: {
    //         type: Array,
    //     },
    //     users: {
    //         type: Array,
    //     },
    //     followedUsersId: {
    //         type: Array,
    //     },
    //     followUnfollowHtml: {
    //         type: String,
    //     },
    // },

    data(){
        return {
            publicPath: 'http://localhost:8000/',
            observer: null,


            // User Data
            slickForLoop: [],
            userSliceIndex: 10,
            userIterations: 0,
            userSlickIterations: 0,
            userTarget: 10,


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

                {
                breakpoint: 1800,
                settings: {
                    centerMode: true,
                    centerPadding: '140px',
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                }
                },
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
        }

    },
    

    created: function () {
        // this.followUnfollow = _.debounce(this.followUnfollow, 300)

        // to fire the slick forloop once
        this.slickForLoop=[1]
    },

    
    mounted: function() {
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
            if (this.followedUsersId.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))) {
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
                    let index = this.followedUsersId.indexOf(
                        parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                    );

                    if (this.followedUsersId.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                        //  remove it from the followedUsersId array
                        this.followedUsersId.splice(index, 1)
                        $("#" + userFollowId)[0].innerHTML = 'Follow' 
                    
                    }
                    
                    
                    });
            } else {
                // apply the laravel follow function
                axios
                .get("users/" + $("#" + userFollowId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/follow" )
                .then((response) => {
                    
                    if (!this.followedUsersId.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                        // add the user id to the followedUsersId array
                        this.followedUsersId.push(
                        parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                        )
                        $("#" + userFollowId)[0].innerHTML = 'Unfollow' 

                        console.log(this.$refs);
                        this.$refs.slick[0].next();
                    }
                    
                });
                        
                
                
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
    
        goToProfile(event) {
            var userId = event.target.attributes[0].nodeValue;
            this.$router.push({path : userId+'/profile'})
        },    
    },

    computed: {
    },


    component: {
        Slick,
        Observer,
    },

}
</script>