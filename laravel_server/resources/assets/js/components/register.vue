<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div  v-if="ifError"class="alert alert-danger alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>{{ error }}</strong>
        </div>
        <div  v-show="ifSuccess"class="alert alert-success alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>{{ success }}</strong>
        </div>
        <h1>Register</h1>

        <form @submit.prevent="register(user)">
          <div class="text-left">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" placeholder="Your name" v-model="user.name">
            </div>

            <div class="form-group">
              <label for="name">NickName</label>
              <input type="text" class="form-control" id="nickName" placeholder="Your nickname" v-model="user.nickname">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" placeholder="Your email" v-model="user.email">
            </div>

            <div class="form-group" >
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Your password" v-model="user.password">
            </div>

            <div class="form-group" >
              <label for="password_confirmation">Password Confirmation</label>
              <input type="password" class="form-control" id="password_confirmation" placeholder="Your password confirmation" v-model="user.password_confirmation">
            </div>
          </div>

            <button class="btn btn-warning pull-left" v-on:click.prevent="back()">Back</button>
          <div class="btn-group pull-right">
            <button class="btn btn-primary" v-on:click.prevent="register">Register</button>
            <button class="btn btn-danger" v-on:click.prevent="resetUser">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
export default {
  data() {
    return {
      user : {
          email: null,
          password: null,
          name: null,
          nickname: null,
          password_confirmation: null,
          logged_user: {},
          isUserLogged: false,
      },
        ifError: false,
        error: null,
        ifSuccess: false,
        success: null
      //registerError: false
    };
  },

  methods: {

      resetUser() {
          this.user = {
              name: null,
              nickname: null,
              email: null,
              password: null,
              password_confirmation: null,
          }
      },

      register() {          //register(user)
          axios.post('/api/users', this.user)
              .then(response => {
                  console.log(response);
                  //let successMessage = response.data.message;
                  //alert('Register pending, please check your e-mail');
                  this.ifSuccess = true;
                  this.success = response.data.message;
              })
              .catch(error => {
                  console.log('register Error: ' + error);
                  //alert('Erro: Dados não conformes!');
                  this.ifError = true;
                  //this.error = json_decode(error.response.data.msg);
                  this.error = "Algum dado inserido está errado/já existe, pff tente novamente";

          resetUser();
              })
      },


      getLoggedUser: function () {
          this.token = localStorage.getItem('token');
          //console.log("get Logged User");
          axios.get('/api/user', {
              headers: {
                  'Content-Type': 'application/json',
                  'Authorization': 'Bearer ' + this.token
              }
          }).then(response => {
              this.logged_user = response.data;
              //console.log (this.logged_user.id);
              this.isUserLogged = true;
              console.log(this.logged_user);

          }).catch(error => {
              // não está autenticado
              this.isUserLogged = false;
          });
      }, // end function

      back: function () {
          this.$router.go(-1);
      }
  },
  mounted () {

    // this.getLoggedUser();
  }
}
</script>


<scope>

</scope>
