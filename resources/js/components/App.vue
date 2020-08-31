<template>
<div>
    <navbar></navbar>
    <main class="h-100">
        
        @yield('content')
    </main>

    <vfooter></vfooter>
</div>
    
</template>
<script>
export default {
    
}
</script>