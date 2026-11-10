<template>
    <form class="space-y-6 py-2" @submit.prevent="createContract">
        <div v-if="editMode && hasPendingAttachmentChanges" class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="font-semibold">你有尚未保存的附件变更</p>
                    <p class="mt-1 text-xs text-amber-700">已选择：{{ pendingAttachmentLabels.join('、') }}。请在对应附件区域点击“上传并保存”。</p>
                </div>
                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                    待保存
                </span>
            </div>
        </div>

        <div v-if="submitMessage" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            {{ submitMessage }}
        </div>

        <div v-if="submitError" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm">
            {{ submitError }}
        </div>

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
                        <p class="mt-1 text-sm text-slate-500">{{ editMode ? '支持补传或替换合同文件，历史数据将以最新上传为准。' : '合同文件可在创建后补充上传。' }}</p>
                    </div>

                    <AttachmentUploadSection
                        id="path"
                        title="合同文件"
                        :existing-path="formData.path"
                        :download-url="filename"
                        :selected-file="selectedFile"
                        :input-label="editMode ? '替换合同文件' : '上传合同文件'"
                        helper-text="合同文件可后续补充上传。"
                        pending-prefix="待替换文件"
                        :pending-helper-text="editMode ? '点击“上传并保存”后才会正式覆盖当前合同文件。' : '创建合同时会一并上传。'"
                        :required="false"
                        :show-current-card="editMode"
                        :highlight-pending="true"
                        :independent-upload="editMode"
                        :uploading="uploadingAttachment === 'path'"
                        :upload-message="attachmentMessages.path"
                        :upload-error="attachmentErrors.path"
                        @file-change="handleFileChange"
                        @upload="uploadAttachment('path')"
                    />
                </section>

                <section v-if="editMode" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-4 border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-semibold text-slate-900">附属材料</h2>
                        <p class="mt-1 text-sm text-slate-500">可继续补充询价纪要、验收文件与发票信息。</p>
                    </div>

                    <div class="space-y-4">
                        <AttachmentUploadSection
                            id="consult"
                            title="询价纪要"
                            :existing-path="formData.consult"
                            :download-url="fileconsult"
                            :selected-file="selectedConsult"
                            :independent-upload="true"
                            :uploading="uploadingAttachment === 'consult'"
                            :upload-message="attachmentMessages.consult"
                            :upload-error="attachmentErrors.consult"
                            @file-change="handleConsultChange"
                            @upload="uploadAttachment('consult')"
                        />

                        <AttachmentUploadSection
                            id="acceptance"
                            title="验收文件"
                            :existing-path="formData.acceptance"
                            :download-url="fileacceptance"
                            :selected-file="selectedAcceptance"
                            :independent-upload="true"
                            :uploading="uploadingAttachment === 'acceptance'"
                            :upload-message="attachmentMessages.acceptance"
                            :upload-error="attachmentErrors.acceptance"
                            @file-change="handleAcceptanceChange"
                            @upload="uploadAttachment('acceptance')"
                        />

                        <AttachmentUploadSection
                            id="invoice"
                            title="发票信息"
                            :existing-path="formData.invoice"
                            :download-url="fileinvoice"
                            :selected-file="selectedInvoice"
                            :independent-upload="true"
                            :uploading="uploadingAttachment === 'invoice'"
                            :upload-message="attachmentMessages.invoice"
                            :upload-error="attachmentErrors.invoice"
                            @file-change="handleInvoiceChange"
                            @upload="uploadAttachment('invoice')"
                        />
                    </div>
                </section>
            </div>
        </div>

        <div class="flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <p class="text-sm text-slate-500">{{ editMode ? '更新保存基础信息；附件请在对应区域单独上传。' : '保存成功后将返回项目总览页面。' }}</p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="/projects" class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">取消</a>
                <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-400" :disabled="isSubmitting">{{ buttonName }}</button>
            </div>
        </div>
    </form>
</template>

<script>
import AttachmentUploadSection from "./AttachmentUploadSection.vue";
import FormField from "./FormField.vue";

export default {
    components: { AttachmentUploadSection, FormField },
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
        },
        fileinvoice: function () {
            return this.fileUrl(this.formData.invoice);
        },
        hasPendingAttachmentChanges: function () {
            return !!(this.selectedFile || this.selectedConsult || this.selectedAcceptance || this.selectedInvoice);
        },
        pendingAttachmentLabels: function () {
            const labels = [];

            if (this.selectedFile) {
                labels.push('合同文件');
            }

            if (this.selectedConsult) {
                labels.push('询价纪要');
            }

            if (this.selectedAcceptance) {
                labels.push('验收文件');
            }

            if (this.selectedInvoice) {
                labels.push('发票信息');
            }

            return labels;
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
                acceptance: '',
                invoice: ''
            },
            selectedFile: null,
            selectedConsult: null,
            selectedAcceptance: null,
            selectedInvoice: null,
            uploadingAttachment: '',
            attachmentMessages: { path: '', consult: '', acceptance: '', invoice: '' },
            attachmentErrors: { path: '', consult: '', acceptance: '', invoice: '' },
            submitMessage: '',
            submitError: '',
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
        applyContractData(contract) {
            this.formData = {
                ...this.formData,
                ...this.normalizeContract(contract)
            };
        },

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
                acceptance: contract.acceptance ?? '',
                invoice: contract.invoice ?? ''
            };
        },

        resetSelectedFiles() {
            this.selectedFile = null;
            this.selectedConsult = null;
            this.selectedAcceptance = null;
            this.selectedInvoice = null;
        },

        clearMessages() {
            this.submitMessage = '';
            this.submitError = '';
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

            this.clearMessages();
            this.isSubmitting = true;

            this.formData.amount = this.normalizeNumericValue(this.formData.amount);
            this.formData.fulfillmentDeposit = this.normalizeNumericValue(this.formData.fulfillmentDeposit);

            const payload = new FormData();
            const excludedFields = ['path', 'consult', 'acceptance', 'invoice'];
            for (const key in this.formData) {
                if (excludedFields.includes(key)) {
                    continue;
                }
                if (this.formData[key] === null || this.formData[key] === undefined) {
                    continue;
                }
                payload.append(key, this.formData[key]);
            }

            if (!this.editMode && this.selectedFile) {
                payload.append('path', this.selectedFile);
            }

            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            };

            if (this.editMode) {
                axios.post('/contract/' + this.contract.id, payload, config)
                    .then((response) => {
                        if (response.data) {
                            this.applyContractData(response.data);
                        }

                        this.submitMessage = '合同基础信息更新成功。';
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .catch((error) => {
                        console.log(error);
                        const message = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : '更新失败，请检查表单后重试。';
                        this.submitError = message;
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
                        this.submitError = message;
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
            }
        },

        handleFileChange(event) {
            this.clearMessages();
            this.attachmentMessages.path = '';
            this.attachmentErrors.path = '';
            this.selectedFile = event.target.files[0];
        },

        handleConsultChange(event) {
            this.clearMessages();
            this.attachmentMessages.consult = '';
            this.attachmentErrors.consult = '';
            this.selectedConsult = event.target.files[0];
        },

        handleAcceptanceChange(event) {
            this.clearMessages();
            this.attachmentMessages.acceptance = '';
            this.attachmentErrors.acceptance = '';
            this.selectedAcceptance = event.target.files[0];
        },

        handleInvoiceChange(event) {
            this.clearMessages();
            this.attachmentMessages.invoice = '';
            this.attachmentErrors.invoice = '';
            this.selectedInvoice = event.target.files[0];
        },

        async uploadAttachment(type) {
            const selected = {
                path: this.selectedFile,
                consult: this.selectedConsult,
                acceptance: this.selectedAcceptance,
                invoice: this.selectedInvoice
            }[type];
            if (!this.editMode || !selected || this.uploadingAttachment) {
                return;
            }

            this.uploadingAttachment = type;
            this.attachmentMessages[type] = '';
            this.attachmentErrors[type] = '';
            const payload = new FormData();
            payload.append('file', selected);

            try {
                const response = await axios.post(`/contract/${this.contract.id}/attachments/${type}`, payload);
                this.formData[type] = response.data.path;
                const selectionKey = { path: 'selectedFile', consult: 'selectedConsult', acceptance: 'selectedAcceptance', invoice: 'selectedInvoice' }[type];
                if (this[selectionKey] === selected) {
                    this[selectionKey] = null;
                }
                this.attachmentMessages[type] = '上传成功';
            } catch (error) {
                const data = error.response?.data;
                this.attachmentErrors[type] = data?.errors?.file?.[0] || data?.message || '上传失败，请重试。';
            } finally {
                this.uploadingAttachment = '';
            }
        },

        fileUrl(path) {
            if (!path) {
                return '';
            }

            return `/storage/${path}`;
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
