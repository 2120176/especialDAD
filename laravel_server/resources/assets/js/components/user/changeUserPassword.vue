<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h3><strong>Change Account Password</strong></h3>
                <p>&nbsp;</p>
                <div  v-show="success"class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ success }}</strong>
                </div>
                <div  v-show="error"class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ error }}</strong>
                </div>

                <form v-on:submit.prevent="changeUserPassword()">
                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >Current password*</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" placeholder="Password" v-model="currentPassword" required>
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >New password*</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" placeholder="Password" v-model="newPassword" required>
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >New password confirmation*</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" placeholder="Password" v-model="newPasswordConfirmation" required>
                            </span>
                        </div>
                    </div>

                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-primary" v-on:click="cancel">Cancel</button>
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
                logged_user : {},
                isUserLogged : false,
                currentPassword:'',
                newPassword:'',
                newPasswordConfirmation:'',
                success: false,
                error: '',

            }
        },
        methods: {

            checkmatch()
            {
                if (this.newPassword != this.newPasswordConfirmation) {
                    this.error = 'Passwords doesn\'t match!'
                }
            },

            changeUserPassword()
            {
                const user = this.logged_user;
                axios.post('/api/changePassword', {
                    "currentPassword": this.currentPassword,
                    "newPassword": this.newPassword,
                    "user_id": user.id,
                })
                    .then((response) => {
                        this.success = response.data.msg;

                    }).catch((error) => {
                    console.log(error.response.data.msg);
                    this.error = error.response.data.msg;
                });

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
                    //console.log(this.logged_user);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                    console.log(error);
                });
            }, // end function

            cancel(){
                this.currentPassword = "";
                this.newPassword = "";
                this.newPasswordConfirmation = "";
                this.success = false;
            }
        },
        mounted() {
            this.getLoggedUser();
        }

    }
</script>
<style></style>