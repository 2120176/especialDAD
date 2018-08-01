<template>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th>ID</th>
	            <th>Player 1</th>
				<th>Player 2</th>
				<th>Player 3</th>
				<th>Player 4</th>
	            <th>Actions</th>
	        </tr>
	    </thead>
	    <tbody>
	      <!--  <tr v-for="game in games"  :key="game.gameID">
	            <td>{{ game.gameID }}</td>
	            <td>{{ game.player1 }}</td>
	            <td v-if="gameIsFull(game)">
	                <a class="btn btn-xs btn-danger">Game is Full</a>
	            </td>
							<td v-else>
								<a class="btn btn-xs btn-danger" v-on:click.prevent="join(game)">Join</a>
							</td>
	        </tr>-->
			<tr v-for="game in games"  :key="game.gameID">
				<td>{{ game.gameID }}</td>
				<!--<td> {{game.arrayPlayers[0].name}}</td>-->

				<td v-if="game.arrayPlayers[0]"> {{game.arrayPlayers[0].name}}</td>

				<td v-if="game.arrayPlayers[1]"> {{game.arrayPlayers[1].name}}</td>
				<td v-if="!game.arrayPlayers[1]"> Waiting for player 2</td>

				<td v-if="game.arrayPlayers[2]"> {{game.arrayPlayers[2].name}}</td>
				<td v-if="!game.arrayPlayers[2]"> Waiting for player 3</td>

				<td v-if="game.arrayPlayers[3]"> {{game.arrayPlayers[3].name}}</td>
				<td v-if="!game.arrayPlayers[3]"> Waiting for player 4</td>

				<td>
					<a class="btn btn-xs btn-primary" v-on:click.prevent="join(game)">Join</a>
				</td>

			</tr>







	    </tbody>
	</table>
</template>

<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		props: ['games'],
        data: function(){
            return {
                playerName: "",
            }
        },
        methods: {
            join: function (game) {
                this.$emit('join-click', game);
            },
            gameIsFull(game) {
                return game.numPlayers >= 4;
            },
            getLoggedUser: function () {
                let token = localStorage.getItem('token');
                //console.log("get Logged User");
                axios.get('/api/user', {
                    headers: {'Content-Type' : 'application/json',
                        'Authorization' : 'Bearer ' + token }
                }).then(response => {
                    this.playerName = response.data.name;
                    console.log(this.currentPlayer);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                    console.log(error);
                });
            },
        },
        mounted() {
            this.getLoggedUser();
        }
	}
</script>

<style scoped>

</style>
