/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
// import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('home', require('./components/Home.vue').default);
Vue.component('app', require('./components/App.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('vfooter', require('./components/Vfooter.vue').default);
Vue.component('feed', require('./components/Feed.vue').default);
Vue.component('profile', require('./components/Profile.vue').default);
Vue.component('edit-profile', require('./components/EditProfile.vue').default);
Vue.component('storyGlider', require('./components/StoryGlider.vue').default);
Vue.component('followUsers', require('./components/FollowUsers.vue').default);
Vue.component('validation', require('./components/ValidationErrors.vue').default); 
Vue.component('comment', require('./components/Comments.vue').default);
Vue.component('story', require('./components/Stories.vue').default);
Vue.component('ImgCropper', require('./components/ImageCropper.vue').default);
Vue.component('chat', require('./components/Chat.vue').default);
Vue.component('conversation', require('./components/Conversation.vue').default);
Vue.component('contactList', require('./components/ContactList.vue').default);
Vue.component("observer", require('./components/Observer.vue').default);
Vue.component("searchComponent", require('./components/SearchComponent.vue').default);
Vue.component("slick", require('vue-slick').default);
// var sessionUser = JSON.parse(document.querySelector("meta[name='session_user']").getAttribute('content'));
// if (typeof sessionUser.liked == 'string') {
//     var liked = JSON.parse(sessionUser.liked);
//     var notification_preferences = JSON.parse(sessionUser.notification_preferences);
//     var favorites = JSON.parse(sessionUser.favorites);
//     var followed = JSON.parse(sessionUser.followed);
//     var followers = JSON.parse(sessionUser.followers);
//     sessionUser.liked = liked;
//     sessionUser.notification_preferences = notification_preferences;
//     sessionUser.favorites = favorites;
//     sessionUser.followed = followed;
//     sessionUser.followers = followers;
// }

// // console.log(sessionUser);
// Vue.prototype.$sessionUser = sessionUser
if ( document.querySelector("meta[name='session_user_id']") != null ) {
   var sessionUserId = document.querySelector("meta[name='session_user_id']").getAttribute('content')
    Vue.prototype.$sessionUserId = sessionUserId 
}



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// import axios from 'axios'
import store from "./src/store"
window.axios = require('axios')
window.Fire = new Vue()
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import Cloudinary from "cloudinary-vue";
Vue.use(Cloudinary, {
  configuration: { cloudName: "resize" }
});
import VueDragscroll from 'vue-dragscroll'
Vue.use(VueDragscroll);




let routes = [
  { name: 'feeds', path: '/', component: require('./components/Feed.vue').default },
  { name: 'stories', path: '/story/:userId', component: require('./components/Stories.vue').default, },
  { name: 'comments', path: '/post/:postId/:objectId?/:originalCommentId?', component: require('./components/Comments.vue').default, },
  { name: 'chat', path: '/chat', component: require('./components/Chat.vue').default, },
  { name: 'profile', path: '/:userId/profile', component: require('./components/Profile.vue').default, },
  { name: 'edit-profile', path: '/profile/edit', component: require('./components/EditProfile.vue').default, },
]

const router = new VueRouter({
  mode: 'history',
  routes // short for `routes: routes`
})


const app = new Vue({
        store,
        el: '#app',
        router,
        data() {
            return {
                navbarState: true,
            }
        },
        created: function() {
          this.$store.dispatch('setSessionUser') 

        },
        mounted() {
          // console.log(this.$store.getters.getCurrentSessionUser);
        },
        computed : {
          getSessionUser(){
              
              return this.$store.getters.getCurrentSessionUser
              // return this.$store.getters.getCurrentSessionUser
             
          }
        },
      
    });

// LOGIN IMAGE FADE EFFECT

    class Slideshow {

      constructor() {
        this.initSlides();
        this.initSlideshow();
      }
    
      // Set a `data-slide` index on each slide for easier slide control.
      initSlides() {
        this.container = $('[data-slideshow]');
        this.slides = this.container.find('img');
        this.slides.each((idx, slide) => $(slide).attr('data-slide', idx));
      }
    
      // Pseudo-preload images so the slideshow doesn't start before all the images
      // are available.
      initSlideshow() {
        this.imagesLoaded = 0;
        this.currentIndex = 0;
        this.setNextSlide();
        this.slides.each((idx, slide) => {
          $('<img>').on('load', $.proxy(this.loadImage, this)).attr('src', $(slide).attr('src'));
        });
      }
    
      // When one image has loaded, check to see if all images have loaded, and if
      // so, start the slideshow.
      loadImage() {
        this.imagesLoaded++;
        if (this.imagesLoaded >= this.slides.length) { this.playSlideshow() }
      }
    
      // Start the slideshow.
      playSlideshow() {
        this.slideshow = window.setInterval(() => { this.performSlide() }, 3500);
      }
    
      // 1. Previous slide is unset.
      // 2. What was the next slide becomes the previous slide.
      // 3. New index and appropriate next slide are set.
      // 4. Fade out action.
      performSlide() {
        if (this.prevSlide) { this.prevSlide.removeClass('prev fade-out') }
    
        this.nextSlide.removeClass('next');
        this.prevSlide = this.nextSlide;
        this.prevSlide.addClass('prev');
    
        this.currentIndex++;
        if (this.currentIndex >= this.slides.length) { this.currentIndex = 0 }
    
        this.setNextSlide();
    
        this.prevSlide.addClass('fade-out');
      }
    
      setNextSlide() {
        this.nextSlide = this.container.find(`[data-slide="${this.currentIndex}"]`).first();
        this.nextSlide.addClass('next');
      }
    
    }
    
    $(document).ready(function() {
      new Slideshow;
    });
    