<template>
    <div class="form-group">


        <div v-if="!logged_user.admin">
            <strong>{{ message }} </strong>
        </div>

        <div v-else>
            <div class="jumbotron">
                <h1>Admin Dashboard </h1>
            </div>
            <div class="form-group">
                <a class="btn btn-default" @click.prevent="usersList">Users</a>
                <router-link class="btn btn-default" :to="{path: '/adminStatistics' }">
                    Statistics
                </router-link>

                <router-link class="btn btn-default" :to="{path: '/playerStatistics' }">
                    Players Statistics
                </router-link>

                <router-link class="btn btn-default" :to="{path: '/decksManagement' }">
                    Manage Decks
                </router-link>

                <router-link class="btn btn-default" :to="{path: '/adminSettings' }">
                    Settings
                </router-link>
                <button v-on:click="logout()" class="btn btn-primary">Logout</button>

            </div>

            <!--<div>
                <h4 v-if="isVisible"><a @click.prevent="getBlockedUsers">obter total de users bloqueados</a></h4>
                <h4 v-else> existem {{ defesa }} utilizadores bloqueados </h4>

            </div>-->

            <div v-if="userList">
                <users-list :users="users" @block-click="blockUser" @unlock-click="unlockUser" @delete-click="deleteUser">Users List</users-list>
                <div class="text-center">
                </div>
            </div>
        </div>
    </div>

</template>
<script type="text/javascript">
    import AdminUsersList from '../adminUsersList.vue';

    export default {
        data: function(){
            return {
                title: 'List Users',
                showSuccess: false,
                successMessage: '',
                currentUser: null,
                users: [],
                images: [],
                departments: [],
                userList: false,
                imagesList: false,
                logged_user: {},
                isVisible : true,
                defesa : 0,
            }
        },
        methods: {


            blockUser: function(user){
                this.currentUser = user;
                this.getUsers();
            },

            unlockUser: function(user){
                this.currentUser = user;
                this.getUsers();
            },
            deleteUser: function(user){
                axios.delete('api/users/'+user.id)
                    .then(response => {
                        this.showSuccess = true;
                        this.successMessage = 'User Deleted';
                    }).then(response => {
                        this.getUsers();

                    });
            },
            getUsers: function(){
                axios.get('api/users')
                    .then(response=>{
                        this.users = response.data.data;
                        //console.log(this.users);
                    });
            },
            usersList: function(){
                this.userList = true;
            },
            imageList: function(){
                this.imagesList = true;
            },
            childMessage: function(message){
                this.showSuccess = true;
                this.successMessage = message;
            },
            changePage: function (page) {
                this.pagination.current_page = page;
                this.getUsers(page);
            },

            getImages: function(){
                axios.get('api/images/active')
                    .then(response=>{
                        this.images = response.data.data;
                        console.log(this.images);
                        console.log(response.data.data.path);
                    });
            },
            resetPWAdmin: function () {
              this.$router.push('/adminPassword');
            },
            getBlockedUsers: function () {
                if (this.logged_user != null) {
                    axios.get('api/defesa')
                        .then(response => {
                            console.log('defesa', response.data.users);
                            this.defesa = response.data.users;
                            this.isVisible = false;
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
                        console.log('logged_user', this.logged_user);

                  }).catch(error => {
                    // não está autenticado
                    console.log(error);
                  });
            },
            logout: function() {
                this.$router.push({ path: 'logout' })
            },
        },
        components: {
            'users-list': AdminUsersList,
            //'images-list': ImagesList,

        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            message() {
                return "You are not alloed to navigate this page!";
            }

        },

        mounted() {
            this.getUsers();
            //this.getImages();
            this.getLoggedUser();
        }

    }
</script>

<style scoped>
    p {
        font-size: 2em;
        text-align: center;
    }
</style>
