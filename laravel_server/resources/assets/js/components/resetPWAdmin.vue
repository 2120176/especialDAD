<template>
  <form>
    <div class="text-left">
        
        <div class="form-group">
            <label for="password">Old PW:</label>
            <input type="password" class="form-control" id="old_password" v-model="old_password" required>
        </div>

        <div class="form-group">
            <label for="password">New PW:</label>
            <input required type="password" class="form-control" id="password" v-model="password">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm PW:</label>
            <input required type="password" class="form-control" id="confirm_password" v-model="confirm_password">
        </div>

        <div class="text-center">
            <a type="submit" class="btn btn-primary" v-on:click.prevent="updatePW()">Mudar PW</a>
        </div>

    </div>
  </form>
</template>

<script type="text/javascript">

    export default {

        data: function() {
            return {
             
              old_password:null,
              password:null,
              confirm_password:null,
              updatePWError:null,
              isUserLogged : false,
        	  logged_user: {},
            }
        },
        methods: {

          updatePW: function () {
            if (this.old_password.trim() != "" && this.password === this.confirm_password) {
					axios.put('/api/admin/resetPassAdmin/' + this.logged_user.id, {
						old_password: this.old_password,
						password: this.password
					}).then(response => {
						alert(response.data.message);

					this.old_password= null,
					this.password= null,
					this.confirm_password= null;

					}).catch(error => {
						this.old_password= null,
						this.password= null,
						this.confirm_password= null;
						//error = response.data.message;
						alert(error.response.data.message);
						// alert(response.data.message);
					});
            	} else {
					this.old_password= null,
					this.password= null,
					this.confirm_password= null;
					alert('error: pass\'s diferentes');
					
				}
          },

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

        },
        components: {

        },

        mounted() {
            this.getLoggedUser();

        },
    }
</script>
