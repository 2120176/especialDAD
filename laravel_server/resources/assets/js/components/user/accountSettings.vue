<template>
    <div class="jumbotron" v-if="isUserLogged">
        <h2>My Account</h2>

        <div><img v-bind:src="pieceImageURL(logged_user.avatar)" height="100" width="80"> </div>
        <h5> (If your image is broken, report to admin) </h5>


        <div class="form-group">
            <label>Name: </label> {{ logged_user.name }}
        </div>
        <div class="form-group">
            <label>Email: </label> {{ logged_user.email }}
        </div>
        <div class="form-group">
            <label>Nickname: </label> {{ logged_user.nickname }}
        </div>

        <div class="form-group">
            <a class="btn btn-info" v-on:click.prevent="editUser()">Edit my profile</a>
            <a class="btn btn-warning" v-on:click.prevent="back()">Back</a>

        </div>

        <div v-show="editingUser">

            <changeUserInfo :name="name" :nickname="nickname" :email="email"></changeUserInfo>
            <changeUserPassword></changeUserPassword>
            <changeAvatar></changeAvatar>
            <deleteUserAccount></deleteUserAccount>
        </div>


    </div>
</template>
<script type="text/javascript">
    export default {

        data: function() {
            return {
                logged_user : {},
                isUserLogged : false,
                editingUser : false
            };
        },
        methods: {
            editUser: function(){
                this.editingUser = true;
            },

            pieceImageURL (path) {
                var imgSrc = String(path);
                return 'img/avatar/' + imgSrc;
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
                   // console.log(this.logged_user);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                    console.log(error);
                });
            }, // end function

            back: function () {
                this.$router.go(-1);
            }
        },
        mounted() {
            this.getLoggedUser();
        }
    }
</script>
<style>

</style>


