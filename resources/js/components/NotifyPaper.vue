

<template>

    <div class="row g-4 py-5">

        <div class="col-md-12 ">
            <FormField id="name" label="名称" type="text" :required="true" v-model="formData.name"></FormField>
            <div v-if="editMode">
                <hr>抄告单已上传：
                <a :href="filename" download>下载抄告单</a>
            </div>
            <hr>
            <div class="mb-3">
                <label for="path" class="form-label">{{ labelname }}</label>
                <input type="file" id="path" ref="fileInput" @change="handleFileChange" class="form-control">
            </div>
            <FormField id="issue_date" label="发送日期" type="date" :required="true" v-model="formData.issue_date"></FormField>
            <button type="submit" class="btn btn-primary"  @click="createContract">{{ buttonName }}</button>
        </div>




    </div>

</template>

<script>
import FormField from "./FormField.vue";

export default {
    components: {FormField},
    props: ['notify_paper'],
    mounted() {
        console.log('notify_papers start load')
        if(this.notify_paper != null){
            this.formData = { ...this.notify_paper };
        }
    },



    computed:{
        buttonName: function (){
            return this.editMode?'更新':'创建';
        },
        editMode: function (){
            return this.notify_paper
        },
        filename: function (){
            return "http://project.zufedfc.edu.cn/storage/" + this.formData.path;
        },
        labelname: function (){
            return this.editMode?'编辑':'创建';
        },


    },
    data() {
        return {

            formData:{
                name: '',
                issue_date: '',
                path: '',      // 文件路径
            }
        }
    },

    methods:{
        createContract(){
            const formData = new FormData();
            for (const key in this.formData){
                if(this.formData[key] === null || this.formData[key] === undefined || this.formData[key] === ''){
                    continue;
                }
                formData.append(key, this.formData[key]);
            }
            if(this.selectedFile){
                formData.append('path', this.selectedFile);
            }


            const config = {
                headers: {
                    // 'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT' // 如果需要覆盖HTTP方法
                }
            };

            if(this.editMode){
                //     更新

                axios.post('/notify_papers/' +  this.notify_paper.id, formData, config)
                    .then(response => {
                        console.log(response.data)
                        //     重定向到contract
                        window.location.href = '/projects'; // 不推荐，仅作为临时解决方案
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }else{
                axios.post('/notify_papers', formData)
                    .then(response => {
                        console.log(response.data)
                        //     重定向到contract
                        // window.location.href = '/projects'; // 不推荐，仅作为临时解决方案
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }



        },



        handleFileChange(event){
            this.selectedFile = event.target.files[0];
        },

    }

}
</script>

<style scoped>

</style>
