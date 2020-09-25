<template>
<div id="app" class=" h-100">
    <h1>Hello App!</h1>
    <p>
        <!-- use router-link component for navigation. -->
        <!-- specify the link by passing the `to` prop. -->
        <!-- `<router-link>` will be rendered as an `<a>` tag by default -->
        <router-link to="/foo">Go to Foo</router-link>
        <router-link to="/bar">Go to Bar</router-link>
    </p>
    <!-- route outlet -->
    <!-- component matched by the route will render here -->
    <router-view></router-view>
    
    <!-- <example-component></example-component> -->
</div>
    
</template>
<script>
// import ExampleComponent from './components/ExampleComponent.vue'
export default {
    name : 'app',
    computed: {
    username() {
        // We will see what `params` is shortly
        return this.$route.params.username
        }
    },
    methods: {
        goBack() {
        window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
        }
    },
    components: {
        // ExampleComponent,
    }

    
}
</script>