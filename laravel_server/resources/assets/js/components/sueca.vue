<template>
	<div>
        <div>
            <div class="btn-group">
                <button v-on:click="logout()" class="btn btn-primary">Logout</button>
                <router-link class="btn btn-default" :to="{ path: '/userAccount' }">
                    Account Settings
                </router-link>
                <router-link class="btn btn-default" :to="{ path: '/playerStatistics' }">
                    Statistics
                </router-link>
            </div>


            <h3 class="text-center">{{ title }}</h3>
            <br>
            <h2>Current Player : {{ currentPlayer }}</h2>
            <!--<p>Set current player name <input v-model.trim="currentPlayer"></p>
            <p><em>Player name replaces authentication! Use different names on different browsers, and don't change it frequently.</em></p>-->
            <hr>
            <h3 class="text-center">Lobby</h3>
            <p><button class="btn btn-xs btn-success" v-on:click.prevent="createGame">Create a New Game</button></p>
            <hr>
            <h4>Pending games (<a @click.prevent="loadLobby">Refresh</a>)</h4>
            <lobby :games="lobbyGames" @join-click="join"></lobby>
            <template v-for="game in activeGames">
                <game :game="game"></game>
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
    import Lobby from './lobby.vue';
    import GameSueca from './game-sueca.vue';

	export default {
        data: function(){
			return {
                title: 'Sueca',
                currentPlayer: '',
                lobbyGames: [],
                activeGames: [],
                socketId: "",
                playerId: "",
                gameID: ""
            }
        },
        sockets:{
            connect(){
                console.log('socket connected');
                this.socketId = this.$socket.id;
            },
            discconnect(){
                console.log('socket disconnected');
                this.socketId = "";
            },
            lobby_changed(){
                this.loadLobby();
            },
            my_active_games_changed(){
                this.loadActiveGames();
            },
            my_active_games(games){
                this.activeGames = games;
            },
            my_lobby_games(games){
                this.lobbyGames = games;
            },
            invalid_play(errorObject){
                if (errorObject.type == 'Invalid_Game') {
                    alert("Error: Game does not exist on the server");
                } else if (errorObject.type == 'Invalid_Player') {
                    alert("Error: Player not valid for this game");
                } else if (errorObject.type == 'Invalid_Play') {
                    alert("Error: You lost");
                } else {
                    alert("Error: " + errorObject.type);
                }

            },
            game_changed(game){
                for (var lobbyGame of this.lobbyGames) {
                    if (game.gameID == lobbyGame.gameID) {
                        Object.assign(lobbyGame, game);
                        break;
                    }
                }
                for (var activeGame of this. activeGames) {
                    if (game.gameID == activeGame.gameID) {
                        Object.assign(activeGame, game);
                        break;
                    }
                }
            },
        },
        methods: {
            loadLobby(){
                this.$socket.emit('get_my_lobby_games');
            },
            loadActiveGames(){
                this.$socket.emit('get_my_activegames');
            },
            createGame(){
                if (this.currentPlayer == "") {
                    alert('Current Player is Empty - Cannot Create a Game');
                    return;
                }
                else {
                    this.$socket.emit('create_game', { gameID: this.gameID });
                }
            },
            join(game){
                if (game.player1 == this.currentPlayer) {
                    alert('Cannot join a game because your name is the same as Player 1');
                    return;
                }
								console.log("SUECA VUE GAME ID: " + game.gameID);
                this.$socket.emit('join_game', {gameID: game.gameID, playerID: this.playerID, playerName: this.currentPlayer });
            },
            play(game, index){
                this.$socket.emit('play', {gameID: game.gameID, index: index });
            },
            close(game){
                this.$socket.emit('remove_game', {gameID: game.gameID });
            },
            getLoggedUser: function () {
                let token = localStorage.getItem('token');
                //console.log("get Logged User");
                axios.get('/api/user', {
                    headers: {'Content-Type' : 'application/json',
                        'Authorization' : 'Bearer ' + token }
                }).then(response => {
                    this.currentPlayer = response.data.name;
                    this.playerID = response.data.id;
                    this.nickname
                    //console.log (this.logged_user.id);
                    this.isUserLogged = true;
                    console.log(this.currentPlayer);

                }).catch(error => {
                    // não está autenticado
                    this.isUserLogged = false;
                    console.log(error);
                });
            }, // end function
        },
        components: {
            'lobby': Lobby,
            'game': GameSueca,
        },
        mounted() {
            this.getLoggedUser();
            this.loadLobby();

        }

    }
</script>

<style>

</style>
