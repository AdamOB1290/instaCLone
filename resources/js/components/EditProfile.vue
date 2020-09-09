<template>
    <div class="pt-3">
        <div class="d-flex justify-content-center align-items-center flex-column ">
            <img class="slider-image" :src="sessionUser.pfp" />
            <span>Change Profile Photo</span>
        </div>

        <b-container fluid>
            <b-form @submit="updateProfile">
                <b-row class="my-1" v-for="(input, key) in form.profileInputs" :key="key">                    
                    <b-col sm="3">
                        <label :for="`input-${input.label}`"><code>{{ input.label }}</code>:</label>
                    </b-col>
                    <b-col sm="9">
                        <b-form-input :id="`input-${input.label}`" v-model="input.vModelVal" :type="input.type" :placeholder="`${sessionUser[key] ? sessionUser[key] : 'Enter your '+input.label}`"></b-form-input>
                    </b-col>
                </b-row>
                <b-row class="my-1">                    
                    <b-col sm="3">
                        <label for="select-gender"><code>Gender</code>:</label>
                    </b-col>
                    <b-col sm="9">
                        <b-form-select id="select-gender" v-model="form.gender" :options="genders"></b-form-select>
                    </b-col>
                </b-row>

                <b-button type="submit" variant="primary">Submit</b-button>
            </b-form>
        </b-container>
        
        
        

    </div>
</template>

<script>
    export default {
        data(){
            return {
                publicPath: 'http://localhost:8000/',
                sessionUserId : this.$sessionUserId,
                sessionUser: '',
                form:{
                    profileInputs: {
                        name : {label:'Name', type:'text', vModelVal:'',},
                        username : {label:'Username', type:'text', vModelVal:'',},
                        email : {label:'Email', type:'email', vModelVal:'',},
                        bio : {label:'Bio', type:'text', vModelVal:'',},
                        birthdate : {label:'Birthdate', type:'date', vModelVal:'',},
                        phone_number : {label:'Phone Number', type:'tel', vModelVal:'',}
                    },
                    gender:null,

                },
                genders: [{ text: 'Select One', value: null }, 'Male', 'Female', 'Attack Helicopter'],

            }
        },
                            
        created: function () {
        axios
        .get(this.publicPath+"users/"+this.sessionUserId)
        .then((data) => { 
            this.sessionUser = data.data;
        })
        .catch((err) => {});
        },
    
        methods:{
            updateProfile (event) {
                event.preventDefault();
                let formData = {
                    name: this.form.profileInputs.name.vModelVal,
                    username: this.form.profileInputs.username.vModelVal,
                    email: this.form.profileInputs.email.vModelVal,
                    bio: this.form.profileInputs.bio.vModelVal,
                    phone_number: this.form.profileInputs.phone_number.vModelVal,
                }
                console.log(formData);
                for (var propName in formData) { 
                    if (formData[propName] == null || formData[propName] == undefined || formData[propName] == '') {
                        console.log(formData[propName]);
                    delete formData[propName];
                    }
                }
                console.log(formData);

                axios({
                    method: 'patch',
                    url: this.publicPath+'users/'+this.sessionUserId,
                    data: formData,
                }).then((response) => {
                   console.log(response);
                })

            },
        },

        mounted() {
            
        },

        components: {
           
        }

        }
</script>
