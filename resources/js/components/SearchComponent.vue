<template>
  <div class="container">
    <div id="search">
        <div class="search-wrapper my-2">
            <input type="text" v-model="search" placeholder="Search title.."/>
                <label>Search title:</label>
        </div>
        <div class="wrapper">
            <li v-for="(user, key) in filteredList" :key="key" class="d-flex justify-content-between">
              <div>
                <img  @click="goToProfile" :data-userId="user.id" class="pfp  rounded-circle mr-2" :src="user.pfp"/>
                <span @click="goToProfile" :data-userId="user.id" class="username font-weight-bold">{{user.username}}</span>
              </div>
              <button v-if="post" :id="'postShareId'+post.id" :data-contactId="user.id" :data-postId="post.id" @click="sendPost" class="btn-primary py-1 px-4  border-0 rounded  h-25 align-self-center">Send</button>
              <button v-else :id="'userId'+user.id" :data-followerId="user.id" @click="followUnfollow" class="btn btn-primary" 
                v-text="`${sessionUser.followed.includes(user.id) ? 'Unfollow' : 'Follow'}`">
              </button>
            </li>
        </div>
        
    </div>
  </div>
</template>

<script>
export default {
    props: ['users', 'post', 'sessionUser'],
  
    data () {
      return {
        search: '',
        }
    },

    methods :{
      goToProfile(event) {
        var userId = event.target.attributes[0].nodeValue;
        window.location.replace(this.publicPath+userId+'/profile')
      },

      sendPost (event) {
        var targetcontactId = event.target.attributes[1].nodeValue
        var targetPostId = event.target.attributes[2].nodeValue

        axios.post('/conversation/send', {
            contactId: targetcontactId,
            sharedPostId: targetPostId
        }).then((response)=>{
          $(event.target).html('Sent').addClass('btn-success').attr('disabled', 'disabled')
          // $(this.$refs)[0]['my_sharePostModal'+targetPostId][0].hide() 
        }).catch((err) => {
        });
      },

       followUnfollow(event) {
            let userFollowId;

            if (typeof $(event.target).attr("id") == 'undefined') {
                userFollowId = $(event.target.parentElement).attr("id");
            } else {
                userFollowId = $(event.target).attr("id");
            }

            //  check if the post is already liked by the user
            if (this.sessionUser.followed.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))) {
                // apply the laravel unlike function
                axios
                    .get(this.publicPath+"users/" +$("#" + userFollowId)[0].attributes[1].nodeValue +"/" 
                    +this.sessionUser.id +"/unfollow")
                    .then((response) => {
                    
                    // get the index of the user id we want to delete
                    let index = this.sessionUser.followed.indexOf(
                        parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                    );

                    if (this.sessionUser.followed.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                        //  remove it from the followedUsersId array
                        this.sessionUser.followed.splice(index, 1)
                        $("#" + userFollowId)[0].innerHTML = 'Follow' 
                    
                    }
                    
                    
                    });
            } else {
                // apply the laravel follow function
                axios
                .get(this.publicPath+"users/" + $("#" + userFollowId)[0].attributes[1].nodeValue + "/" + this.sessionUser.id + "/follow" )
                .then((response) => {
                    
                    if (!this.sessionUser.followed.includes(parseInt($("#" + userFollowId)[0].attributes[1].nodeValue))){
                        // add the user id to the followedUsersId array
                        this.sessionUser.followed.push(
                        parseInt($("#" + userFollowId)[0].attributes[1].nodeValue)
                        )
                        $("#" + userFollowId)[0].innerHTML = 'Unfollow' 
                                    

                    }
                    
                });
                        
                
                
            }
        },
    },

    mounted: function(){
      

    },

    computed: {
      
    filteredList() {
      console.log(this.users);
      return this.users.filter(user => {
        return user.username.toLowerCase().includes(this.search.toLowerCase())
      })
    }
  }
}
</script>

<style lang="scss">
html, body {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  margin-top: 16px;
  margin-bottom: 16px;
}

div#search {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  
  .search-wrapper {
    position: relative;
    label {
      position: absolute;
      font-size: 12px;
      color: rgba(0,0,0,.50);
      top: 8px;
      left: 12px;
      z-index: -1;
      transition: .15s all ease-in-out;
    }
    input {
      padding: 4px 12px;
      color: rgba(0,0,0,.70);
      border: 1px solid rgba(0,0,0,.12);
      transition: .15s all ease-in-out;
      background: white;
      &:focus {
        outline: none;
        transform: scale(1.05);
        & + label  {
          font-size: 10px;
          transform: translateY(-24px) translateX(-12px);
        }
      }
      &::-webkit-input-placeholder {
          font-size: 12px;
          color: rgba(0,0,0,.50);
          font-weight: 100;
      }
    }
  }

//   .wrapper {
//     display: flex;
//     max-width: 444px;
//     flex-wrap: wrap;
//     padding-top: 12px;
//   }

//   .card {
//     box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 6px, rgba(0, 0, 0, 0.117647) 0px 1px 4px;
//     max-width: 124px;
//     margin: 12px;
//     transition: .15s all ease-in-out;
//     &:hover {
//       transform: scale(1.1);
//     }
//     a {
//       text-decoration: none;
//       padding: 12px;
//       color: #03A9F4;
//       font-size: 24px;
//       display: flex;
//       flex-direction: column;
//       align-items: center;
//       img {
//         height: 100px;
//       }
//       small {
//         font-size: 10px;
//         padding: 4px;
//       }
//     }
//   }



  .hotpink {
    background: hotpink;
  }

  .green {
    background: green;
  }

  .box {
    width: 100px;
    height: 100px;
    border: 1px solid rgba(0,0,0,.12);
  }
}
</style>
