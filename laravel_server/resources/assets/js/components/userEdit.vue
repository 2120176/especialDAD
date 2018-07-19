<template>
	<div class="jumbotron">
	    <h2>Edit User</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="user.name"
	            name="name" id="inputName"
	            placeholder="Fullname"/>
	    </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control" v-model="user.email"
	            name="email" id="inputEmail"
	            placeholder="Email address"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPassword">Password</label>
	        <input
	            type="password" class="form-control" v-model="user.password"
	            name="password" id="inputPassword"
	            placeholder="Password"/>
	    </div>
	    <div class="form-group">
	        <label for="inputNickname">Nickname</label>
	        <input
	            type="text" class="form-control" v-model="user.nickname"
	            name="nickname" id="inputNickname"
	            placeholder="Nickname"/>
	    </div>

		<input type="file" onchange="previewFile()"><br>

		<img src="" height="200" alt="Image preview...">
		


	    <div class="form-group">
	        <a class="btn btn-default" v-on:click.prevent="saveUser()">Save</a>
	        <a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		props: ['user'],
		data: function() {
			return {
				currentUser : {},
			};
		},
	    methods: {
	        saveUser: function(){
	            axios.put('api/users/'+this.user.id, this.user)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-saved', this.user)
	                });
	        },
	        cancelEdit: function(){
	        	axios.get('api/users/'+this.user.id)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-canceled', this.user);
	                });
	        },
	        getLoggedUser: function () {
                let token = JSON.parse(localStorage.getItem('token'));
                console.log("get Logged User");
                axios.get('/api/user', { 
                            headers: {'Content-Type' : 'application/json', 'Authorization' : 'Bearer ' + token }
                      }).then(response => {
                            this.logged_user = response.data;
                            //console.log (this.logged_user.id);
                            this.isUserLogged = true;

                      }).catch(error => {
                        // não está autenticado
                        this.isUserLogged = false;
                        console.log(error);
                      });
			}, // end function
			previewFile() {
				var preview = document.querySelector('img'); //selects the query named img
				var file    = document.querySelector('input[type=file]').files[0]; //sames as here
				var reader  = new FileReader();

				reader.onloadend = function () {
					preview.src = reader.result;
				}

				if (file) {
					reader.readAsDataURL(file); //reads the data as a URL
				} else {
					preview.src = "rage.png";
				}
			}, // end img function
		}
	}
</script>

<style scoped>

</style>
