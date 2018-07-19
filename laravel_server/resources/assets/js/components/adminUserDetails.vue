<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th><strong>Name</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Nickname</strong></th>
            <th><strong>Blocked</strong></th>
            <!-- <th><strong>Block Reason</strong></th>             -->
            <th><strong>Admin</strong></th>
            <th><strong>Total Points</strong></th>
            <th><strong>Total Games</strong></th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="!aux" v-bind:src="getUser(user)">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.nickname }}</td>

            <!-- Just one or two -->
            <td v-if="user.blocked"> Sim - Razão: {{ user.reason_blocked }} </td>
            <td v-else> Não </td>
            
            <!-- Just one -->
            <td v-if="user.admin"> Sim </td>
            <td v-else> Não </td>

            <td>{{ user.total_points }}</td>
            <td>{{ user.total_games_played }}</td>



        </tr>
        </tbody>
            <a class="btn btn-primary" @click.prevent="home()">Home</a>

    </table>
</template>

<script type="text/javascript">

    export default {
        data: function(){
            return {
              user: null,
              aux : true,
            }
        },
        methods: {

          getUser: function(){
            if (this.aux){
              axios.get('api/users/' + this.$route.params.id)
                  .then(response => {
                      this.user = response.data.data;
                      console.log(this.user);
                      this.aux = false;
                  });
                }
          },
          home: function () {
            this.$router.push('/adminMasterPage');
          },

        },
        components: {

        },

        mounted() {
          this.getUser();
        },
    }
</script>
