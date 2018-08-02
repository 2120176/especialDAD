<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h3><strong>Delete Account?</strong></h3>
                <button type="button" class="btn btn-danger" @click="deleteAccount">Delete</button>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                logged_user : {},
                isUserLogged : false,
            }
        },
        methods: {

            deleteAccount: function() {
                if (window.confirm("Do you really want to delete your account?")) {
                    let head = {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        },
                    };
                    axios.post('/api/logout', null, head)
                        .then(response => {
                            console.log(response);
                            window.localStorage.clear();
                            //console.log("logout sucessfull");

                            axios.delete('/api/users/'+this.logged_user.id)
                                .then(response => {
                                    this.logged_user = null;
                                    console.log("user deleted");

                                });
                        }).then(response => {
                        this.$router.push('/login');
                    });
                }

            },
            getLoggedUser: function () {
                let token = localStorage.getItem('token');
                //console.log("get Logged User");
                axios.get('/api/user', {
                    headers: {'Content-Type' : 'application/json',
                        'Authorization' : 'Bearer ' + token }
                }).then(response => {
                    this.logged_user = response.data;
                    //console.log (this.logged_user.id);
                    this.isUserLogged = true;
                    this.email = this.logged_user.email;
                    this.name = this.logged_user.name;
                    this.nickname = this.logged_user.nickname;
                    //console.log(this.logged_user);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                    console.log(error);
                });
            }, // end function

        },
        mounted() {
            this.getLoggedUser();
        }

    }
</script>
<style>

</style>
