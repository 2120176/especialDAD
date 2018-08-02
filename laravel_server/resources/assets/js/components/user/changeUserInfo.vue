<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h3><strong>Change Account Info</strong></h3>
                <p>&nbsp;</p>
                <div  v-show="showSuccess"class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ success.name }}</strong>
                    <strong>{{ success.nickname }}</strong>
                    <strong>{{ success.email}}</strong>
                </div>

                <div  v-if="failed"class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ error.name }}</strong>
                    <strong>{{ error.nickname }}</strong>
                    <strong>{{ error.email }}</strong>
                </div>
                <form v-on:submit.prevent="changeInfo()">
                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="User name" v-model="name" required>
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >Nickname *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="User nickname" v-model="nickname" required>
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >Email *</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" placeholder="User email" v-model="email" required>
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
                logged_user : {},
                isUserLogged : false,
                email:'',
                showSuccess: false,
                success: {
                    name: '',
                    nickname: '',
                    email: '',
                },
                failed: false,
                error: {
                    name: '',
                    nickname: '',
                    email: '',
                },
                name: '',
                nickname: ''
            }
        },
        methods: {

            changeInfo()
            {
                const user = this.logged_user;
                axios.post('/api/changeUserName', {"name": this.name, "user_id": user.id})
                    .then((response) => {
                        this.showSuccess = true;
                        this.success.name = response.data.msg;
                    })
                    .catch((error) => {
                        this.failed = true;
                        this.error.name = error.response.data.msg;
                    });

                axios.post('/api/changeUserNickname', {"nickname": this.nickname, "user_id": user.id})
                    .then((response) => {
                        this.showSuccess = true;
                        this.success.nickname = response.data.msg;
                    })
                    .catch((error) => {
                        this.failed = true;
                        this.error.nickname = error.response.data.msg;
                    });

                axios.post('/api/changeEmail', {"email": this.email, "user_id": user.id})
                    .then((response) => {
                        this.showSuccess = true;
                        this.success.email = response.data.msg;
                    })
                    .catch((error) => {
                        this.error.email = error.response.data.msg;
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


            cancel(){
                this.getLoggedUser();
                this.showSuccess = false;
            }

        },

        mounted(){
            this.getLoggedUser();
        }
    }
</script>
<style></style>