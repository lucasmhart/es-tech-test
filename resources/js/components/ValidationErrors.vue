<template>
    <div v-if="validationErrors" v-show="validationErrors.length > 0">
        <ul class="alert alert-danger">
            <li v-for="(value, key, index) in validationErrors">{{ value }}</li>
        </ul>
    </div>
</template>
<script>
    export default {
        data(){
            return {}
        },
        props: ['errors'],
        computed: {
            validationErrors(){
                let errors = Object.values(this.errors)
                errors = errors.flat()
                return errors
            }
        }
    }
</script>
