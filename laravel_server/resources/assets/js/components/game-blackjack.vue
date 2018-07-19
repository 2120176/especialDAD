<template>
	<div class="gameseparator">
        <div>
            <h2 class="text-center">Game {{ game.gameID }}</h2>
            <br>
        </div>
        <div class="game-zone-content">
            <div class="alert" :class="alerttype">
                <strong>{{ message }} &nbsp;&nbsp;&nbsp;&nbsp;<a v-show="game.gameEnded" v-on:click.prevent="closeGame">Close Game</a></strong>

            </div>
<!--
            <div>
                    <template v-if= "user.id == game.gameOwner">
    -->                  <p><button class="btn btn-xs btn-success" v-show="!game.gameStarted" v-on:click.prevent="startGame(game)">Start Game</button></p>
          <!--        </template>
					</div> -->
<div class="board">
	<div class="line" v-for="(line, player) in game.boardGame" >
			<div v-for="(piece, index) in line" >
					<img class="img" v-bind:src="pieceImageURL(piece)" v-on:click.prevent="hitMe(game, index)">
			</div>
			<!-- <button class="btn btn-xs btn-primary" v-on:click.prevent="placeBet(game)">Bet</button> -->
	</div>


	<div class="optButtons" style="text-align:center;">
		<p>
			<button class="btn btn-xs btn-primary" v-on:click.prevent="placeBet(game)">Bet</button>
			<button class="btn btn-xs btn-danger" v-on:click.prevent="giveUp(game)">Give UP!</button>
		</p>
	</div>

</div>

<br><br>

            <hr>
        </div>
    </div>
</template>

<script type="text/javascript">
	export default {
        props: ['game', 'user'],
        data: function(){
			return {

            }
        },

        sockets: {
            authenticated(user){
                this.user = user;
                console.log(this.user);
            },
        },
        computed: {
            ownPlayerNumber(){
                if (this.game.player1SocketID == this.$parent.socketId) {
                    return 1;
                } else if (this.game.player2SocketID == this.$parent.socketId) {
                    return 2;
                }
                return 0;
            },
            ownPlayerName(){
                var ownNumber = this.ownPlayerNumber;
                if (ownNumber == 1)
                    return this.game.player1;
                if (ownNumber == 2)
                    return this.game.player2;
                return "Unknown";
            },
            adversaryPlayerName(){
                var ownNumber = this.ownPlayerNumber;
                if (ownNumber == 1)
                    return this.game.player2;
                if (ownNumber == 2)
                    return this.game.player1;
                return "Unknown";
            },
            message(){
                if (!this.game.gameStarted) {
                    return "Game has not started yet";
                } else if (this.game.gameEnded) {
                    if (this.game.winner == this.ownPlayerNumber) {
                        return "Game has ended. You Win.";
                    } else if (this.game.winner == 0) {
                        return "Game has ended. There was a tie.";
                    }
                    return "Game has ended and " + this.adversaryPlayerName + " has won. You lost.";
                } else {
                    if (this.game.playerTurn == this.ownPlayerNumber) {
                        return "It's your turn";
                    } else {
                        return "It's " + this.adversaryPlayerName + " turn";
                    }
                }
                return "Game is inconsistent";
            },
            alerttype(){
                if (!this.game.gameStarted) {
                    return "alert-warning";
                } else if (this.game.gameEnded) {
                    if (this.game.winner == this.ownPlayerNumber) {
                        return "alert-success";
                    } else if (this.game.winner == 0) {
                        return "alert-info";
                    }
                    return "alert-danger";
                }
                if (this.game.playerTurn == this.ownPlayerNumber) {
                    return "alert-success";
                } else {
                    return "alert-info";
                }

            }
        },
        methods: {
					hitMe(game, index){
							// if (this.game.boardGame[index] == "Empty") {
							// 		this.$parent.play(this.game, index);
							// }
						this.$socket.emit('play', {gameID: game.gameID, index: index});
					},

            pieceImageURL (pieceNumber) {
                var imgSrc = String(pieceNumber);
                return 'img/' + imgSrc + '.png';
            },
            closeGame (){
                this.$parent.close(this.game);
            },
            startGame(game) {
                this.$socket.emit('start_game', {gameID : game.gameID});
            }
        }
    }
</script>

<style scoped>

.gameseparator{
    border-style: solid;
    border-width: 2px 0 0 0;
    border-color: black;
}
</style>
