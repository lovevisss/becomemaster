<template>
    <select  class="form-control" v-model="value"  >
        <option :value="c.id" v-for="c in models" :key="c.id">{{ c.name }}</option>
    </select>
</template>


<script>
export default {
    props: {
        model: {
            type: String,
            required: true
        },
        modelValue:{
            type: [String,Number],
            default: 0
        },

    },

    computed:{
        model_id: function(){
        //   model是companies 如何转换成company

        return this.model + 'Id'
        },
        selectedModelId: function(){
            return 'selected_' + this.model_id
        },
        value: {
            get(){
                return this.modelValue;
            },
            set(newValue){
                // console.log('value change')
                this.$emit('update:modelValue', newValue);
                this.$emit('selected_companyId', newValue);
            }
        }
    },

    mounted() {
        // this.model
        axios.get(`/${this.model}`).then(
            res => {
                this.models = res.data
            }
        )

    },

    data() {
        return {
            models: []
        }
    },

}
</script>
