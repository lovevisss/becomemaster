

<template>

    <div class="row g-4 py-5">

        <div class="col-md-12 ">
            <FormField id="name" label="名称" type="text" :required="true" v-model="formData.name"></FormField>
            <FormField id="year" label="合同年份" placeholder="签订年份(2024)" type="text" :required="true" v-model="formData.year"></FormField>
            <FormField id="code" label="合同编号" type="text"  v-model="formData.code"></FormField>
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
                <label for="user_id" class="form-label">签订人</label>
                <selector model="user" v-model="formData.user_id"></selector>
            </div>
            <FormField id="amount" label="合同金额" type="text" v-model="formData.amount" :required="true" ></FormField>


                <div class="mb-3">
                    <label for="company_id" class="form-label">公司</label>
                    <selector model="company" v-model="formData.company_id"></selector>
                </div>
            <FormField id="fulfillmentDeposit" label="质保金" type="text" v-model="formData.fulfillmentDeposit"></FormField>

                <div class="mb-3">
                    <label for="funding_id">经费来源</label>
                    <selector model="funding" v-model="formData.funding_id" ></selector>

                </div>

                <div class="mb-3">
                    <label for="project_type_id">项目类型</label>
                    <selector model="project_type" v-model="formData.project_type_id"></selector>
                </div>
                <div class="mb-3">
                    <label for="project_type_id">合同类型</label>
                    <selector model="contract_type" v-model="formData.contract_type_id"></selector>
                </div>


                <button type="submit" class="btn btn-primary"  @click="createContract">{{ buttonName }}</button>
        </div>




    </div>

</template>

<script>
    import FormField from "./FormField.vue";

    export default {
        components: {FormField},
        props: ['contract'],
        mounted() {
            console.log('contract start load')
            if(this.contract != null){
                this.formData = { ...this.contract };
            }
        },



        computed:{
          buttonName: function (){
              return this.editMode?'更新':'创建';
          },
          editMode: function (){
              return this.contract
          },
            filename: function (){
              return "http://project.zufedfc.edu.cn/storage/" + this.formData.path;
            },
            labelname: function (){
              return this.editMode?'编辑合同':'创建合同';
            },
            fileconsult: function (){
              return "http://project.zufedfc.edu.cn/storage/" + this.formData.consult;
            },
            fileacceptance: function (){
                return "http://project.zufedfc.edu.cn/storage/" + this.formData.acceptance;
            }

        },
        data() {
            return {

                formData:{
                    name: '',
                    year:'',
                    code:'',
                    signing_date: '',
                    user_id: '',
                    amount: '',
                    company_id: '',
                    fulfillmentDeposit: '',
                    funding_id:'',
                    project_type_id:'',
                    contract_type_id: '',
                    path: '',      // 合同文件路径
                    consult: '',    // 询价纪要文件路径
                    acceptance: ''
                }
            }
        },

        methods:{
            createContract(){
                const formData = new FormData();
                for (const key in this.formData){
                    if(this.formData[key] === null || this.formData[key] === undefined){
                        continue;
                    }
                    formData.append(key, this.formData[key]);
                }
                if(this.selectedFile){
                    formData.append('path', this.selectedFile);
                }
                if(this.selectedConsult){
                    console.log('add consult')
                    formData.append('consult', this.selectedConsult);
                }
                if(this.selectedAcceptance){
                    console.log('add acceptance')
                    formData.append('acceptance', this.selectedAcceptance);
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

                    axios.post('/contract/' +  this.contract.id, formData, config)
                        .then(response => {
                            console.log(response.data)
                        //     重定向到contract
                            window.location.href = '/projects'; // 不推荐，仅作为临时解决方案
                        })
                        .catch(error => {
                            console.log(error)
                        })
                }else{
                    axios.post('/contract', formData)
                        .then(response => {
                            console.log(response.data)
                            //     重定向到contract
                            window.location.href = '/projects'; // 不推荐，仅作为临时解决方案
                        })
                        .catch(error => {
                            console.log(error)
                        })
                }



            },



            handleFileChange(event){
                this.selectedFile = event.target.files[0];
            },

            handleConsultChange(event){
                console.log('handle consult' + event.target.files[0]);
                this.selectedConsult = event.target.files[0];
            },
            handleAcceptanceChange(event){
                console.log('handle acceptance' + event.target.files[0]);
                this.selectedAcceptance = event.target.files[0];
            }

        }

    }
</script>

<style scoped>

</style>
