<template>
    <select name="company_id" class="form-control" v-for="c in companies" :key="c.id">
    <option :value="c.id">{{ c.name }}</option>
</select>
</template>


<script>
export default {
    props: ['model'],

    mounted() {
        // this.model
        axios.get(`/${this.model}`).then(
            res => {
                this.companies = res.data
            }
        )
    },

    data() {
        return {
            companies: []
        }
    }
}
</script>
