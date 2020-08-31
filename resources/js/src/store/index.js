// store/index.js
 
import Vue from "vue";
import Vuex from "vuex";
 
Vue.use(Vuex);
 
export default new Vuex.Store({
  state: {
    post: {
      id: null,
    },
    user: {
      username: 'matt',
      fullName: 'Matt Maribojoc'
    },
    componentState: {
      navbar: true,
    },
    image: {
      srcFile: null,
      croppedImage : null,
    },
 },
 getters: {},
 mutations: {
    changeName (state, payload) {
      state.user.username = payload
    },
    changePostId (state, payload) {
      state.post.id = payload
    },
    changeState (state, payload){
      state.componentState.navbar = payload
    },
    changeImgSrc (state, payload){
      state.image.srcFile = payload
    },
    changeCroppedImage (state, payload){
      state.image.croppedImage = payload
    },
    
 },
 actions: {}
});