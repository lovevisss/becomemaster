<template>
    <form class="space-y-6 py-2" @submit.prevent="createContract">
        <div class="contract-main-grid">
            <div class="min-w-0 space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5 flex flex-col gap-3 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">{{ editMode ? '合同编辑' : '新建合同' }}</p>
                            <h2 class="mt-1 text-xl font-semibold text-slate-900">基础信息</h2>
                            <p class="mt-1 text-sm text-slate-500">先选择公司与签订人，再维护合同基础资料。</p>
                        </div>
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                            {{ editMode ? '支持调整状态与替换附件' : '创建后可继续补充资料' }}
                        </span>
                    </div>

                    <div class="contract-basic-grid">
                        <div class="contract-grid-wide">
                            <FormField id="name" label="名称" type="text" :required="true" v-model="formData.name"></FormField>
                        </div>
                        <FormField id="year" label="合同年份" placeholder="签订年份(2024)" type="text" :required="true" v-model="formData.year"></FormField>
                        <FormField id="code" label="合同编号" type="text" v-model="formData.code"></FormField>
                        <FormField id="signing_date" label="签订日期" type="date" :required="true" v-model="formData.signing_date"></FormField>

                        <div v-if="editMode" class="mb-3">
                            <label for="status_id" class="mb-1 block text-sm font-medium text-slate-700">合同状态</label>
                            <selector model="status" v-model="formData.status_id"></selector>
                            <p class="mt-2 text-xs text-slate-500">可直接在编辑页切换合同当前状态。</p>
                        </div>

                        <div class="mb-3">
                            <label for="company_id" class="mb-1 block text-sm font-medium text-slate-700">公司</label>
                            <selector model="company" v-model="formData.company_id" :searchable="true"></selector>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="mb-1 block text-sm font-medium text-slate-700">签订人</label>
                            <select id="user_id" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:bg-slate-100 disabled:text-slate-400" v-model="formData.user_id" :disabled="!formData.company_id || usersLoading">
                                <option value="" disabled>{{ formData.company_id ? (usersLoading ? '签订人加载中...' : '请选择签订人') : '请先选择公司' }}</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                            <p v-if="formData.company_id && !usersLoading && users.length === 0" class="mt-2 text-xs text-amber-600">该公司暂无可选签订人</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5 border-b border-slate-100 pb-4">
                        <h2 class="text-xl font-semibold text-slate-900">金额与分类</h2>
                        <p class="mt-1 text-sm text-slate-500">维护合同金额、质保金及归类信息，便于后续统计与筛选。</p>
                    </div>

                    <div class="contract-money-grid">
                        <FormField id="amount" label="合同金额" type="text" format="number" inputmode="decimal" v-model="formData.amount" :required="true"></FormField>
                        <FormField id="fulfillmentDeposit" label="质保金" type="text" format="number" inputmode="decimal" v-model="formData.fulfillmentDeposit"></FormField>

                        <div class="mb-3">
                            <label for="funding_id" class="mb-1 block text-sm font-medium text-slate-700">经费来源</label>
                            <selector model="funding" v-model="formData.funding_id"></selector>
                        </div>

                        <div class="mb-3">
                            <label for="project_type_id" class="mb-1 block text-sm font-medium text-slate-700">项目类型</label>
                            <selector model="project_type" v-model="formData.project_type_id"></selector>
                        </div>

                        <div class="mb-3">
                            <label for="contract_type_id" class="mb-1 block text-sm font-medium text-slate-700">合同类型</label>
                            <selector model="contract_type" v-model="formData.contract_type_id"></selector>
                        </div>
                    </div>
                </section>
            </div>

            <div class="min-w-0 space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-4 border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-semibold text-slate-900">合同文件</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ editMode ? '支持替换当前合同文件，历史数据将以最新上传为准。' : '请上传合同正本或扫描件。' }}</p>
                    </div>

                    <div v-if="editMode && formData.path" class="mb-4 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-900">当前合同文件</p>
                                <p class="mt-1 break-all text-xs text-slate-500">{{ displayFileName(formData.path) }}</p>
                            </div>
                            <a :href="filename" download class="inline-flex shrink-0 items-center rounded-xl bg-white px-3 py-2 text-sm font-medium text-blue-600 shadow-sm ring-1 ring-blue-200 hover:bg-blue-100">
                                下载
                            </a>
                        </div>
                    </div>

                    <div>
                        <label for="path" class="mb-1 block text-sm font-medium text-slate-700">
                            {{ editMode ? '替换合同文件' : '上传合同文件' }}
                            <span v-if="!editMode" class="ml-0.5 text-red-500">*</span>
                        </label>
                        <input type="file" id="path" ref="fileInput" @change="handleFileChange" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <p class="mt-2 text-xs text-slate-500">未上传文件时，创建合同不会提交成功。</p>
                        <p v-if="selectedFile" class="mt-2 text-xs font-medium text-emerald-600">已选择：{{ selectedFile.name }}</p>
                    </div>
                </section>

                <section v-if="editMode" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-4 border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-semibold text-slate-900">附属材料</h2>
                        <p class="mt-1 text-sm text-slate-500">可继续补充询价纪要与验收文件。</p>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-900">询价纪要</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ formData.consult ? displayFileName(formData.consult) : '暂未上传' }}</p>
                                </div>
                                <a v-if="formData.consult" :href="fileconsult" download class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">下载</a>
                            </div>
                            <input type="file" id="consult" ref="consultInput" @change="handleConsultChange" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <p v-if="selectedConsult" class="mt-2 text-xs font-medium text-emerald-600">已选择：{{ selectedConsult.name }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-900">验收文件</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ formData.acceptance ? displayFileName(formData.acceptance) : '暂未上传' }}</p>
                                </div>
                                <a v-if="formData.acceptance" :href="fileacceptance" download class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">下载</a>
                            </div>
                            <input type="file" id="acceptance" ref="acceptanceInput" @change="handleAcceptanceChange" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <p v-if="selectedAcceptance" class="mt-2 text-xs font-medium text-emerald-600">已选择：{{ selectedAcceptance.name }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-900">发票信息</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ formData.invoice ? displayFileName(formData.invoice) : '暂未上传' }}</p>
                                </div>
                                <a v-if="formData.invoice" :href="fileinvoice" download class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">下载</a>
                            </div>
                            <input type="file" id="invoice" ref="invoiceInput" @change="handleInvoiceChange" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <p v-if="selectedInvoice" class="mt-2 text-xs font-medium text-emerald-600">已选择：{{ selectedInvoice.name }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <p class="text-sm text-slate-500">保存成功后将返回项目总览页面。</p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="/projects" class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">取消</a>
                <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-400" :disabled="isSubmitting">{{ buttonName }}</button>
            </div>
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
            this.formData = {
                ...this.formData,
                ...this.normalizeContract(this.contract)
            };
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
            return !!this.contract;
        },
        filename: function () {
            return this.fileUrl(this.formData.path);
        },
        fileconsult: function () {
            return this.fileUrl(this.formData.consult);
        },
        fileacceptance: function () {
            return this.fileUrl(this.formData.acceptance);
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
                status_id: '',
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
        normalizeContract(contract) {
            return {
                name: contract.name ?? '',
                year: contract.year ?? '',
                code: contract.code ?? '',
                signing_date: contract.signing_date ?? '',
                user_id: contract.user_id ?? '',
                amount: contract.amount ?? '',
                company_id: contract.company_id ?? '',
                status_id: contract.status_id ?? '',
                fulfillmentDeposit: contract.fulfillmentDeposit ?? '',
                funding_id: contract.funding_id ?? '',
                project_type_id: contract.project_type_id ?? '',
                contract_type_id: contract.contract_type_id ?? '',
                path: contract.path ?? '',
                consult: contract.consult ?? '',
                acceptance: contract.acceptance ?? ''
            };
        },

        initializeUsers() {
            this.fetchUsersByCompany(this.formData.company_id)
                .finally(() => {
                    this.initializingUsers = false;
                });
        },

        fetchUsersByCompany(companyId) {
            if (!companyId) {
                this.users = [];
                this.usersLoading = false;
                return Promise.resolve();
            }

            this.usersLoading = true;

            const params = {};
            params.company_id = companyId;

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

        fileUrl(path) {
            if (!path) {
                return '';
            }

            return `/storage/${path}`;
        },

        displayFileName(path) {
            if (!path) {
                return '';
            }

            return String(path).split('/').pop();
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
.contract-main-grid,
.contract-basic-grid,
.contract-money-grid {
    display: grid;
    gap: 1.5rem;
    align-items: start;
}

.contract-basic-grid,
.contract-money-grid {
    gap: 1rem;
}

@media (min-width: 1024px) {
    .contract-basic-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .contract-money-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .contract-grid-wide {
        grid-column: span 2 / span 2;
    }
}

@media (min-width: 1536px) {
    .contract-main-grid {
        grid-template-columns: minmax(0, 1.5fr) minmax(340px, 420px);
    }

    .contract-money-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}
</style>
