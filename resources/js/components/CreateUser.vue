

<template>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 mt-3">
                    <h1>
                        Create Users
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

<!--        @include('adminlte-templates::common.errors')-->

        <div class="card">


            <div class="card-body">

                <div class="row">
<!--                    @include('users.fields')-->
                    <div class="form-group col-sm-6">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" v-model="name" id="name" placeholder="Enter Name">
                    </div>
<!--                    company-->
                    <div class="form-group col-sm-6">
                        <label for="company_id">Company</label>
                        <selector :model="'company'" @selected_companyId="updateSelectedCompanyId" ></selector>

                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button class="btn btn-primary mr-1" @click="saveUser()">Save</button>
                <a href="/users" class="btn btn-default"> Cancel </a>
            </div>


        </div>
    </div>
</template>


<script>
import Selector from "./Selector.vue";
    export default {
        // name: "CreateUser",
        mounted() {
            console.log('create user')

        },
        components: {
            Selector
        },
        data() {
            return {
                name: '',
                selectCompany: {
                    name: '',
                    id: ''
                },
                companies: [],
                isSelectVisible: true
            }
        },
        methods:{


            saveUser(){
                console.log()
                axios.post('/api/users', {
                    name: this.name,
                    company_id: this.selectCompany.id
                })
                    .then(response => {
                        console.log(response.data)
                        window.location.href = 'http://project.zufedfc.edu.cn/users';
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            updateSelectedCompanyId(id){
                console.log('uodate id');
                this.selectCompany.id = id
            }

        }
    }


</script>
