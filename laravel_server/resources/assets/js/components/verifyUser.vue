<template>
    <div>
        <div class="container">
            <div class="jumbotron vertical-center">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1>User activation</h1>

                        <div class="alert alert-success"  v-cloak v-show="success">
                            <p>Registo activado com sucesso.</p>
                        </div>
                    </div>
                </div>
                <router-link class="btn btn-default center-block" :to="{ path: '/login' }">
                    Login
                </router-link>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['token'],
        data: function(){
            return {
                success: false
            }
        },
        mounted: function () {
            const data = {
                token: this.token
            };
            axios.post('/api/verify', data)
                .then((response) => {
                    this.success = true;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
</script>