<template>
    <h2 :style="{ color: color }">合同签订阶段</h2>
    <div v-show="contracts">
        <a :href="file_name" download>下载合同</a>
    </div>
    <label class="btn btn-primary" v-show="!contracts">
        上传合同
        <input type="file" @change="onFileChange" id="contract" class="form-control"  placeholder="上传合同">
        <input type="text" id="year" placeholder="合同年份" class="form-control" v-model="year">
    </label>
    <hr>

</template>


<script>
import iziToast from "izitoast";

export default {
    name: 'SignContract',

    props: ['project_id', 'contracts'],

    computed: {
        color() {
            if(this.file_name){
                return 'green'
            }
            return 'red'
        },

    },

    data(){
        return {
            year: null,
        }
    },

    methods: {
        onFileChange(e) {
            // alert(this.project_id)
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            let data = new FormData();
            data.append('file', files[0])
            data.append('project_id', this.project_id)
            data.append('user_id', this.user_id)
            data.append('year', this.year)
            // data.append('reason', this.reason)
            axios.post(`/contracts`, data).then(response => {
                    iziToast.show({
                        title: '成功',
                        message: '合同已经上传',
                    })
                }
            );
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.file_name = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    },
}
</script>
