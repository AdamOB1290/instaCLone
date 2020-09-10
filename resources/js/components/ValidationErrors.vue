<template>
    <div>
        <div v-if="success">
            <div class="alert alert-success text-center">{{ success }}</div>
        </div>
        <div v-else-if="validationErrors">
            <ul class="alert alert-danger">
                <li v-for="(value, key) in validationErrors" :key="key">{{ value }}</li>
            </ul>
        </div>
        
    </div>
</template>

<script>
export default {
    props: ['errors', 'success'],
    data(){
        return {
            
        }
    },
    computed: {
        validationErrors(){
            let errors = Object.values(this.errors);
            errors = errors.flat();
            return errors;
        }
    }

}
</script>