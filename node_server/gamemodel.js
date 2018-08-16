/*jshint esversion: 6 */

class BlackJackGame {
    constructor(ID, playerID, playerName, socketID) {
        this.numPlayers = 1;
        this.winner = 0;
        this.winnerP = 0;
        this.arrayBaralho = [];
        this.arrayPontuacao = [];
        this.arraySockets = [];
        //this.gameOwner = owner.id;
        this.arrayPlayers = []; // array populado quando feito o join
        this.join(playerID, playerName, socketID);
        this.gameEnded = false;
        this.gameStarted = false;
        this.gameID = ID;
        this.gameTurn = 1;
        this.currentCard = 0;
        this.boardGame;
        this.playerGame;
        this.trunfoGame;
        this.trunfoCard; // carta de trunfo
        this.baralhoPlayer1= [];
        this.baralhoPlayer2= [];
        this.baralhoPlayer3= [];
        this.baralhoPlayer4= [];
        this.whosTurn;
        this.pontuacaoP1P3 = 0;
        this.pontuacaoP2P4 = 0;
        this.firstPlayer; // variavle estatica
        this.firtCardPlayed;
        this.roundNumber = 0;

    }

    startGame() {

       // if (this.numPlayers != 4) {
       //   return;
       // }

       //incializar arrayBaralho
       // randomize arrayBaralho
       this.inicializarArrayBaralho();
       // console.log("arrayBaralho");
       // console.log(this.arrayBaralho);

       //Zerar pontuacoes
       this.zerarPontuacoes();
       // console.log("arrayPontuacao");
       // console.log(this.arrayPontuacao);

       // inicialize boardGame
       this.boardGame = new Array(3);
       this.playerGame = new Array(4);
       this.trunfoGame = new Array(1);

       for (let i = 0; i < 3; i++) {
         this.boardGame[i] = new Array(3);
       }

       for (let i = 0; i < 4; i++) {
         this.playerGame[i] = new Array(11);
       }

       for (let i = 0; i < 1; i++) {
         this.trunfoGame[i] = new Array(1);
       }


       /* preenchimento da boardGame com todos os componentes */
       //COLOCAÇÃO SITOS PLAYER
       this.resetBoardGame();

       // popular board game (cartas jogadas)
       for (let j = 0; j < 3; j++) {
         for (let k = 0; k < 3; k++) {
           if (this.boardGame[j][k] != 'Player1'
             && this.boardGame[j][k] != 'Player2'
             && this.boardGame[j][k] != 'Player3'
             && this.boardGame[j][k] != 'Player4') {
             this.boardGame[j][k] = 'Empty';
           }
         }
       }
       /* fim da população do boardGame */


       //Distribuir Cards pro players
       // console.log("Sockets " + this.arraySockets);
       // console.log("Player " + this.arrayPlayers);


       // for (let i = 1; i < this.playerGame.length; i++) {
       //   this.playerGame[0][0] = "Player1";
       //   this.playerGame[i][0] = this.arrayBaralho[i-1];
       //   this.currentCard++;
       // }

       // popular P1,P2,P3,P4
       let xy= 0;
       for (let i = 0; i < 4; i++) {
         for (let j = 0; j < this.playerGame.length; j++) {
           xy=i+1;
           this.playerGame[i][0] = "Player" + xy;
         }
       }


       // random 1º jogador

       let randomPlayer = Math.floor(Math.random() * 3); // queremos o zero :D
       console.log('randomPlayer' + randomPlayer);
       this.whosTurn = randomPlayer+1;
       this.firstPlayer = randomPlayer+1;
       let jaTenhoCartas = 0;
       // console.log('trunfo baralho', this.arrayBaralho[0]);
       this.trunfoCard = this.arrayBaralho[0];
       /* popular cartas no playerGame */
       for (let k = randomPlayer; k <=3 ; k++){
         if(jaTenhoCartas != 4) {
           for (var m = 1; m < 11; m++) {

             this.playerGame[k][m] = "semFace";

             // this.playerGame[k][m] = this.arrayBaralho[this.currentCard];
             // this.currentCard++;
           }
           jaTenhoCartas++;

           if (k == 3) {
             k = -1;
           }
         } else {
           k = 4;
         }

       } // fim do for do k



       this.trunfoGame[0][0] = this.arrayBaralho[0];

       this.gameTurn = 2;
       this.gameStarted = true;
     }

    zerarPontuacoes () {
        for (let i = 0; i< this.numPlayers; i++) {
            this.arrayPontuacao[i] = 0;
        }
    }

    inicializarArrayBaralho() {
        let baralho = ["p1", "p2", "p3", "p4", "p5", "p6", "p7", "p11", "p12", "p13",
            "c1", "c2", "c3", "c4", "c5", "c6", "c7", "c11", "c12", "c13",
            "o1", "o2", "o3", "o4", "o5", "o6", "o7", "o11", "o12", "o13",
            "e1", "e2", "e3", "e4", "e5", "e6", "e7", "e11", "e12", "e13"
        ];
        this.arrayBaralho = this.shuffleArray(baralho);
    }

    shuffleArray(array){
        let arraySize = array.length;
        while(arraySize > 0){
            let index = Math.floor(Math.random()* arraySize);
            arraySize --;
            let temp = array[arraySize];
            array[arraySize] = array[index];
            array[index] = temp;
        }
        return array;

    }

    join(playerID, playerName, socketID){

        if (this.numPlayers < 4) {
            let teamNumber = undefined;
            if (this.numPlayers % 2 != 0) {
                teamNumber = 1;
            } else {
                teamNumber = 2;
            }
            let player = {
                playerID: playerID,
                socketID: socketID,
                name: playerName,
                score: 0,
                team: teamNumber,
                hand: []
            }
            this.numPlayers = this.arrayPlayers.push(player);
        }

    }

    hasPlayer(playerID){
        for (let i = 0; i<this.numPlayers; i++) {
            if (this.arrayPlayers[i] === playerID)
                return true;
        }
    }

    // função para as pontuações!!
    removeFirstCharString(card){ // receber a string da carta
        while(card.charAt(0) === 'c' || card.charAt(0) === 'e' || card.charAt(0) === 'p' || card.charAt(0) === 'o' ){
            card = card.substr(1);
        }

        return card;

    }

    play(playerID, index){ // index = posicao da carta pedida
        if (!this.gameStarted) {
            return;
        }

        if (this.gameEnded) {
            return;
        }



        if (playerID != 1 && this.whosTurn != 0) {
            if (playerID-1 == this.whosTurn+1) {
                return;
            }
        }
        this.whosTurn = 0;

        if (this.playerGame[playerID-1][index] == "Empty") {
            return;
        }

        for (let k = this.whosTurn; k <=3 ; k++){
                for (var m = 1; m < 11; m++) {
                    this.playerGame[2][m] = this.arrayBaralho[this.currentCard];
                    this.currentCard++;
                }

                /*if (k == 3) {
                    k = -1;
                }*/

        } // fim do for do k


        /* if (playerID == 1) {

             this.boardGame[0][1] = this.playerGame[playerID-1][index];

         }else if (playerID == 2) {

             this.boardGame[1][2] = this.playerGame[playerID-1][index];

         }else if (playerID == 3) {

             this.boardGame[2][1] = this.playerGame[playerID-1][index];

         }else if (playerID == 4) {

             this.boardGame[1][0] = this.playerGame[playerID-1][index];

         }

         this.roundNumber++;

         //VERIFICAÇÃO DE QUAL A 1ª CARTA JOGADA
         if (this.whosTurn == this.firstPlayer) {
             this.firtCardPlayed = this.playerGame[playerID-1][index];
             console.log('carta: ' + this.firtCardPlayed);
         }

         this.playerGame[playerID-1][index] = "Empty";


         this.whosTurn++;

         if (this.whosTurn == 4) {
             this.whosTurn = 0;
         }


         // verificar se o boardGame está cheio
         this.checkRound();

         if (this.roundNumber == 10) { //jogo terminou
             this.gameEnded == true;

           /*
            if (houve renuncia) ???


             if (this.pontuacaoP1P3 > this.pontuacaoP2P4) {
                 // player 1 e player 3 ganham 2 pontos
             } else if (this.pontuacaoP1P3 < this.pontuacaoP2P4) {
                 // player 2 e 4 recebem 2 pontos
             } else {
                 // cada player recebe 1 ponto
             }
         }*/

        return true;
    } // end play function

    checkRound() {
        if (this.boardGame[0][1] != "Player1" && this.boardGame[1][2] != "Player2" && this.boardGame[2][1] != "Player3" && this.boardGame[1][0] != "Player4")
        {
            // ver qual a equipa que vai receber pontuacoes

            //validar se há trunfo

            var arrayNaipe = [];
            var arrayCartas = []; // array com o numero das cartas
            var arrayNaipeCarta = [];

            let naipeTrunfo = this.trunfoCard.charAt(0);

            let naipeP1 = this.boardGame[0][1].charAt(0);
            let naipeP2 = this.boardGame[1][2].charAt(0);
            let naipeP3 = this.boardGame[2][1].charAt(0);
            let naipeP4 = this.boardGame[1][0].charAt(0);


            let cartaP1 = this.boardGame[0][1].substr(1);
            let cartaP2 = this.boardGame[1][2].substr(1);
            let cartaP3 = this.boardGame[2][1].substr(1);
            let cartaP4 = this.boardGame[1][0].substr(1);

            var strongestCard;

            arrayNaipe.push(naipeP1, naipeP2, naipeP3, naipeP4); // naipes
            arrayCartas.push(cartaP1, cartaP2, cartaP3, cartaP4); // numero das cartas
            arrayNaipeCarta.push(this.boardGame[0][1],
                this.boardGame[1][2],
                this.boardGame[2][1],
                this.boardGame[1][0]); // cartas e naipes

            // console.log('arrayNaipe: ', arrayNaipe);
            // console.log('arrayCartas: ', arrayCartas);
            var pontuacaoRonda = 0;
            var aux = 0;

            var numberTrunfos = 0;
            var cartasTrunfo = []; // todos os trunfos (numeros) jogados na ronda

            // Numero de trunfos jogados na ronda
            for (let i = 0; i < arrayNaipe.length; i++) {
                if (naipeTrunfo == arrayNaipe[i]) {
                    cartasTrunfo.push(arrayCartas[i]);

                    numberTrunfos++;
                }
            }

            // calcular pontuacao das 4 cartas
            for (var i = 0; i < arrayCartas.length; i++) {
                aux = this.incrementaPontuacao(arrayCartas[i]);
                pontuacaoRonda+=aux;
                aux = 0;
            }

            console.log('pontuacaoRonda: ', pontuacaoRonda);

            // Caso só haja um trunfo na ronda
            if (numberTrunfos == 1) {
                console.log(" ======= Ronda com apenas 1 trunfo");
                // Somar pontuacao
                if (this.boardGame[0][1] == cartasTrunfo[0] || this.boardGame[2][1] == cartasTrunfo[0])
                {
                    this.pontuacaoP1P3 += pontuacaoRonda;
                    if(this.boardGame[0][1] == cartasTrunfo[0]) { // Player 1
                        this.whosTurn = 0;
                    } else { // Player 3
                        this.whosTurn = 2;
                    }
                }
                else{
                    this.pontuacaoP2P4 += pontuacaoRonda;
                    if(this.boardGame[1][2] == cartasTrunfo[0]) { // Player 2
                        this.whosTurn = 1;
                    } else { // Player 4
                        this.whosTurn = 3;
                    }
                }
                console.log("Pontuacao Equipa P1+P3: ", this.pontuacaoP1P3);
                console.log("Pontuacao Equipa P2+P4: ", this.pontuacaoP2P4);

            }

            // Caso haja mais que um trunfo!

            var highestTrunfo;
            if (numberTrunfos > 1) {
                console.log(" ======= Ronda com "+numberTrunfos+" trunfo");

                // Saber qual o trunfo mais alto da ronda
                highestTrunfo = this.getHighestTrunfo(cartasTrunfo);

                // Somar pontuacao
                if (this.boardGame[0][1] == highestTrunfo || this.boardGame[2][1] == highestTrunfo)
                {
                    this.pontuacaoP1P3 += pontuacaoRonda;

                    if(this.boardGame[0][1] == cartasTrunfo[0]) { // Player 1
                        this.whosTurn = 0;
                    } else { // Player 3
                        this.whosTurn = 2;
                    }
                }
                else {
                    this.pontuacaoP2P4 += pontuacaoRonda;
                    if(this.boardGame[1][2] == cartasTrunfo[0]) { // Player 2
                        this.whosTurn = 1;
                    } else { // Player 4
                        this.whosTurn = 3;
                    }
                }
                console.log("Pontuacao Equipa P1+P3: ", this.pontuacaoP1P3);
                console.log("Pontuacao Equipa P2+P4: ", this.pontuacaoP2P4);

            }

            // caso não haja trunfo em jogo, saber qual a mais alta do naipe puxado
            var highCardSemTrunfo;
            if (numberTrunfos == 0) {
                console.log(" ======= Ronda com nenhum trunfo");

                for (let y = 0; y < arrayNaipeCarta.length; y++) {
                    if (this.firtCardPlayed.charAt(0) == arrayNaipe[y]) {
                        highCardSemTrunfo = this.getHighestTrunfo(arrayNaipe);
                    }
                }

                // Somar pontuacao
                if (this.boardGame[0][1] == highCardSemTrunfo || this.boardGame[2][1] == highCardSemTrunfo)
                {
                    this.pontuacaoP1P3 += pontuacaoRonda;
                    if(this.boardGame[0][1] == cartasTrunfo[0]) { // Player 1
                        this.whosTurn = 0;
                    } else { // Player 3
                        this.whosTurn = 2;
                    }
                }
                else{
                    this.pontuacaoP2P4 += pontuacaoRonda;
                    if(this.boardGame[1][2] == cartasTrunfo[0]) { // Player 2
                        this.whosTurn = 1;
                    } else { // Player 4
                        this.whosTurn = 3;
                    }
                }

                console.log("Pontuacao Equipa P1+P3: ", this.pontuacaoP1P3);
                console.log("Pontuacao Equipa P2+P4: ", this.pontuacaoP2P4);

            }


            this.resetBoardGame(); // limpa o boardGame para nova rodada

        } // End IF game is complete
    }

    resetBoardGame () {
        this.boardGame[0][1] = 'Player1';
        this.boardGame[1][2] = 'Player2';
        this.boardGame[2][1] = 'Player3';
        this.boardGame[1][0] = 'Player4';
    }

    getHighestTrunfo(cartasTrunfo) {
        var highestTrunfoAux;
        for(let a = 0; a < cartasTrunfo.length; a++) {
            if (cartasTrunfo[a] == 1) { // As
                highestTrunfoAux = cartasTrunfo[a];
                a = cartasTrunfo.length;
            } else if (cartasTrunfo[a] == 7) { // 7
                highestTrunfoAux = cartasTrunfo[a];
            } else if (cartasTrunfo[a] == 13) { // Rei
                highestTrunfoAux = cartasTrunfo[a];
            } else if (cartasTrunfo[a] == 12) { // Dama/Rainha
                highestTrunfoAux = cartasTrunfo[a];
            } else if (cartasTrunfo[a] == 11) { // Valete
                highestTrunfoAux = cartasTrunfo[a];
            } else if (cartasTrunfo[a] == 6) { // 6
                highestTrunfoAux = cartasTrunfo[a];
            }else if (cartasTrunfo[a] == 5) { // 5
                highestTrunfoAux = cartasTrunfo[a];
            }else if (cartasTrunfo[a] == 4) { // 4
                highestTrunfoAux = cartasTrunfo[a];
            }else if (cartasTrunfo[a] == 3) { // 3
                highestTrunfoAux = cartasTrunfo[a];
            }else if (cartasTrunfo[a] == 2) { // 2
                highestTrunfoAux = cartasTrunfo[a];
            }
        }
        return highestTrunfoAux;
    }

    incrementaPontuacao(card) {

        let pont = 0;

        switch(card) {
            case "1":
                pont = 11;
                break;
            case "2":
            case "3":
            case "4":
            case "5":
            case "6":
                pont = 0;
                break;
            case "7":
                pont = 10;
                break;
            case "11":
                pont = 3;
                break;
            case "12":
                pont = 2;
                break;
            case "13":
                pont = 4;
                break;
        }
        return pont;

    }

    renunciaClick() {

    }
}
module.exports = BlackJackGame;
