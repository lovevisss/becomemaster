<script>
export default {
    props: {
        modelValue: {
            type: [String, Number],
            default: ''
        },
        label: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        },
        type: {
            type: String,
            default: 'text'
        },
        placeholder: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        value: {
            get() {
                return this.modelValue;
            },
            set(newValue) {
                this.$emit('update:modelValue', newValue);
            }
        }
    }
};
</script>

<template>
    <div class="mb-3">
        <label :for="id" class="form-label">{{ label }}<span v-if="required" class="required">*</span></label>
        <input :type="type" class="form-control" :id="id" :placeholder="placeholder" :required="required" :name="id" v-model="value" />
    </div>
</template>

<style scoped>

</style>
