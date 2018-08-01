/*jshint esversion: 6 */

var BlackJackGame = require('./gamemodel.js');

class GameList {
	constructor() {
        this.contadorID = 0;
        this.games = new Map();
    }

    gameByID(gameID) {
    	let game = this.games.get(gameID);
    	return game;
    }

    createGame(playerID, playerName, socketID) {
    	this.contadorID = this.contadorID+1;
    	var game = new BlackJackGame(this.contadorID, playerID, playerName, socketID);
    	//game.player1SocketID = socketID;
    	this.games.set(game.gameID, game);
    	return game;
    }

    joinGame(gameID, playerID, playerName, socketID) {
    	let game = this.gameByID(gameID);
    	if (game===null) {
    		return null;
    	}

			console.log('gameID: ', gameID);
			console.log('playerName: ', playerName);
			console.log('socketID: ', socketID);


			if (game.player1SocketID != socketID) {
				if (game.player2SocketID == null) {
					game.join(playerID, playerName, socketID);
					game.player2SocketID = socketID;
					console.log('player2 joined', game.player2SocketID);
					console.log("___________________________________");

					game.arraySockets.push(game.player1SocketID);
					game.arraySockets.push(game.player2SocketID);

					return game;
				}
				if (game.player3SocketID == null) {
                    game.join(playerID, playerName, socketID);
					game.player3SocketID = socketID;
					console.log('player3 joined', game.player3SocketID);
					console.log("___________________________________");
					game.arraySockets.push(game.player3SocketID);

					return game;
				}
				if (game.player4SocketID == null) {
                    game.join(playerID, playerName, socketID);
					game.player4SocketID = socketID;
					console.log('player4 joined', game.player4SocketID);
					console.log("___________________________________");
					game.arraySockets.push(game.player4SocketID);
					console.log("Array SOCKETS: " + game.arraySockets);

					return game;
				}

			}
    	return game;
    }

    removeGame(gameID, socketID) {
    	let game = this.gameByID(gameID);
    	if (game===null) {
    		return null;
    	}
    	if (game.player1SocketID == socketID) {
    		game.player1SocketID = "";
    	} else if (game.player2SocketID == socketID) {
    		game.player2SocketID = "";
    	}
    	if ((game.player1SocketID === "") && (game.player2SocketID === "")) {
    		this.games.delete(gameID);
    	}
    	return game;
    }

    getConnectedGamesOf(socketID) {
    	let games = [];
    	for (var [key, value] of this.games) {
    		if ((value.player1SocketID == socketID) || (value.player2SocketID == socketID) ||
								(value.player3SocketID == socketID) || (value.player4SocketID == socketID)) {
    			games.push(value);
    		}
		}
		return games;
    }

    getLobbyGamesOf(socketID) {
    	let games = [];
    	for (var [key, value] of this.games) {
    		if ((!value.gameStarted) && (!value.gameEnded))  {
    			if ((value.player1SocketID != socketID) && (value.player2SocketID != socketID) &&
								(value.player3SocketID != socketID) && (value.player4SocketID != socketID)) {
    				games.push(value);
    			}
    		}
		}
		return games;
    }



    startGame(gameID, socketId) {

        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
				// console.log(game.player1SocketID);
        game.startGame(gameID);
        return game;
}
}

module.exports = GameList;
