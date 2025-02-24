<template>
    <div class="col-lg-12">
        <div class="card">
            <div class="row row-0">
                <div class="col-4">
                    <!-- Photo -->
                    <img :src="`/storage/${form.path}`" class="w-100 h-100 object-cover card-img-start" alt="Beautiful blonde woman relaxing with a can of coke on a tree stump by the beach">
                </div>
                <div class="col">
                    <div class="card-body">
                        <h3 class="card-title" v-show="!editable">{{form.reason}}</h3>
                        <textarea class="form-control" v-show="editable" v-model="form.reason"></textarea>
                        <button class="btn btn-danger" @click="editable = !editable" v-show="!editable">修改描述</button>
                        <button class="btn btn-success" @click="changeReason" v-show="editable">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



<script>
    import iziToast from "izitoast";

    export default {
        props:["form"],
        data(){
            return{
                editable:false
            }
        },
        methods:{
            changeReason(){
                axios.put(`/apply_forms/${this.form.id}`,{
                    reason:this.form.reason
                }).then(res=>{
                    iziToast.show({
                        title: '成功',
                        message: '申请成功',
                    })
                    this.editable = false
                })
            }

        }

    }

</script>
