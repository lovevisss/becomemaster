<template>

    <h2 :style="{ color: color }">申请阶段</h2>
    <div v-show="file_name" class="mb-3">
        <img :src="imageUrl" style="width: 300px">
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">负责人:</label>
        <div class="col-sm-10">
            <select class="form-control" v-model="user_id">
                <option v-for="user in userlist" :value="user.id" >{{user.name}}</option>
            </select>
        </div>

    </div>
    <div class="mb-3 row">
        <label class="col-sm-1 col-form-label">理由:</label>
        <div class="col-sm-11">
            <textarea type="text" v-model="reason"  class="form-control" placeholder="say the reason" >{{file.reason}}</textarea>
        </div>
    </div>

    <input type="file" @change="onFileChange" id="apply_form" class="form-control" v-show="!file_name">
    <hr>
</template>


<script>
    import iziToast from "izitoast";

    export default {
        name: 'ApplyFile',
        data() {
            return {
                reason: this.file_name.reason,
                userlist: null,
                user_id: this.file_name.user_id,
                file: this.file_name
            }
        },

        props: ['project_id', 'file_name'],

        mounted() {
            // this.project_id = this.$route.params.id\
            axios.get('/apply_userlist').then(({data}) => {
                    this.userlist = data
                }

            )
        },
        computed:{
            imageUrl(){
                return `/storage/${this.file_name.path}`
            },
            color(){
                if(this.file_name){
                    return 'green'
                }
                return 'red'
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
                data.append('reason', this.reason)
                axios.post(`/apply_forms/`, data).then(response => {
                    // alert('back'+response.data)
                    iziToast.show({
                        title: '成功',
                        message: '申请成功',
                    })
                }
                );
            }

        },


    }
</script>
