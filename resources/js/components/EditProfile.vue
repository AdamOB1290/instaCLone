<template>
    <div class="h-full edit_profile_root pb-3 pt-3">
        <div class="edit_profile_wrapper bg-white sm:w-3/4 md:border md:border-black-900 mx-auto py-3 sm:pt-4 position-relative ">
            <i @click="backToProfile" class="fas fa-arrow-left ml-2 exit_edit_profile cursor-pointer"></i>
            <div class="edit_profile_content_wrapper sm:shadow-2xl mx-auto w-content px-5 py-4 sm:mt-3 rounded mb-5">
                <div class="d-flex justify-content-center align-items-center flex-column  ">
                    <!-- <cld-image  dpr="auto" responsive="width" width="auto" crop="scale" :src="form.pfp.url" :publicId="form.pfp.publicId"  class="slider-image my-2">
                    <cld-transformation border="3px_solid_rgb:00390b" radius="20" />
                    <cld-transformation quality="auto" fetchFormat="auto" />
                    </cld-image> -->
                    <div id="openWidgetPfp" class="profile_avatar_wrapper d-flex position-relative">
                        <img class="slider-image " :src="sessionUser.pfp" >
                        <i class="plusStory fas fa-plus-circle position-absolute text-primary"></i>
                    </div>
                    <span id="openWidgetPfp" class="text-primary">Change Profile Photo</span>
                </div>

                <validation :errors="validationErrors" :success="validationSuccess" v-if="validationErrors || validationSuccess"></validation>

                <b-container  fluid>
                    <b-form class="max-w-md mx-auto " @submit="updateProfile">
                        <b-row class="flex flex-col my-1 w-64" v-for="(input, key) in form.profileInputs" :key="key">                    
                            <b-col  class="px-0" >
                                <label :for="`input-${input.label}`"><code>{{ input.label }}</code>:</label>
                            </b-col>
                            <b-col  class="px-0">
                                <b-form-input :id="`input-${input.label}`" v-model="input.vModelVal" :type="input.type" :placeholder="`${sessionUser[key] ? sessionUser[key] : 'Enter your '+input.label}`"></b-form-input>
                            </b-col>
                        </b-row>
                        <b-row class="flex flex-col my-1 ">                    
                            <b-col class="px-0" >
                                <label for="select-gender"><code>Password</code>:</label>
                            </b-col>
                            <b-col  class="position-relative px-0">
                                <b-form-input :type="form.password.type" v-model="form.password.vModelVal" class="pr-4" placeholder="Enter a New Password" ></b-form-input>
                                <i @click="switchVisibility" :class="form.password.eyeIcon"></i>
                            </b-col>
                        </b-row>
                        <b-row class="flex flex-col my-1  ">                    
                            <b-col class="px-0" >
                                <label for="select-gender"><code>Gender</code>:</label>
                            </b-col>
                            <b-col class="px-0" >
                                <b-form-select id="select-gender" v-model="form.gender" :options="genders"></b-form-select>
                            </b-col>
                        </b-row>
                        <div class="flex w-full justify-end mt-4">
                            <b-button class=" py-1" ref="submitForm" type="submit" variant="primary">Submit</b-button>
                        </div>
                    </b-form>
                </b-container>           
            </div>
        </div>
    </div>
</template>

<script>
    import ValidationErrors from './ValidationErrors';

    export default {
        data(){
            return {
                validationErrors: '',
                validationSuccess: '',
                publicPath: 'http://localhost:8000/',
                sessionUserId : this.$sessionUserId,
                sessionUser: '',
                form:{
                    profileInputs: {
                        name : {label:'Name', type:'text', vModelVal:''},
                        username : {label:'Username', type:'text', vModelVal:''},
                        email : {label:'Email', type:'email', vModelVal:''},
                        bio : {label:'Bio', type:'text', vModelVal:''},
                        birthdate : {label:'Birthdate', type:'date', vModelVal:''},
                        phone_number : {label:'Phone Number', type:'tel', vModelVal:''}
                    },
                    gender:null,
                    password : {label:'Password', type:'password', vModelVal:'', eyeIcon : 'fas fa-eye eye_icon'},
                    pfp : { publicId:'', url:''},
                    
                },
                genders: [
                    { text: 'Select One', value: null }, 
                    'Male', 
                    'Female'
                    ],
                
            }
        },

        // watch: {
        // '$route': {
        //         handler: 'reloadProfile',
        //         immediate: true
        //     }
        // },

                            
        created: function () {
        axios
        .get(this.publicPath+"users/"+this.sessionUserId)
        .then((data) => { 
            this.sessionUser = data.data;
            console.log(this.sessionUser.gender);
            if (this.sessionUser.gender == 'Male') {
                this.form.gender = 'Male'
            } else if(this.sessionUser.gender == 'Female') {
                this.form.gender = 'Female'
            }
        })
        .catch((err) => {});
        },
    
        methods:{
            backToProfile(){
                this.$router.push({path : '/'+this.sessionUserId+'/profile'})
            },
            
            reloadProfile() {
                axios
                .get(this.publicPath+"users/"+this.sessionUserId)
                .then((data) => { 
                    this.sessionUser = data.data;
                     console.log(this.sessionUser.gender);
                    if (this.sessionUser.gender == 'Male') {
                        this.form.gender = 'Male'
                    } else if(this.sessionUser.gender == 'Female') {
                        this.form.gender = 'Female'
                    }
                })
                .catch((err) => {});
            },
            updateProfile (event) {
                event.preventDefault();
                
                let formData = {
                    pfp: this.form.pfp.url,
                    name: this.form.profileInputs.name.vModelVal,
                    username: this.form.profileInputs.username.vModelVal,
                    email: this.form.profileInputs.email.vModelVal,
                    bio: this.form.profileInputs.bio.vModelVal,
                    birthdate: this.form.profileInputs.birthdate.vModelVal,
                    phone_number: this.form.profileInputs.phone_number.vModelVal,
                    password: this.form.password.vModelVal,
                    gender: this.form.gender,
                }
                
                for (var propName in formData) { 
                    if (formData[propName] == null || formData[propName] == undefined || formData[propName] == '') {
                    delete formData[propName];
                    }
                }

                axios({
                    method: 'patch',
                    url: this.publicPath+'users/'+this.sessionUserId,
                    data: formData,
                }).then((response) => {
                    console.log({response});
                    if (response.status == 200){                   
                    this.validationErrors = ''
                    this.validationSuccess = 'Profile updated successfully';
                    this.form.pfp.url = ''
                    this.form.profileInputs.name.vModelVal = ''
                    this.form.profileInputs.username.vModelVal = ''
                    this.form.profileInputs.email.vModelVal = ''
                    this.form.profileInputs.bio.vModelVal = ''
                    this.form.profileInputs.birthdate.vModelVal = ''
                    this.form.profileInputs.phone_number.vModelVal = ''
                    this.form.password.vModelVal = ''
                    this.form.gender = ''
                    this.reloadProfile()
                    }
                    if (this.form.pfp.url) {
                       this.sessionUser.pfp = this.form.pfp.url 
                    }
                    
                }).catch((error) => {
                    this.validationSuccess = '';
                    if (error.response.status == 422){
                        console.log(error.response.data.errors);
                    this.validationErrors = error.response.data.errors
                    }
                });
                $('html,body').animate({scrollTop: 0}, 100);
            },
        
            switchVisibility() {
                if (this.form.password.type === 'password') {
                    this.form.password.type = 'text' 
                    this.form.password.eyeIcon = 'fa fa-eye-slash text-secondary eye_icon'
                } else {
                    this.form.password.type = 'password'
                    this.form.password.eyeIcon = 'fa fa-eye eye_icon'
                }
                
            }
        },

        mounted() {
            
             var widget = cloudinary.createUploadWidget( 
                { cloudName: "****REMOVED****", uploadPreset: "****REMOVED****", cropping: true },
                (error, result) => { 
                    if (typeof result.info.files != 'undefined') {
                        this.form.pfp.url = result.info.files[0].uploadInfo.url
                        this.form.pfp.publidId = result.info.files[0].uploadInfo.public_id
                        $(this.$refs['submitForm']).click()
                    }
                    // this.postForm.postMedia = result
                    // if (typeof result.info.files != 'undefined') {
                    // this.postForm.publicId = result.info.files[0].uploadInfo.public_id
                    // this.postForm.resourceType = result.info.files[0].uploadInfo.resource_type
                    // this.postForm.fileName = result.info.files[0].name
                    // this.postForm.urlImg = result.info.files[0].uploadInfo.url
                    // this.$bvModal.show('modal_post_form')
                    // }
                    

                    // this.publicId = result.info.uploadInfo.publicId
                        //  console.log(result.info.files[0].uploadInfo.resource_type);

            });

             $('#openWidgetPfp').click(function() {
                widget.open();
            });
        },

  components: {
    ValidationErrors,
  },

        }
</script>
