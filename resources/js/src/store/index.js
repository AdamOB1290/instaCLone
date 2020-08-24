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
      }
 },
 getters: {},
 mutations: {
    changeName (state, payload) {
        state.user.username = payload
      },
    changePostId (state, payload) {
        state.post.id = payload
      }
 },
 actions: {}
});