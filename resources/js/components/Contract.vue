<template>
    <form class="row g-4 py-2" @submit.prevent="createContract">
        <div class="col-md-12">
            <FormField id="name" label="名称" type="text" :required="true" v-model="formData.name"></FormField>
            <FormField id="year" label="合同年份" placeholder="签订年份(2024)" type="text" :required="true" v-model="formData.year"></FormField>
            <FormField id="code" label="合同编号" type="text" v-model="formData.code"></FormField>

            <div v-if="editMode">
                <hr>合同已签订：
                <a :href="filename" download>下载合同</a>
                <hr>
                <div v-if="formData.consult">
                    已询价：
                    <a :href="fileconsult" download>下载询价纪要</a>
                </div>
                <div v-else>
                    添加询价纪要
                    <input type="file" id="consult" ref="consultInput" @change="handleConsultChange" class="form-control">
                </div>
                <hr>
                <div v-if="formData.acceptance">
                    已验收：
                    <a :href="fileacceptance" download>下载验收文件</a>
                </div>
                <div v-else>
                    添加验收文件
                    <input type="file" id="acceptance" ref="acceptanceInput" @change="handleAcceptanceChange" class="form-control">
                </div>
            </div>

            <hr>
            <div class="mb-3">
                <label for="path" class="form-label">{{ labelname }}</label>
                <input type="file" id="path" ref="fileInput" @change="handleFileChange" class="form-control">
            </div>

            <FormField id="signing_date" label="签订日期" type="date" :required="true" v-model="formData.signing_date"></FormField>

            <div class="mb-3">
                <label for="company_id" class="form-label">公司</label>
                <selector model="company" v-model="formData.company_id" :searchable="true"></selector>
            </div>

            <div class="mb-3">
                <label for="user_id" class="form-label">签订人</label>
                <select id="user_id" class="form-control" v-model="formData.user_id" :disabled="!formData.company_id || usersLoading">
                    <option value="" disabled>{{ formData.company_id ? '请选择签订人' : '请先选择公司' }}</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>
                <small class="text-muted" v-if="formData.company_id && !usersLoading && users.length === 0">该公司暂无可选签订人</small>
            </div>

            <FormField id="amount" label="合同金额" type="text" format="number" inputmode="decimal" v-model="formData.amount" :required="true"></FormField>

            <FormField id="fulfillmentDeposit" label="质保金" type="text" format="number" inputmode="decimal" v-model="formData.fulfillmentDeposit"></FormField>

            <div class="mb-3">
                <label for="funding_id">经费来源</label>
                <selector model="funding" v-model="formData.funding_id"></selector>
            </div>

            <div class="mb-3">
                <label for="project_type_id">项目类型</label>
                <selector model="project_type" v-model="formData.project_type_id"></selector>
            </div>

            <div class="mb-3">
                <label for="contract_type_id">合同类型</label>
                <selector model="contract_type" v-model="formData.contract_type_id"></selector>
            </div>

            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">{{ buttonName }}</button>
        </div>
    </form>
</template>

<script>
import FormField from "./FormField.vue";

export default {
    components: { FormField },
    props: ['contract'],

    mounted() {
        if (this.contract != null) {
            this.formData = { ...this.contract };
        }

        this.initializeUsers();
    },

    computed: {
        buttonName: function () {
            if (this.isSubmitting) {
                return this.editMode ? '更新中...' : '创建中...';
            }
            return this.editMode ? '更新' : '创建';
        },
        editMode: function () {
            return this.contract;
        },
        filename: function () {
            return "http://project.zufedfc.edu.cn/storage/" + this.formData.path;
        },
        labelname: function () {
            return this.editMode ? '编辑合同' : '创建合同';
        },
        fileconsult: function () {
            return "http://project.zufedfc.edu.cn/storage/" + this.formData.consult;
        },
        fileacceptance: function () {
            return "http://project.zufedfc.edu.cn/storage/" + this.formData.acceptance;
        }
    },

    data() {
        return {
            formData: {
                name: '',
                year: '',
                code: '',
                signing_date: '',
                user_id: '',
                amount: '',
                company_id: '',
                fulfillmentDeposit: '',
                funding_id: '',
                project_type_id: '',
                contract_type_id: '',
                path: '',
                consult: '',
                acceptance: ''
            },
            selectedFile: null,
            selectedConsult: null,
            selectedAcceptance: null,
            isSubmitting: false,
            users: [],
            usersLoading: false,
            initializingUsers: true
        };
    },

    watch: {
        'formData.company_id': function (newCompanyId, oldCompanyId) {
            if (!this.initializingUsers && String(newCompanyId || '') !== String(oldCompanyId || '')) {
                this.formData.user_id = '';
            }

            this.fetchUsersByCompany(newCompanyId);
        }
    },

    methods: {
        initializeUsers() {
            this.fetchUsersByCompany(this.formData.company_id)
                .finally(() => {
                    this.initializingUsers = false;
                });
        },

        fetchUsersByCompany(companyId) {
            this.usersLoading = true;

            const params = {};
            if (companyId) {
                params.company_id = companyId;
            }

            return axios.get('/users', { params })
                .then((response) => {
                    const payload = Array.isArray(response.data)
                        ? response.data
                        : (response.data && Array.isArray(response.data.data) ? response.data.data : []);

                    this.users = payload;
                })
                .catch((error) => {
                    console.log(error);
                    this.users = [];
                })
                .finally(() => {
                    this.usersLoading = false;
                });
        },

        createContract() {
            if (this.isSubmitting) {
                return;
            }

            if (!this.editMode && !this.selectedFile) {
                alert('请先上传合同文件。');
                return;
            }

            this.isSubmitting = true;

            this.formData.amount = this.normalizeNumericValue(this.formData.amount);
            this.formData.fulfillmentDeposit = this.normalizeNumericValue(this.formData.fulfillmentDeposit);

            const payload = new FormData();
            const excludedFields = ['path', 'consult', 'acceptance'];
            for (const key in this.formData) {
                if (excludedFields.includes(key)) {
                    continue;
                }
                if (this.formData[key] === null || this.formData[key] === undefined) {
                    continue;
                }
                payload.append(key, this.formData[key]);
            }

            if (this.selectedFile) {
                payload.append('path', this.selectedFile);
            }
            if (this.selectedConsult) {
                payload.append('consult', this.selectedConsult);
            }
            if (this.selectedAcceptance) {
                payload.append('acceptance', this.selectedAcceptance);
            }

            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            };

            if (this.editMode) {
                axios.post('/contract/' + this.contract.id, payload, config)
                    .then(() => {
                        window.location.href = '/projects';
                    })
                    .catch((error) => {
                        console.log(error);
                        const message = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : '更新失败，请检查表单后重试。';
                        alert(message);
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
            } else {
                axios.post('/contract', payload)
                    .then(() => {
                        window.location.href = '/projects';
                    })
                    .catch((error) => {
                        console.log(error);
                        const message = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : '创建失败，请检查表单后重试。';
                        alert(message);
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
            }
        },

        handleFileChange(event) {
            this.selectedFile = event.target.files[0];
        },

        handleConsultChange(event) {
            this.selectedConsult = event.target.files[0];
        },

        handleAcceptanceChange(event) {
            this.selectedAcceptance = event.target.files[0];
        },

        normalizeNumericValue(value) {
            return String(value ?? '')
                .replace(/,/g, '')
                .replace(/[^\d.]/g, '')
                .replace(/\.(?=.*\.)/g, '');
        }
    }
}
</script>

<style scoped>
</style>
