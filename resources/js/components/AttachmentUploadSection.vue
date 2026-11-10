<template>
    <div class="rounded-2xl border border-slate-200 p-4">
        <div v-if="showCurrentCard && existingPath" class="mb-4 rounded-2xl border border-blue-100 bg-blue-50 p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-900">当前{{ title }}</p>
                    <p class="mt-1 break-all text-xs text-slate-500">{{ displayFileName(existingPath) }}</p>
                </div>
                <a
                    v-if="downloadUrl"
                    :href="downloadUrl"
                    download
                    class="inline-flex shrink-0 items-center rounded-xl bg-white px-3 py-2 text-sm font-medium text-blue-600 shadow-sm ring-1 ring-blue-200 hover:bg-blue-100"
                >
                    下载
                </a>
            </div>
        </div>

        <div v-else class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-slate-900">{{ title }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ existingPath ? displayFileName(existingPath) : emptyText }}</p>
            </div>
            <a
                v-if="existingPath && downloadUrl"
                :href="downloadUrl"
                download
                class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200"
            >
                下载
            </a>
        </div>

        <div>
            <label :for="id" class="mb-1 block text-sm font-medium text-slate-700">
                {{ inputLabel || title }}
                <span v-if="required" class="ml-0.5 text-red-500">*</span>
            </label>
            <input
                :id="id"
                :ref="id + 'Input'"
                type="file"
                @change="emitFileChange"
                class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
            >
            <p v-if="helperText" class="mt-2 text-xs text-slate-500">{{ helperText }}</p>
            <div v-if="selectedFile && highlightPending" class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-3 py-2">
                <p class="text-xs font-medium text-amber-800">{{ pendingPrefix }}：{{ selectedFile.name }}</p>
                <p v-if="pendingHelperText" class="mt-1 text-xs text-amber-700">{{ pendingHelperText }}</p>
            </div>
            <p v-else-if="selectedFile" class="mt-2 text-xs font-medium text-amber-700">{{ pendingPrefix }}：{{ selectedFile.name }}</p>
            <button v-if="independentUpload" type="button" :disabled="!selectedFile || uploading" @click="$emit('upload')" class="mt-3 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white disabled:cursor-not-allowed disabled:bg-slate-300">
                {{ uploading ? '上传中...' : '上传并保存' }}
            </button>
            <p v-if="uploadMessage" role="status" class="mt-2 text-xs font-medium text-emerald-700">{{ uploadMessage }}</p>
            <p v-if="uploadError" role="alert" class="mt-2 text-xs font-medium text-rose-700">{{ uploadError }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        existingPath: {
            type: String,
            default: '',
        },
        downloadUrl: {
            type: String,
            default: '',
        },
        selectedFile: {
            type: Object,
            default: null,
        },
        inputLabel: {
            type: String,
            default: '',
        },
        helperText: {
            type: String,
            default: '',
        },
        emptyText: {
            type: String,
            default: '暂未上传',
        },
        pendingPrefix: {
            type: String,
            default: '待保存',
        },
        pendingHelperText: {
            type: String,
            default: '',
        },
        required: {
            type: Boolean,
            default: false,
        },
        showCurrentCard: {
            type: Boolean,
            default: false,
        },
        highlightPending: {
            type: Boolean,
            default: false,
        },
        independentUpload: {
            type: Boolean,
            default: false,
        },
        uploading: {
            type: Boolean,
            default: false,
        },
        uploadMessage: {
            type: String,
            default: '',
        },
        uploadError: {
            type: String,
            default: '',
        },
    },
    watch: {
        selectedFile(file) {
            if (!file) {
                this.$refs[this.id + 'Input'].value = '';
            }
        },
    },
    methods: {
        emitFileChange(event) {
            this.$emit('file-change', event);
        },
        displayFileName(path) {
            if (!path) {
                return '';
            }

            return String(path).split('/').pop();
        },
    },
};
</script>
