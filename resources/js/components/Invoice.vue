<template>
    <div >
        支付依据：{{payment.reason}}
        <p>
        金额： {{payment.amount}}
        </p>
        开票日期：{{formattedDate}}
        <div v-if="payment.invoice">
<!--            {{payment.invoice}}-->
           <a :href="invoice_path">已有发票</a>
        </div>
        <div v-else>
            上传发票
            <input type="file" @change="onFileChange" id="invoice" class="form-control">
        </div>
    </div>
    <hr>
</template>
<script>
export default {
    name: 'ApplyFile',
    data() {
        return {
            // reason: this.file_name.reason,
            // userlist: null,
            // user_id: this.file_name.user_id,
            // file: this.file_name
        }
    },

    props: ['project_id', 'payment'],

    mounted() {

    },
    computed:{
        formattedDate() {
            return new Date(this.payment.created_at).toLocaleDateString();
        },
        invoice_path(){
            return `/storage/${this.payment.invoice}`
        }

    },
    methods: {
        onFileChange(e) {
            alert(this.payment.id)
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            let data = new FormData();
            data.append('file', files[0])
            data.append('payment_id', this.payment.id)
            // data.append('user_id', this.user_id)
            // data.append('reason', this.reason)
            axios.post(`/payment_invoice`, data).then(response => {
                    alert('back'+response.data)
                }
            );
        }

    },


}
</script>
