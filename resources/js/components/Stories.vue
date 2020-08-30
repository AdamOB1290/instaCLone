<template>
    <div class="">
        <b-carousel
      id="carousel-1"
      v-model="slide"
      :interval="0"
      indicators
      background="#ababab"
      img-width="1024"
      img-height="480"
      style="text-shadow: 1px 1px 2px #333;"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >
      <!-- Text slides with image -->
      <b-carousel-slide
        caption="First slide"
        text="Nulla vitae elit libero, a pharetra augue mollis interdum."
        img-src="https://picsum.photos/1024/480/?image=52"
      ></b-carousel-slide>

      <!-- Slides with custom text -->
      <b-carousel-slide img-src="https://picsum.photos/1024/480/?image=54">
        <h1>Hello world!</h1>
      </b-carousel-slide>

      <!-- Slides with image only -->
      <b-carousel-slide img-src="https://picsum.photos/1024/480/?image=58"></b-carousel-slide>

      <!-- Slides with img slot -->
      <!-- Note the classes .d-block and .img-fluid to prevent browser default image alignment -->
      <b-carousel-slide>
        <template v-slot:img>
          <!-- <img
            class="d-block img-fluid w-100"
            width="1024"
            height="480"
            src="https://picsum.photos/1024/480/?image=55"
            alt="image slot"
          > -->
          <video class="video img" controls muted>
                  <source :src="publicPath+'storage/uploads/LlWMjfb2xYhyH5Om278iHB5Gz9MDGsXqJohTgWdX.mp4'"/>
        </video>
        </template>
      </b-carousel-slide>

      <!-- Slide with blank fluid image to maintain slide aspect ratio -->
      <b-carousel-slide caption="Blank Image" img-blank img-alt="Blank image">
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eros felis, tincidunt
          a tincidunt eget, convallis vel est. Ut pellentesque ut lacus vel interdum.
        </p>
      </b-carousel-slide>
    </b-carousel>

        <!-- <slick ref="slick" :options="slickOptions"  v-for="(nothing, key) in slickIteration " :key="key" class="pt-3 pb-5 follow_suggestions d-flex justify-content-center align-items-center">
            <div v-for="(story, key) in storyUser.stories " :key="key" class="user_wrapper py-2 px-1 position-relative">
                <p>{{story.id}}</p>
      
            </div>
        </slick> -->
    </div>
</template>

<script>
    import Slick from 'vue-slick';
    export default {
         data() {
            return {
                slide: 0,
                sliding: null,
                publicPath: 'http://localhost:8000/',
            }
            },
            methods: {
            onSlideStart(slide) {
                this.sliding = true
            },
            onSlideEnd(slide) {
                this.sliding = false
            }
            }
    //     data() {
    //         return {
    //         sessionUser: null,
    //         posts: [],
    //         storyUsers: [],
    //         storyUser: [],
    //         publicPath: 'http://localhost:8000/',
    //         storyUserId: null,
    //         slickIteration: [1],
    //         slickOptions: {
    //         slidesToShow: 'auto',
    //         slidesToScroll: 1,
    //         arrows: false,
    //         slickSetOption: true,
    //         autoplay: false,
    //         autoplaySpeed: 3000,
    //         centerMode: false,
    //         infinite: false,
    //         responsive: [

    //         // cards needs to be bigger through media queries

    //             // {
    //             // breakpoint: 1024,
    //             // settings: {
    //             //   centerMode: true,
    //             //   centerPadding: '40px',
    //             //     slidesToShow: 3,
    //             //     slidesToScroll: 3,
    //             //     infinite: true,
    //             // }
    //             // },
    //             // {
    //             // breakpoint: 900,
    //             // settings: {
    //             //   centerMode: true,
    //             //   centerPadding: '60px',
    //             //     slidesToShow: 4,
    //             //     slidesToScroll: 4
    //             // }
    //             // },
    //             // {
    //             // breakpoint: 800,
    //             // settings: {
    //             //   centerMode: true,
    //             //   centerPadding: '60px',
    //             //     slidesToShow: 3,
    //             //     slidesToScroll: 3
    //             // }
    //             // },
    //             // {
    //             // breakpoint: 700,
    //             // settings: {
    //             //   centerMode: true,
    //             //   centerPadding: '10px',
    //             //     slidesToShow: 3,
    //             //     slidesToScroll: 3
    //             // }
    //             // },
    //             {
    //             breakpoint: 600,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '80px',
    //                 slidesToShow: 2,
    //                 slidesToScroll: 2
    //             }
    //             },
    //             {
    //             breakpoint: 560,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '65px',
    //                 slidesToShow: 2,
    //                 slidesToScroll: 2
    //             }
    //             },
    //             {
    //             breakpoint: 520,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '50px',
    //                 slidesToShow: 2,
    //                 slidesToScroll: 2
    //             }
    //             },
    //             {
    //             breakpoint: 480,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '120px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             },
    //             {
    //             breakpoint: 430,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '105px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             },
    //             {
    //             breakpoint: 400,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '90px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             },
    //             {
    //             breakpoint: 370,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '75px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             },
    //             {
    //             breakpoint: 340,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '60px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             },
    //             {
    //             breakpoint: 310,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '50px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             },
    //             {
    //             breakpoint: 290,
    //             settings: {
    //             centerMode: true,
    //             centerPadding: '45px',
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //             }
    //         ]
    
    //       // Any other options that can be got from plugin documentation
    //   },
    //     }
    // },

    // created: function () {
    //     this.storyUserId = window.location.href.split("/")[4];

    //     axios
    //     .get(this.publicPath+'posts')
    //     .then((data) => { 
    //         this.sessionUser = data.data[0].session_user 

    //         this.posts = data.data;
    //         this.storyUsers.push(...this.posts[0]['story_users'])
    //         this.storyUsers.forEach(storyUser => {
    //             storyUser.stories = []
    //         })
                    
    //         this.posts.forEach((post) => {

    //             if (post.type == "story" || post.type == "post/story" || post.type == "story/post") {
    //             this.storyUsers.forEach(storyUser => {
    //                 if (storyUser.id == post.user.id) {
    //                     if (this.storyUserId == storyUser.id) {
    //                          storyUser.stories.push(post)
    //                          this.storyUser = storyUser
    //                     }
    //                 }
    //             });
    //             }

    //         });
            
    //         console.log(this.storyUser);
    //     })
    //     .catch((err) => {});
    // },

    // methods: {

    // },

    // components: {
    //     Slick,
    // },

    }
</script>