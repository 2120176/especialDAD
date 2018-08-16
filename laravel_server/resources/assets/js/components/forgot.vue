<template v-if="localStorage.getItem('token') == null">
    <div>
        <h1>Forgot password</h1>
        <p></p>
        <div v-show="success" class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ success }}</strong>
        </div>
        <form>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" placeholder="Enter your email address" class="form-control" id="email"
                       v-model="email">
            </div>

            <div class="text-center form-group">
                <a class="btn btn-primary" v-on:click.prevent="sendEmail()">Send Email</a>
                <a class="btn btn-warning" v-on:click.prevent="back()">Back</a>

            </div>
        </form>


    </div>
</template>

<script type="text/javascript">
    export default {
        data: function() {
            return {
                email: '',
                success: false
            }
        },

        methods: {
            sendEmail () {

                axios.post('/api/forgot-password', {email: this.email})
                    .then(response => {
                    console.log('response', response)
                    this.success = response.data.msg;
                }).catch(response => {
                    console.log('response', response)
                });
            },

            back: function () {
                this.$router.go(-1);
            }
        }
    }
</script>


<scope>

</scope>