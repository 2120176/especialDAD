<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h3><strong>Change Administrator Email</strong></h3>
                <p>&nbsp;</p>
                <div  v-show="success"class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ success }}</strong>
                </div>

                <div  v-if="error"class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ error }}</strong>
                </div>

                <form v-on:submit.prevent="changeEmail()">
                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >Email*</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" placeholder="Admin email" v-model="email" required>
                        </div>
                    </div>

                    <div class="btn-group pull-right">
                        <button type="button"class="btn btn-primary" v-on:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-success pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                email:'',
                success: false,
                error: '',
                logged_user : {},
                isUserLogged : false,
            }
        },
        methods: {

            changeEmail()
            {
                const user_id = this.logged_user.id;
                axios.post('/api/changeEmail', {"email": this.email, "user_id": user_id})
                    .then((response) => {
                        this.success = response.data.msg;
                    })
                    .catch((error) => {
                        console.log(error.response.data.msg);
                        this.error = error.response.data.msg;
                    });
            },

            getCurrentEmail(){
                this.email = this.logged_user.email;
                //return email
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
                    console.log(this.logged_user);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                    console.log(error);
                });
            }, // end function

            cancel(){
                this.getCurrentEmail();
                this.success = false;
            }

        },

        mounted(){
            this.getLoggedUser();
            this.getCurrentEmail();
        }
    }
</script>
<style></style>
