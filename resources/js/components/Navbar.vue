<template>
  <nav v-if="!storyPage" class="navbar navbar-expand-lg navbar-light bg-light  py-0 px-md-0 ">
    <div :class="`nav_content w-full flex justify-between items-center ${chatPage ? 'sm:max-w-full sm:px-10' : 'sm:mx-auto sm:w-3/4'}`">
      <!-- camera icon -->
      <svg aria-label="New Story" class="_8-yf5 openWidget" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path clip-rule="evenodd" d="M38.5 46h-29c-5 0-9-4-9-9V17c0-5 4-9 9-9h1.1c1.1 0 2.2-.6 2.7-1.7l.5-1c1-2 3.1-3.3 5.4-3.3h9.6c2.3 0 4.4 1.3 5.4 3.3l.5 1c.5 1 1.5 1.7 2.7 1.7h1.1c5 0 9 4 9 9v20c0 5-4 9-9 9zm6-29c0-3.3-2.7-6-6-6h-1.1C35.1 11 33 9.7 32 7.7l-.5-1C31 5.6 29.9 5 28.8 5h-9.6c-1.1 0-2.2.6-2.7 1.7l-.5 1c-1 2-3.1 3.3-5.4 3.3H9.5c-3.3 0-6 2.7-6 6v20c0 3.3 2.7 6 6 6h29c3.3 0 6-2.7 6-6V17zM24 38c-6.4 0-11.5-5.1-11.5-11.5S17.6 15 24 15s11.5 5.1 11.5 11.5S30.4 38 24 38zm0-20c-4.7 0-8.5 3.8-8.5 8.5S19.3 35 24 35s8.5-3.8 8.5-8.5S28.7 18 24 18z" fill-rule="evenodd"></path></svg>
          <!-- brand title -->
      <router-link to="/" class="navbar-brand py-0 mr-0">Instaclone</router-link>
      <!-- direct message icon -->
      <div class="sm:hidden">
          <span v-if="!chatPage" @click="markAllMessagesRead"  class="sm:hidden cursor-pointer position-relative">
            <svg aria-label="Share Post" class="mx-2" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
              <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path>
            </svg>
            <span v-if="unreadNotifications.length > 0" class="notif_count">{{unreadNotifications.length}}</span>
          </span>
          <svg v-else aria-label="Direct" class="mx-2 " fill="#262626" height="22" viewBox="0 0 48 48" width="22"><path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l13.2 13c.5.4 1.1.6 1.7.3l16.6-8c.7-.3 1.6-.1 2 .5.4.7.2 1.6-.5 2l-15.6 9.9c-.5.3-.8 1-.7 1.6l4.6 19c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.5-.5.5-1.1.2-1.6z"></path></svg>
      </div>
      <div class="hidden  sm:flex sm:items-center">
        <!-- home icon -->

          <router-link to="/" v-if="!homePage">
            <svg  aria-label="Home" class="_8-yf5 mx-2  " fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M45.3 48H30c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2-4.6-4.6-4.6s-4.6 2-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.5-.6 2.1 0l21.5 21.5c.4.4.6 1.1.3 1.6 0 .1-.1.1-.1.2v22.8c.1.8-.6 1.5-1.4 1.5zm-13.8-3h12.3V23.4L24 3.6l-20 20V45h12.3V34.2c0-4.3 3.3-7.6 7.6-7.6s7.6 3.3 7.6 7.6V45z"></path></svg>
          </router-link>
          <a href="#" v-else> 
          <svg  aria-label="Home" class="_8-yf5 mx-2  " fill="#262626" height="22" viewBox="0 0 48 48" width="22"><path d="M45.5 48H30.1c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2.1-4.6-4.6-4.6s-4.6 2.1-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.6-.6 2.1 0l21.5 21.5c.3.3.4.7.4 1.1v23.5c.1.8-.6 1.5-1.4 1.5z"></path></svg>
          </a>

        <!-- direct message icon -->
          <span v-if="!chatPage" @click="markAllMessagesRead"  class=" cursor-pointer position-relative">
            <svg aria-label="Share Post" class="mx-2" fill="#262626" height="24" viewBox="0 0 48 48" width="24">
              <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path>
            </svg>
            <span v-if="unreadNotifications.length > 0" class="notif_count">{{unreadNotifications.length}}</span>
          </span>
          <svg v-else aria-label="Direct" class="mx-2" fill="#262626" height="22" viewBox="0 0 48 48" width="22"><path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l13.2 13c.5.4 1.1.6 1.7.3l16.6-8c.7-.3 1.6-.1 2 .5.4.7.2 1.6-.5 2l-15.6 9.9c-.5.3-.8 1-.7 1.6l4.6 19c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.5-.5.5-1.1.2-1.6z"></path></svg>


        <!-- activity -->
          <b-dropdown ref="activity_dropdown_dropup"  id="activity_dropdown_dropup" dropup text="Drop-Up" size="sm"  variant="link" toggle-class="text-decoration-none" no-caret>
            <div  v-if="notifications.length > 0" class="d-flex flex-column activity_ellipsis_wrapper">
              <b-dropdown ref="subMenu" id="subMenu" text="Dropdown Button" variant="link" toggle-class="text-decoration-none" class="z-10 align-self-end" no-caret>
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
              <div class="notif_count" >
              <span  v-if="unreadNotifications.length > 0" class="relative inline-flex rounded-full h-4 w-4 bg-red-650 text-center" >
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-650 opacity-75"></span> 
                <span class="mx-auto">{{unreadNotifications.length}}</span>
              </span>
              </div>
            </template>
            <b-dropdown-item  v-for="(notification, key) in notifications" :key="key" size="lg" class="position-relative">
              <div @click="notificationRedirect" :data-notifId="notification.id"  class="d-flex">
                <img v-if="notification.read_at" class="pfp_notif rounded-circle mr-2 " :src="notification.notifier.pfp">
                <img v-else class="pfp_notif rounded-circle mr-2 " :src="notification.notifier.pfp"  style="border : 2px solid #007bff;">
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
        
          <!-- session user -->
          <div>
            <b-dropdown id="dropdown-dropup" dropup text="Drop-Up" size="sm"  variant="link" toggle-class="text-decoration-none pr-0" no-caret>
              <template v-slot:button-content>
                <img class="footer_pfp" :src="sessionUser.pfp">
              </template>
              <b-dropdown-item size="sm" :to="sessionUserId+'/profile'">Profile</b-dropdown-item>
              <b-dropdown-item size="sm" @click="logout" variant="danger" active-class="bg-danger">Log Out</b-dropdown-item>
            </b-dropdown>
          </div>
      </div>
    </div>
  </nav>



</template>
 <script>
 var moment = require("moment");
  export default {
    data() {
        return {
          publicPath: 'http://localhost:8000/',
          navbarState: null,
          sessionUserId: this.$sessionUserId,
          notifications: '',
          unreadNotifications : [],
          messageNotifications: '',
          unreadMessageNotifications : [],
     }
        
    },

    created: function () {
      
      if (this.sessionUserId) {
        
        axios
        .get(this.publicPath+this.sessionUserId+'/notifications/messages')
        .then((data) => { 
          this.messageNotifications = data.data
           this.unreadMessageNotifications = []
          this.notifications.forEach(notification => {
            if (notification.read_at == null) {
              this.unreadNotifications.push(notification)
            }
          });
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

      
      updateNav(){
         axios
        .get(this.publicPath+this.sessionUserId+'/notifications/messages')
        .then((data) => { 
          this.messageNotifications = data.data
           this.unreadMessageNotifications = []
          this.notifications.forEach(notification => {
            if (notification.read_at == null) {
              this.unreadNotifications.push(notification)
            }
          });
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
      },

      markAllMessagesRead(event){
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/messagesRead',
        }).then((response) => {
          if (window.location.href != "http://localhost:8000/chat") {
            this.$router.push({path : '/chat', params : this.sessionUser})
            // window.location.replace("http://localhost:8000/chat")
          }
          this.updateNav()
        })
      },

      onClassChange(classAttrValue) {
        const classList = classAttrValue.split(' ');
        if (classList.includes('show')) {
          this.$refs.activity_icon_path.attributes[0].nodeValue ='M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
        } else {
          this.$refs.activity_icon_path.attributes[0].nodeValue ='M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z'
        }
      },

      logout() {
        axios
        .post(this.publicPath+"logout")
        .then((data) => { 
            window.location.replace(this.publicPath+"login")
        })
        .catch((err) => {});
      },

       markAllRead(event){
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/read',
        }).then((response) => {
          this.updateNav()
        })
      },
      
      markAllUnread(event){
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/unread',
        }).then((response) => {
          this.updateNav()
        })
      },

      markRead(event){
        var targetId = event.target.attributes[0].nodeValue
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/'+targetId+'/read',
        }).then((response) => {
          this.updateNav()
        })
      },
      
      markUnread(event){
        var targetId = event.target.attributes[0].nodeValue
        axios({
          method: 'patch',
          url: this.publicPath+'notifications/'+this.sessionUserId+'/'+targetId+'/unread',
        }).then((response) => {
          this.updateNav()
        })
      },
      
      notificationRedirect(event){

        if ($(event.currentTarget)[0].nextElementSibling.className.includes('bg-primary')) {
          $(event.currentTarget)[0].nextElementSibling.click()
        }
        var notifId = event.currentTarget.attributes[0].nodeValue
        var notifType
        var objectId 
        var postId 
        var originalCommentId
        setTimeout(() => {
          this.notifications.forEach(notification => {
            objectId = notification.data.object_id
            notifType = notification.type.split('\\')[2];
            if (notification.id == notifId) {
              if ((notifType == 'Like') || (notifType == 'Comment') || (notifType == 'Reply')) {
                // check if it's a comment
                if (notification.data.post_id != undefined) {
                  postId = notification.data.post_id
                  // check if it's a reply
                  if (notification.data.original_comment_id != undefined) {
                    originalCommentId = notification.data.original_comment_id
                    this.$router.push({path : '/post/'+postId+'?'+objectId+'?'+originalCommentId})
                    // window.location.replace(this.publicPath+'post/'+postId+'?'+objectId+'?'+originalCommentId)
                  } else {
                    this.$router.push({path : '/post/'+postId+'?'+objectId})
                    // window.location.replace(this.publicPath+'post/'+postId+'?'+objectId)
                  }
                  
                } else {
                  this.$router.push({path : '/post/'+objectId})
                  // window.location.replace(this.publicPath+'post/'+objectId)
                }              
              } else if (notifType == 'Follow') {
                this.$router.push({path : '/'+objectId+'/profile'})
                // window.location.replace(this.publicPath+objectId+'/profile')
              }
            }
          })

        }, 400)

      },

    },

    mounted: function () {
        Echo.private('messages.'+this.sessionUserId)
        .listen('MessageSent', (e) => {
          this.unreadNotifications.push(e)
        })

        if(this.sessionUserId) { 
          Echo.private(`activity.${this.sessionUserId}`)
            .listen('CommentCreated', (e) => {
                this.notifications.push(e.realTime_notification);
                this.updateNav()
            })
              .listen('ReplyCreated', (e) => {
                this.notifications.push(e.realTime_notification);
                this.updateNav()
            })
            .listen('UserFollowed', (e) => {
                this.notifications.push(e.realTime_notification);
                this.updateNav()
            })
              .listen('LikeEvent', (e) => {
                this.notifications.push(e.realTime_notification);
                this.updateNav()
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
        
    computed: {
      homePage() {
        if(this.$route.name == "feeds") {
          return true
        } else {
          return false
        }
      },

      chatPage() {
        if(this.$route.name == "chat") {
          return true
        } else {
          return false
        }
      },

      storyPage() {
        if(this.$route.name == "stories") {
          return true
        } else {
          return false
        }
      },

      sessionUser() {
              return this.$store.getters.getCurrentSessionUser           
      }
    },
    
  }
</script>
               