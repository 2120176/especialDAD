<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h3><strong>Avatar</strong></h3>
                <p>&nbsp;</p>
                <div  v-show="success"class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ success }}</strong>
                </div>
                <div class="col-md-2">
                    <input type="file" v-on:change="onFileChange" class="form-control">
                </div>
                <div class="col-md-2">
                    <img :src="avatar" class="img-responsive">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success btn-block" v-model="avatar" v-on:click.prevent="uploadAvatar(avatar)">
                        Save
                    </button>
                </div>
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
                avatar: '',
                success: '',
            }
        },
        methods: {

            onFileChange: function(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createAvatar(files[0]);
            },
            createAvatar: function (file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.avatar = e.target.result;
                    //console.log("O PATH NAO ESTA VAZIO ", vm.avatar);
                };
                reader.readAsDataURL(file);
            },
            uploadAvatar: function(avatar){
                const user = this.logged_user;
                axios.post('/api/updateAvatar', {avatar: this.avatar, user_id: user.id})
                    .then(response => {
                        this.success = response.data.msg;
                        this.getCurrentAvatar();
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
                    console.log(this.logged_user);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                   // console.log(error);
                });
            } // end function

        },

        mounted(){
            this.getLoggedUser();
        }
    }
</script>
<style></style>