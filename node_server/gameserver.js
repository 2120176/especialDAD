/*jshint esversion: 6 */

var app = require('http').createServer();

// CORS TRIALS
// var app = require('http').createServer(function(req,res){
// 	// Set CORS headers
// 	res.setHeader('Access-Control-Allow-Origin', 'http://dad.p6.dev');
// 	res.setHeader('Access-Control-Request-Method', '*');
// 	res.setHeader('Access-Control-Allow-Methods', 'OPTIONS, GET');
// 	res.setHeader('Access-Control-Allow-Credentials', true);
// 	res.setHeader('Access-Control-Allow-Headers', req.header.origin);
// 	if ( req.method === 'OPTIONS' ) {
// 		res.writeHead(200);
// 		res.end();
// 		return;
// 	}
// });

var io = require('socket.io')(app);

var BlackJackGame = require('./gamemodel.js');
var GameList = require('./gamelist.js');

app.listen(8080,  function(){
	console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

let games = new GameList();

io.on('connection', function (socket) {
    console.log('client has connected');

    socket.on('create_game', function (data){
    	let game = games.createGame(data.playerID, data.playerName, socket.id);
		socket.join(game.gameID, game.playerName, game.socketID);
		// Notifications to the client
		socket.emit('my_active_games_changed');
		io.emit('lobby_changed');
    });

    socket.on('join_game', function (data){
    	let game = games.joinGame(data.gameID, data.playerID, data.playerName, socket.id);
		socket.join(game.gameID, game.playerName, game.socketID);
		io.to(game.gameID).emit('my_active_games_changed');
		io.emit('lobby_changed');
    });

    socket.on('remove_game', function (data){
    	let game = games.removeGame(data.gameID, socket.id);
    	socket.emit('my_active_games_changed');
    });

    socket.on('play', function (data){
			console.log('play', data);
		let game = games.gameByID(data.gameID);
		if (game === null) {
			socket.emit('invalid_play', {'type': 'Invalid_Game', 'game': null});
			return;
		}
		var numPlayer = 0;
		if (game.player1SocketID == socket.id) {
			numPlayer = 1;
		} else if (game.player2SocketID == socket.id) {
            numPlayer = 2;
        } else if (game.player3SocketID == socket.id) {
            numPlayer = 3;
        } else if (game.player4SocketID == socket.id) {
            numPlayer = 4;
        }
		if (numPlayer === 0) {
			socket.emit('invalid_play', {'type': 'Invalid_Player', 'game': game});
			return;
		}
		if (game.play(numPlayer, data.index)) {
			io.to(game.gameID).emit('game_changed', game);
			socket.emit();
		} else {
			socket.emit('invalid_play', {'type': 'Invalid_Play', 'game': game});
			return;
		}
    });

    socket.on('get_game', function (data){
		let game = games.gameByID(data.gameID);
		socket.emit('game_changed', game);
    });

    socket.on('get_my_activegames', function (data){
    	var my_games= games.getConnectedGamesOf(socket.id);
    	socket.emit('my_active_games', my_games);
    });

    socket.on('get_my_lobby_games', function (){
    	var my_games= games.getLobbyGamesOf(socket.id);
    	socket.emit('my_lobby_games', my_games);
    });

    socket.on('authentication', function (token){
        let config = {
            headers: {
				'Authorization': 'Bearer ' + token}
            };
            axios.get(api_url+'/api/user', config).then(response =>{
                this.user = response.data;
                socket.emit('authenticated', this.user);
            }).catch(error => {
                console.log("You are not logged in");
                socket.emit('not_authenticated');
                console.log(error.message);
            });
    });


    socket.on('start_game', function (data){
		let gameID = data.gameID;
		games.startGame(gameID);
		io.to(data.gameID).emit('my_active_games_changed');
		console.log("Game DATA: " + data);
		console.log('game started');
		io.emit('lobby_changed');
	});


	socket.on('renuncia_click', function (data){
		let gameID = data.gameID;
		games.renunciaClick(gameID);
	});

});
