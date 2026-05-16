<template>
    <div>
        <input
            v-if="searchable"
            :id="inputId"
            type="text"
            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
            :list="datalistId"
            :placeholder="searchPlaceholderText"
            v-model="searchText"
            @input="handleSearchInput"
            @change="handleSearchInput"
            autocomplete="off"
        >
        <datalist v-if="searchable" :id="datalistId">
            <option v-for="c in filteredModels" :key="c.id" :value="c.name"></option>
        </datalist>

        <select v-else class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" v-model="value">
            <option :value="''" disabled>{{ placeholderText }}</option>
            <option :value="c.id" v-for="c in models" :key="c.id">{{ c.name }}</option>
        </select>
    </div>
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
        searchable: {
            type: Boolean,
            default: false
        },
    },

    computed:{
        datalistId() {
            return `${this.model}-selector-options`;
        },
        inputId() {
            return `${this.model}-selector-input`;
        },
        endpoint: function (){
            if (this.model === 'company') {
                return '/companies';
            }

            return `/${this.model}`;
        },
        placeholderText: function () {
            return `请选择${this.modelLabel}`;
        },
        searchPlaceholderText: function () {
            return `输入关键字查找${this.modelLabel}`;
        },
        modelLabel: function () {
            const labels = {
                user: '签订人',
                company: '公司',
                status: '合同状态',
                funding: '经费来源',
                project_type: '项目类型',
                contract_type: '合同类型'
            };

            return labels[this.model] || '选项';
        },
        selectedModel() {
            return this.models.find((model) => String(model.id) === String(this.modelValue)) || null;
        },
        filteredModels() {
            const keyword = String(this.searchText || '').trim().toLowerCase();

            if (!keyword) {
                return this.models;
            }

            return this.models.filter((model) => String(model.name || '').toLowerCase().includes(keyword));
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

    watch: {
        modelValue: {
            immediate: true,
            handler() {
                this.syncSearchTextFromValue();
            }
        },
        models() {
            this.syncSearchTextFromValue();
        }
    },

    mounted() {
        // this.model
        axios.get(this.endpoint).then(
            res => {
                this.models = Array.isArray(res.data)
                    ? res.data
                    : (res.data && Array.isArray(res.data.data) ? res.data.data : []);
            }
        )

    },

    data() {
        return {
            models: [],
            searchText: ''
        }
    },

    methods: {
        syncSearchTextFromValue() {
            if (!this.searchable) {
                return;
            }

            if (this.selectedModel) {
                this.searchText = this.selectedModel.name;
            }
        },
        handleSearchInput() {
            const normalizedInput = String(this.searchText || '').trim();
            const matchedModel = this.models.find((model) => String(model.name || '').trim().toLowerCase() === normalizedInput.toLowerCase());

            if (matchedModel) {
                this.value = matchedModel.id;
                return;
            }

            this.value = '';
        }
    },

}
</script>
