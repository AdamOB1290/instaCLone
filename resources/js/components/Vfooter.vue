<template>
<div  v-if="this.$store.state.componentState.navbar" class="footer_container">
<footer  class="v_footer navbar navbar-expand-lg navbar-light bg-light d-flex align-items-center justify-content-between py-0">
    <!-- home icon -->
      <a href="http://localhost:8000/feeds" v-if="!this.$store.state.iconState.home">
        <svg  aria-label="Home" class="_8-yf5 " fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M45.3 48H30c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2-4.6-4.6-4.6s-4.6 2-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.5-.6 2.1 0l21.5 21.5c.4.4.6 1.1.3 1.6 0 .1-.1.1-.1.2v22.8c.1.8-.6 1.5-1.4 1.5zm-13.8-3h12.3V23.4L24 3.6l-20 20V45h12.3V34.2c0-4.3 3.3-7.6 7.6-7.6s7.6 3.3 7.6 7.6V45z"></path></svg>
      </a>
     <a href="#" v-else> 
       <svg  aria-label="Home" class="_8-yf5 " fill="#262626" height="22" viewBox="0 0 48 48" width="22"><path d="M45.5 48H30.1c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2.1-4.6-4.6-4.6s-4.6 2.1-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.6-.6 2.1 0l21.5 21.5c.3.3.4.7.4 1.1v23.5c.1.8-.6 1.5-1.4 1.5z"></path></svg>
      </a>
    <!-- search icon -->
    <svg ref="search_icon_path" @click="changeSearchIcon"  aria-label="Search &amp; Explore" class="_8-yf5 " fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path :d="searchIconHead"></path><path :d="searchIconTail"></path></svg>
    <!-- <svg aria-label="Search &amp; Explore" class="_8-yf5 " fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M47.6 44L35.8 32.2C38.4 28.9 40 24.6 40 20 40 9 31 0 20 0S0 9 0 20s9 20 20 20c4.6 0 8.9-1.6 12.2-4.2L44 47.6c.6.6 1.5.6 2.1 0l1.4-1.4c.6-.6.6-1.6.1-2.2zM20 35c-8.3 0-15-6.7-15-15S11.7 5 20 5s15 6.7 15 15-6.7 15-15 15z"></path></svg> -->

    <b-modal id="search_modal" ref="search_modal"  modal-class="sharePost_Modal"  hide-header hide-footer >
        <ul class="sharePostUl position-relative">
          <span  @click="changeSearchIcon" class="close_icon"></span> 
          <searchComponent :users="users" :sessionUser="sessionUser" ></searchComponent>
        </ul>
    </b-modal> 

    <!-- add post icon -->
    <svg aria-label="New Post"  class="_8-yf5 openWidget" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path ref="search_icon_path" d="M31.8 48H16.2c-6.6 0-9.6-1.6-12.1-4C1.6 41.4 0 38.4 0 31.8V16.2C0 9.6 1.6 6.6 4 4.1 6.6 1.6 9.6 0 16.2 0h15.6c6.6 0 9.6 1.6 12.1 4C46.4 6.6 48 9.6 48 16.2v15.6c0 6.6-1.6 9.6-4 12.1-2.6 2.5-5.6 4.1-12.2 4.1zM16.2 3C10 3 7.8 4.6 6.1 6.2 4.6 7.8 3 10 3 16.2v15.6c0 6.2 1.6 8.4 3.2 10.1 1.6 1.6 3.8 3.1 10 3.1h15.6c6.2 0 8.4-1.6 10.1-3.2 1.6-1.6 3.1-3.8 3.1-10V16.2c0-6.2-1.6-8.4-3.2-10.1C40.2 4.6 38 3 31.8 3H16.2z"></path><path d="M36.3 25.5H11.7c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5h24.6c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5z"></path><path d="M24 37.8c-.8 0-1.5-.7-1.5-1.5V11.7c0-.8.7-1.5 1.5-1.5s1.5.7 1.5 1.5v24.6c0 .8-.7 1.5-1.5 1.5z"></path></svg>
    

      <b-dropdown ref="activity_dropdown_dropup"  id="activity_dropdown_dropup" dropup text="Drop-Up" size="sm"  variant="link" toggle-class="text-decoration-none" no-caret>
        <div  v-if="notifications.length > 0" class="d-flex flex-column activity_ellipsis_wrapper">
          <b-dropdown ref="subMenu" id="subMenu" text="Dropdown Button" variant="link" toggle-class="text-decoration-none" class="m-md-2 align-self-end" no-caret>
            <template v-slot:button-content>
              <i class="fas fa-ellipsis-h text-secondary mr-2 subMenuToggle" ></i>
            </template>

            <b-dropdown-item @click="markAllRead" >Mark all as read</b-dropdown-item>
            <b-dropdown-item @click="markAllUnread">Mark all as unread</b-dropdown-item>
           
          </b-dropdown>
        </div>
        <b-dropdown-item v-else size="lg" >
          <span class="text-secondary">You have no notifications</span> 
        </b-dropdown-item>
        <template v-slot:button-content>
           <!-- activity icon -->
          <svg  aria-label="Activity" class="_8-yf5 " fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path ref="activity_icon_path" d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg>    
          <!-- <svg aria-label="Activity" class="_8-yf5 " fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg> -->
          <span v-if="unreadNotifications.length > 0" class="notif_count">{{unreadNotifications.length}}</span>
        </template>
        <b-dropdown-item  v-for="(notification, key) in notifications" :key="key" size="lg" class="position-relative">
          <div @click="notificationRedirect" :data-notifId="notification.id"  class="d-flex">
            <img v-if="notification.read_at" class="pfp_notif rounded-circle mr-2 " :src="notification.notifier.pfp">
            <img v-else class="pfp_notif rounded-circle mr-2 " :src="notification.notifier.pfp"  style="border: 2px solid #007bff;">
            <div class="d-flex flex-column">
              <div class="notif_message">
                <span class=" font-weight-bold ">{{notification.notifier.username}}</span>
                <span v-if="notification.read_at" class="  "> {{notification.data.notification_message}} </span>
                <span v-else class=" notif_font "> {{notification.data.notification_message}} </span>
              </div>
              <span v-if="notification.read_at" class="text-secondary">{{notification.created_at}}</span>
              <span v-else class="text-primary notif_font">{{notification.created_at}}</span>
            </div>
          </div>
          
          <div v-if="notification.read_at" @click.stop="markUnread" :data-notifId="notification.id" class="mark_read_unread bg-secondary">  
            <div class="tooltip_notif">
              <span class="tooltip_text">Read</span>
            </div>
          </div>
          <div v-else @click.stop="markRead" :data-notifId="notification.id"  class="mark_read_unread bg-primary">
            <div class="tooltip_notif">
              <span class="tooltip_text">Unread</span>
            </div>
          </div>
        </b-dropdown-item>
        
      </b-dropdown>
    

    <div>
      <b-dropdown id="dropdown-dropup" dropup text="Drop-Up" size="sm"  variant="link" toggle-class="text-decoration-none" no-caret>
        <template v-slot:button-content>
          <img class="footer_pfp" :src="sessionUser.pfp">
        </template>

        <b-dropdown-item size="sm" :href="publicPath+sessionUserId+'/profile'">Profile</b-dropdown-item>
        <b-dropdown-item size="sm" @click="logout" variant="danger" active-class="bg-danger">Log Out</b-dropdown-item>
      </b-dropdown>
    </div>

    <b-modal id="modal_post_form" ref="modal_post_form" class="settings_Modal" title="Add a Post" hide-footer centered>
        <validation :errors="validationErrors" v-if="validationErrors"></validation>
        <form class="addPost_form" @submit.prevent="addPost" method="post" action="" enctype="multipart/form-data">
          <b-form-group>
            <div class="form-group d-flex flex-column">
              <input type="hidden" name="type" v-model="postForm.mediaType">
              <cld-image v-if="postForm.resourceType == 'image'" dpr="auto" responsive="width" width="auto" crop="scale" :publicId="postForm.publicId" class="my-2">
                <cld-transformation quality="auto" fetchFormat="auto" />
              </cld-image>
              <video class="video img ml-auto bg-dark" v-else-if="postForm.resourceType == 'video'" controls muted>
                <source :src="postForm.urlImg"/>
              </video>
              <div class="px-2">
                <b-form-select v-model="postForm.mediaType" :options="postForm.options" size="sm" class="mt-2 mb-1"></b-form-select>
                <b-form-textarea v-model="postForm.postCaption" name="description" placeholder="Add a caption ..."  cols="30" rows="1" max-rows="10" class="form-control addPostTxt mb-2"></b-form-textarea>
              </div>
              <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
          </b-form-group>
        </form>
      </b-modal> 

  </footer>

</div>
  


</template>

 <script>
 import SearchComponent from './SearchComponent';
 var moment = require("moment");

  export default {
    data() {
        return {
          widget:'',
          postForm: {
            postMedia: null,
            publicId: null,
            resourceType:null,
            imgPreview: null,
            videoPreview: null,
            postCaption: '',
            urlImg:null,
            mediaType: '',
            options: [
              { value: '' , text: 'Select an option' },
              { value: 'post', text: 'Publish as a Post' },
              { value: 'story', text: 'Publish as a Story' },
            ]
          },
          validationErrors: '',
          publicPath: 'http://localhost:8000/',
          navbarState: null,
          users: '',
          sessionUserId: this.$sessionUserId,
          sessionUser: '',
          notifications : '',
          notificationFeed: '',
          unreadNotifications : [],
          observer:'',
          searchIconHead: 'M20 40C9 40 0 31 0 20S9 0 20 0s20 9 20 20-9 20-20 20zm0-37C10.6 3 3 10.6 3 20s7.6 17 17 17 17-7.6 17-17S29.4 3 20 3z',
          searchIconTail: 'M46.6 48.1c-.4 0-.8-.1-1.1-.4L32 34.2c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l13.5 13.5c.6.6.6 1.5 0 2.1-.2.3-.6.4-1 .4z',
          
     }
        
    },

    created: function () {
      this.markAllRead = _.debounce(this.markAllRead, 300)
      this.markAllUnread = _.debounce(this.markAllUnread, 300)
      this.markRead = _.debounce(this.markRead, 300)
      this.markUnread = _.debounce(this.markUnread, 300)
      if (this.sessionUserId) {
        axios
        .get(this.publicPath+"users/"+this.sessionUserId)
        .then((data) => { 
            this.sessionUser = data.data;
        })
        .catch((err) => {});

        axios
        .get(this.publicPath+"users")
        .then((data) => { 
            this.users = data.data;
        })
        .catch((err) => {});

        axios
        .get(this.publicPath+this.sessionUserId+'/notifications/activity')
        .then((data) => { 
          this.notifications = data.data
          if (this.$refs.activity_dropdown_dropup.$el.classList.value.includes('show')) {
          this.$refs.activity_dropdown_dropup.hide(true)
          this.$refs.activity_dropdown_dropup.show(true)
          }
          this.unreadNotifications = []
          this.notifications.forEach(notification => {
            if (notification.read_at == null) {
              this.unreadNotifications.push(notification)
            }
            notification.created_at = moment(notification.created_at).fromNow();
          });
          // this.notificationFeed.push(...this.notifications)
        })
        .catch((err) => {});
      }
      
    },

    methods: {
      addPost(event) {
      
      // let Blob = this.dataURLToBlob(this.$store.state.image.croppedImage) 
      // this.postForm.postMedia = this.blobToFile(Blob)  
      let formData = new FormData();
      formData.append('media_file', this.postForm.urlImg);
      formData.append('media_type', this.postForm.resourceType);
      formData.append('description', this.postForm.postCaption);
      formData.append('type', this.postForm.mediaType);
      formData.append('user_id', this.sessionUser.id);
      axios({
        method: 'post',
        url: this.publicPath+'posts',
        data: formData,
      }).then((response) => {
        let post = response.data
        post.likes = []
        post.created_at = moment(post.created_at).fromNow();
        post.showStatus = "Show More";
        post.likePath = "M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z";
        post.likeColor = "#262626";
        post.savePath = "M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z";
        post.saveColor = "#262626";
      
        this.$refs['modal_post_form'].hide()
        this.postFeed[0].splice(0, 0, post)

        this.postForm.imgPreview = null;
        this.postForm.videoPreview = null;
        this.postForm.postCaption = ''

      }).catch((error) => {
        if (error.response.status == 422){
        this.validationErrors = error.response.data.errors;
        }


      });

      
    },


      notificationRedirect(event){

        if ($(event.currentTarget)[0].nextElementSibling.className.includes('bg-primary')) {
          $(event.currentTarget)[0].nextElementSibling.click()
          console.log('test');
        }
        var notifId = event.currentTarget.attributes[0].nodeValue
        var notifType
        var objectId 
        var postId 
        setTimeout(() => {
          console.log('object');
          this.notifications.forEach(notification => {
            objectId = notification.data.object_id
            notifType = notification.type.split('\\')[2];
            if (notification.id == notifId) {
              if ((notifType == 'Like') || (notifType == 'Comment') || (notifType == 'Reply')) {
                if (notification.data.post_id != undefined) {
                  postId = notification.data.post_id
                  console.log(postId, objectId);
                  window.location.replace(this.publicPath+'post/'+postId+'?'+objectId)
                } else {
                  window.location.replace(this.publicPath+'post/'+objectId)
                }              
              } else if (notifType == 'Follow') {
                window.location.replace(this.publicPath+objectId+'/profile')
              }
            }
          })

        }, 400)

      },

      updateFooter() {
        if (this.sessionUserId) {
        axios
        .get(this.publicPath+"users/"+this.sessionUserId)
        .then((data) => { 
            this.sessionUser = data.data;
        })
        .catch((err) => {});

        axios
        .get(this.publicPath+"users")
        .then((data) => { 
            this.users = data.data;
        })
        .catch((err) => {});

        axios
        .get(this.publicPath+this.sessionUserId+'/notifications/activity')
        .then((data) => { 
          this.notifications = data.data
          this.unreadNotifications = []
           this.notifications.forEach(notification => {
            if (notification.read_at == null) {
              this.unreadNotifications.push(notification)
            }
             notification.created_at = moment(notification.created_at).fromNow();
          });
         
          // this.notificationFeed.push(...this.notifications)
        })
        .catch((err) => {});
      }

      },

      changeSearchIcon(){
          var slimSearchIconHead='M20 40C9 40 0 31 0 20S9 0 20 0s20 9 20 20-9 20-20 20zm0-37C10.6 3 3 10.6 3 20s7.6 17 17 17 17-7.6 17-17S29.4 3 20 3z'
          var slimSearchIconTail='M46.6 48.1c-.4 0-.8-.1-1.1-.4L32 34.2c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l13.5 13.5c.6.6.6 1.5 0 2.1-.2.3-.6.4-1 .4z'
          var thickSearchIcon = 'M47.6 44L35.8 32.2C38.4 28.9 40 24.6 40 20 40 9 31 0 20 0S0 9 0 20s9 20 20 20c4.6 0 8.9-1.6 12.2-4.2L44 47.6c.6.6 1.5.6 2.1 0l1.4-1.4c.6-.6.6-1.6.1-2.2zM20 35c-8.3 0-15-6.7-15-15S11.7 5 20 5s15 6.7 15 15-6.7 15-15 15z'
        if (this.searchIconHead==slimSearchIconHead) {
          this.searchIconHead=thickSearchIcon
          this.searchIconTail=''
          this.$refs['search_modal'].show()   
        } else {
          this.searchIconHead=slimSearchIconHead
          this.searchIconTail=slimSearchIconTail  
           this.$refs['search_modal'].hide()   
        }
      },

      markAllRead(event){
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/read',
        }).then((response) => {
          this.updateFooter()
        })
      },
      
      markAllUnread(event){
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/unread',
        }).then((response) => {
          this.updateFooter()
        })
      },

      markRead(event){
        var targetId = event.target.attributes[0].nodeValue
        console.log(targetId);
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/'+targetId+'/read',
        }).then((response) => {
          this.updateFooter()
        })
      },
      
      markUnread(event){
        var targetId = event.target.attributes[0].nodeValue
        console.log(targetId);
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/'+targetId+'/unread',
        }).then((response) => {
          this.updateFooter()
        })
      },
      
      
      logout() {
        axios
        .post(this.publicPath+"logout")
        .then((data) => { 
            window.location.replace(this.publicPath+"login")
        })
        .catch((err) => {});
      },
     
      onClassChange(classAttrValue) {
        const classList = classAttrValue.split(' ');
        if (classList.includes('show')) {
          this.$refs.activity_icon_path.attributes[0].nodeValue ='M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
        } else {
          this.$refs.activity_icon_path.attributes[0].nodeValue ='M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
        }
      },
    },
    mounted: function () {
      var widget = cloudinary.createUploadWidget( 
      { 
          cloudName: "resize", 
          uploadPreset: "resize_preset", 
          showAdvancedOptions: true,
          googleApiKey: "<image_search_google_api_key>",
          cropping: true,
          multiple: false,
          sources: [
            "local",
            "url",
            "camera",
            "image_search",
            "google_drive",
            "facebook",
            "dropbox",
            "instagram",
            "shutterstock"
          ],
          defaultSource: "local",
          styles: {
              palette: {
                  window: "#000000",
                  sourceBg: "#000000",
                  windowBorder: "#8E9FBF",
                  tabIcon: "#FFFFFF",
                  inactiveTabIcon: "#8E9FBF",
                  menuIcons: "#2AD9FF",
                  link: "#08C0FF",
                  action: "#336BFF",
                  inProgress: "#00BFFF",
                  complete: "#33ff00",
                  error: "#EA2727",
                  textDark: "#000000",
                  textLight: "#FFFFFF"
              },
              fonts: {
                  default: null,
                  "'Space Mono', monospace": {
                      url: "https://fonts.googleapis.com/css?family=Space+Mono",
                      active: true
                  }
              }
          }
      },
        (error, result) => { 
                this.postForm.postMedia = result
            if (typeof result.info.files != 'undefined') {
              this.postForm.publicId = result.info.files[0].uploadInfo.public_id
              this.postForm.resourceType = result.info.files[0].uploadInfo.resource_type
              this.postForm.fileName = result.info.files[0].name
              this.postForm.urlImg = result.info.files[0].uploadInfo.url
              this.$bvModal.show('modal_post_form')
            }
        });

    this.widget = widget
    $('.openWidget').click(function() {
      widget.open();
    });

       this.$root.$on('bv::dropdown::show', bvEvent => {
        if(bvEvent.componentId === 'subMenu') {
          this.isDropdown2Visible = true;
        }
        })
      this.$root.$on('bv::dropdown::hide', bvEvent => {
        if(bvEvent.componentId === 'subMenu') {
          this.isDropdown2Visible = false;
        }
        if(this.isDropdown2Visible) {
          bvEvent.preventDefault()
        }
        })
      
     if(this.sessionUserId) { 
    //    Echo.private('App.User.' + this.sessionUserId)
    // .notification((notification) => {
    //     console.log(notification);
    // });

       Echo.private(`activity.${this.sessionUserId}`)
         .listen('CommentCreated', (e) => {
            this.notifications.push(e.realTime_notification);
            this.updateFooter()
        })
          .listen('ReplyCreated', (e) => {
            this.notifications.push(e.realTime_notification);
            this.updateFooter()
        })
         .listen('UserFollowed', (e) => {
            this.notifications.push(e.realTime_notification);
            this.updateFooter()
        })
          .listen('LikeEvent', (e) => {
            this.notifications.push(e.realTime_notification);
            this.updateFooter()
        })
    }  

        this.observer = new MutationObserver(mutations => {
          for (const m of mutations) {
            const newValue = m.target.getAttribute(m.attributeName);
            this.$nextTick(() => {
              this.onClassChange(newValue, m.oldValue);
            });
          }
        });

        this.observer.observe(document.querySelectorAll("#activity_dropdown_dropup")[0], {
          attributes: true,
          attributeOldValue : true,
          attributeFilter: ['class'],
        });

    },

    beforeDestroy() {
      this.observer.disconnect();
    }, 

    components: {
    SearchComponent,
  },
    
  }
</script>