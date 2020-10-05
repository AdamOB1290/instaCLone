<template>
    <div>
        <div v-if="success">
            <div class="text-green-600 text-center mt-3 mb-2 ">{{ success }}</div>
        </div>
        <div class="flex justify-center" v-else-if="validationErrors">
            <ul class="text-red-700 text-center mt-3 mb-2 list-disc w-content">
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
            errorsTobeProccessed : this.errors,
            
        }
    },
    computed: {
        validationErrors(){
            console.log(this.errorsTobeProccessed);
            if (Object.keys(this.errorsTobeProccessed).length == 9) {
                this.errorsTobeProccessed = {
                    requiredError: 'Atleast one Field is required.'
                }
            }
            let errors = Object.values(this.errorsTobeProccessed);
            errors = errors.flat();
            return errors;
        }
    }

}
</script>