<template>
    <div class="container" v-if="isUserLogged">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Logout</h1>
                <p><em>{{message}}</em></p>
                <div class="text-left">
                    <button class="btn btn-primary"  v-on:click="logout()">Logout</button>
                    <a class="btn btn-default" v-on:click.prevent="back()">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    data (){
        return {
        	isUserLogged : false,
        	logged_user: {},
        	token : null,
        };
			},
				computed: {
						message(){
								return "Sure you want logout?";
						}
    },
    methods: {
		logout: function() {
			// console.log('LOGOUT');
			if (this.isUserLogged) {
				 let head = {
						 headers: {
								 'Authorization': 'Bearer ' + this.token,
						 },
				};
				axios.post('/api/logout', null, head)
				.then(response => {
					window.localStorage.clear();
					console.log("logout sucessfull");					
				}).then(response => {
                    this.$router.push('/login');
				}).catch(error => {
					console.log(error);
				});


			}
        },

        // FB.logout(function(response) {
        //     // Person is now logged out
        // });
		
		getLoggedUser: function () {
            this.token = localStorage.getItem('token');
            //console.log("get Logged User");
            axios.get('/api/user', { 
                        headers: {'Content-Type' : 'application/json',
                        		  'Authorization' : 'Bearer ' + this.token }
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
        back: function () {
            this.$router.go(-1);
        }
	}, 
	mounted () {
		this.getLoggedUser();
	},
}
</script>

<style scoped>
</style>