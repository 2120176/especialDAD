<template>
	<div class="jumbotron" v-if="isUserLogged">
	    <h2>My Account</h2>
		<a class="btn btn-danger" v-on:click.prevent="deleteUser()">Delete Account</a>


		<div><img v-bind:src="pieceImageURL(logged_user.avatar)" height="100" width="80"> </div>
	        <h5> (If your image is broken, report to admin) </h5>
		

	    <div class="form-group">
	        <label for="inputName">Name: </label> {{logged_user.name}}
	    </div>
	    <div class="form-group">
	        <label for="inputEmail">Email: </label> {{logged_user.email}}
	    </div>
	    <div class="form-group">
	        <label for="inputNickname">Nickname: </label> {{logged_user.nickname}}
	    </div>


	    <div class="form-group">
	    <!--
	        <a class="btn btn-default" v-on:click.prevent="saveUser()">Save</a>
	        <a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
	        -->
	        <a class="btn btn-info" v-on:click.prevent="editUser()">Edit my profile</a>
			<a class="btn btn-warning" v-on:click.prevent="back()">Back</a>

	    </div>

		<div class="jumbotron" v-show="editingUser">
			<div>
				<form>
					<h2>Edit User info</h2>
					<div class="form-group">
						<label for="inputName">Name</label>
						<input
								type="text" class="form-control" v-model="logged_user.name"
								name="name" id="inputName"
								placeholder="Fullname"/>
					</div>
					<div class="form-group">
						<label for="inputEmail">Email</label>
						<input
								type="email" class="form-control" v-model="logged_user.email"
								name="email" id="inputEmail"
								placeholder="Email address"/>
					</div>

					<div class="form-group">
						<label for="inputNickname">Nickname</label>
						<input
								type="text" class="form-control" v-model="logged_user.nickname"
								name="nickname" id="inputNickname"
								placeholder="Nickname"/>
					</div>

					<div class="form-group">
						<a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
						<a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
					</div>

				</form>
			</div>
			<hr>
			<label>Avatar</label>
			<div>
				<form>

					<div class="col-md-4">
						<input type="file" v-on:change="onFileChange" class="form-control">
					</div>
					<div class="col-md-4">
						<img :src="avatar" class="img-responsive">
					</div>
					<div class="col-md-4">
						<button class="btn btn-success btn-block" v-model="avatar" v-on:click.prevent="uploadAvatar(avatar)">
							Save Image
						</button>
					</div>
				</form>
			</div>

			<hr>

			<div>
				<form>
					<div class="form-group">
						<label for="inputPassword">Old Password</label>
						<input
								type="password" class="form-control" v-model="old_password"
								name="password" id="inputOldPassword"
								placeholder="Password"/>
					</div>


					<div class="form-group">
						<label for="inputPassword">New Password</label>
						<input
								type="password" class="form-control" v-model="password"
								name="password" id="inputNewPassword"
								placeholder="Password"/>
					</div>


					<div class="form-group">
						<label for="inputPassword">Confirm Password</label>
						<input
								type="password" class="form-control" v-model="confirm_password"
								name="password" id="inputConfirmPassword"
								placeholder="Password"/>
					</div>


					<div class="form-group">
						<a class="btn btn-primary" v-on:click.prevent="saveUserPW()">Save</a>
						<a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
					</div>
				</form>
			</div>
		</div>


	</div>
</template>

<script type="text/javascript">
	module.exports={
		data: function() {
			return {
				file: null,
				logged_user : {},
				isUserLogged : false,
				editingUser : false,
				old_password : null,
				password: null,
				confirm_password : null,
				avatar: null,
				success: null,
			};
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
                    console.log("O PATH NAO ESTA VAZIO ", vm.avatar);
                };
                reader.readAsDataURL(file);
            },
            uploadAvatar: function(avatar){
                
                axios.post('/api/updateAvatar', {avatar: this.avatar, user_id: this.logged_user.id})
                    .then(response => {
						this.success = response.data.msg;
						if(window.confirm("Imagem alterada com sucesso!"))
							location.reload();
                    }).then(response => {
						this.pieceImageURL(this.logged_user.id);
					});
            },

			pieceImageURL (path) {
                var imgSrc = String(path);
                return 'img/avatar/' + imgSrc;
			},
			saveUserPW: function() {
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
	        saveUser: function(){
	            axios.put('api/users/'+this.logged_user.id, this.logged_user)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.logged_user, response.data.data);
	                	
	                });
	        },
	        cancelEdit: function(){
	        	axios.get('api/users/'+this.logged_user.id)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.logged_user, response.data.data);
	                	this.editingUser = false;
	                	
	                });
	        },

	        editUser: function(){
	        	this.editingUser = true;
	        },

	        deleteUser: function() {
				if (window.confirm("Tem a certeza?")) {
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
			previewFile: function() {
				var preview = document.querySelector('img'); //selects the query named img
				this.file = document.querySelector('input[type=file]').files[0]; //sames as here
				var reader  = new FileReader();

				reader.onloadend = function () {
					preview.src = reader.result;
				}

				if (file) {
					reader.readAsDataURL(file); //reads the data as a URL
				} else {
					preview.src = "";
				}
			}, // end img function
			
	        logoutUser: function() {
	        	
	        	let head = {
					 headers: {
							 'Authorization': 'Bearer ' + localStorage.getItem('token'),
					 },
			 };
					axios.post('/api/logout', null, head)
					.then(response => {
						console.log(response);
						window.localStorage.clear();
						console.log("logout sucessfull");	
					
					this.$router.push('/login');
			})


	        	
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

			back: function () {
                this.$router.go(-1);
            }
		},
		mounted() {
			this.getLoggedUser();
			if (this.logged_user.admin) {
				this.$router.push('/adminEdit');
			}
		}
	}
</script>

<style scoped>
	.btn-danger{
		float: right;
	}
</style>
