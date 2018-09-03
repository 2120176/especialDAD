<template >
  <div v-if="!isUserLogged">
   

    <form @submit.prevent="login(user)">
        <div class="jumbotron vertical-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="text-center">Welcome to Sueca Online!</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div  v-if="loginError"class="alert alert-danger alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>{{ error }}</strong>
                        </div>
                        <!--<h1>Login</h1>-->
                        <form v-on:submit.prevent="handleLoginFormSubmit()">
                            <div class="text-left">
                                <div class="form-group">
                                    <label>Email /Nickname</label>
                                    <input type="text" class="form-control" placeholder="Your email/nickname" v-model="user.email">
                                </div>
                                <div class="form-group">
                                    <label >Password</label>
                                    <input type="password" class="form-control" placeholder="Your password" v-model="user.password">
                                </div>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-primary" v-on:click.prevent="login()">Login</a>
                                <router-link class="btn btn-default" :to="{ path: '/register' }">
                                    Registo
                                </router-link>
                                <router-link :to="{ path: '/forgot' }">
                                    Forgot password?
                                </router-link>
                            </div>
                            <div class="btn-group pull-right">
                                <router-link class="btn btn-danger" :to="{ path: '/playerStatistics' }">
                                    Estatisticas
                                </router-link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>

  </div>
</template>

<script type="text/javascript">
export default {
  data() {
    return {
      newUser : {},
      user : {
        email: null,
        password: null,
      },
        loginError: false,
        isUserLogged: false,
        token : null,
        logged_user: {},
        error: '',
    };
  },
  methods: {
    login: function() {
      //console.log(user);
      //let user = {email : user.email, password : user.password};
      axios.post('/api/login', this.user, {
        headers: {'Content-Type' : 'application/json'}
      }).then(response => {
        // buscar o token do user logado
        // console.log('Response: ');
        // console.log(response);
        let token = response.data.access_token;
        // guardar na localStorage o token
        localStorage.setItem('token', token);



        // saber se o user é admin ou não e redicioná-lo para a vista correta

        axios.get('/api/user', { 
                    headers: {'Content-Type' : 'application/json',
                          'Authorization' : 'Bearer ' + token }
              }).then(response => {
                this.newUser = response.data;
                // console.log (this.newUser);
                  if (this.newUser.verified == "1" && this.newUser.blocked != "1") { // conta ativada faz login
                    if (this.newUser.admin == "1") {
                      console.log ("sou admin");
                      
                      this.$router.push('/adminMasterPage');
                    } else {
                        console.log ("sou user normal");
                      
                        this.$router.push('/sueca');
                      }
                  } else {
                    
                    window.localStorage.clear();
                    this.$router.push('/');
                  }
                  
              }).catch(error => {
                console.log(error);
              });

      }).catch((error) => {
        // Something went wrong!
        console.log('Login Error: ' + error.response.data.msg);
        this.loginError = true;
        this.error = error.response.data.msg;
        this.user.email =  null;
        this.user.password = null;
      });
    },
    forgot() {
      this.$router.push('/forgot');      
    },
    register() {
      this.$router.push('/register');
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
  mounted () {
    // no load da pagina saber se existe algum user logado pelo fb
    // FB.getLoginStatus(function(response) {
    //   statusChangeCallback(response);

    //   if (response.status = "connected") { // não tenho admin
    //     this.getLoggedUser();
    //     if (isUserLogged)
    //       this.$router.push('/');  
    //     else {

    //     }

    //   } else if( response.status = "not_authorized") { // não tenho admin
    //     this.$router.push('/login');
    //   } else if( response.status = "unknown") { // não tenho admin
    //     this.$router.push('/register');
    //   }


    // });

      //  this.getLoggedUser();
    
    
  }
}
</script>


<scope>

</scope>
<style>
    .vertical-center {
        min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
        min-height: 100vh; /* These two lines are counted as one 🙂       */

        display: flex;
        align-items: center;
    }
</style>