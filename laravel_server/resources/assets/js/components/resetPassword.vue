<template>
    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Forgot password</h1>
                <form v-on:submit.prevent="resetPasswordSubmit()">
                    <div class="form-group">
                        <label>Password</label>
                        <input
                                class="form-control"
                                placeholder="Enter your password"
                                type="password"
                                v-model="password"
                        >
                    </div>

                    <div class="form-group">
                        <label>Confirm password</label>
                        <input
                                class="form-control"
                                placeholder="Enter your password again"
                                type="password"
                                v-model="confirmPassword"
                        >
                    </div>

                    <button class="btn btn-primary pull-right">Reset password</button>
                </form>
            </div>
        </div>
    </div>

</template>

<script type="text/javascript">

    export default {
        props: ['token'],
        data () {
            return {
                password: '',
                confirmPassword: ''
            }
        },
        methods: {

            resetPasswordSubmit () {
                var postData = {
                    password: this.password,
                    confirm_password: this.confirmPassword,
                    token: this.token
                }

                axios.post('/api/reset-password', postData)
                    .then(response => {
                        console.log('response', response)
                        this.$router.push({path: '/login'})
                    }).catch(response => {
                    console.log('response', response)
                });

            }
        }
    }
</script>

<style >

</style>