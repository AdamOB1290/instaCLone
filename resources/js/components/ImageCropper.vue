<template>
    <div>
        <vue-avatar
            :min-width=320
            :max-width=1080
            ref="vueavatar"
            @vue-avatar-editor:image-ready="onImageReady"
            :image="ImageSrc"
        >
        </vue-avatar>
        <br>
        <vue-avatar-scale
            ref="vueavatarscale"
            @vue-avatar-editor-scale:change-scale="onChangeScale"
            :width=250
            :min=1
            :max=3
            :step=0.02
        >
        </vue-avatar-scale>
        <br>
        <img src="" id="img-1">
        <button v-on:click="saveClicked">Click</button>

    </div>
</template>

<script>
import Vue from 'vue'
import VueAvatar from '../vue-avatar-editor/src/components/VueAvatar.vue'
import VueAvatarScale from '../vue-avatar-editor/src/components/VueAvatarScale.vue'

export default {
    props:['src'],
    data: function () {
        return {
          ImageSrc: this.src,
          croppedImage:null,
        }
        },
    
    created: function() {
        this.getCroppedImage = _.debounce(this.getCroppedImage, 300)
    },
     mounted: function () {
    console.log(this.$refs);
    },

  methods:{
    onChangeScale (scale) {
        this.$refs.vueavatar.changeScale(scale)
        this.getCroppedImage()
    },

    saveClicked(){
        // var img = this.$refs.vueavatar.getImageScaled()
    },

    getCroppedImage(){
        var img = this.$refs.vueavatar.getImageScaled()
        console.log(this.$refs.vueavatar);
        this.croppedImage = img.toDataURL()
        this.$store.commit("changeCroppedImage", this.croppedImage);
        // console.log(this.croppedImage);
    }, 

    onImageReady(scale){
      this.$refs.vueavatarscale.setScale(scale)
    }
  },

  components: {
    VueAvatar,
    VueAvatarScale
  },

    
}
</script>