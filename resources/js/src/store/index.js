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
      fullName: 'Matt Maribojoc',
      sessionUser: null,
      loading: true
    },
    componentState: {
      navbar: true,
    },
    image: {
      srcFile: null,
      croppedImage : null,
    },
    iconState: {
      home : false,
    },
 },
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
    changeSessionUser (state, payload){
      state.user.sessionUser = payload
    },
    changeLoadingState(state, payload) {
      state.user.loading = payload
    },
    changeHomeIcon (state, payload){
      state.iconState.home = payload
    },
    
  },

 actions: {

  async setSessionUser(state) {
    axios
    .get("http://localhost:8000/users/"+Vue.prototype.$sessionUserId)
    .then((response) => { 
      state.commit('changeSessionUser', response.data)
    })
    .catch((err) => {})
  }
   },
   getters: {
    getCurrentSessionUser: state => state.user.sessionUser
  },
  
});