<template>

    <div>


        <h1>Games Finished</h1>
        <h2>Total Players: {{ totalPlayers }}</h2>
        <h2>Total Games Played: {{ total_games_played }}</h2>
        <a class="btn btn-xs btn-warning" v-on:click.prevent="home()"> Home</a>


        <table class="table table-striped">
        <thead>
            <h2>TOP 5 Mais Pontos</h2>
            
            <tr>
                <th><strong>Nickname</strong></th>
                <th><strong>Total Points</strong></th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="player in players_by_points" :key="player.id">
            <td>{{player.nickname}}</td>
            <td>{{player.total_points}}</td>
        </tr>
        </tbody>
    </table>
       

    
    <table class="table table-striped">
        <thead>
            <h2>TOP 5 Mais Jogos</h2>
            <tr>
                <th><strong>Nickname</strong></th>
                <th><strong>Total Games</strong></th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="player in players_by_game" :key="player.id">
            <td>{{player.nickname}}</td>
            <td>{{player.total_games_played}}</td>
        </tr>
        </tbody>
    </table>
    
    <table class="table table-striped">
        <thead>
            <h2>TOP 5 Melhor média</h2>
            <tr>
                <th><strong>Nickname</strong></th>
                <th><strong>Points Per Game</strong></th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="player in players_by_avg" :key="player.id">
            <td>{{player.nickname}}</td>
            <td>{{player.avg}}</td>
        </tr>
        </tbody>
    </table>

    <div v-if="isUserLogged">
        <table class="table table-striped" >
        <thead>
            <h2>As minhas estatisticas !!! {{ logged_user.nickname}} !!!</h2>
            <tr>
                <th><strong>Total Games</strong></th>
                <th><strong>Total Points</strong></th>
                <th><strong>Total Draws/Victories</strong></th>
                <th><strong>Total Loses</strong></th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ logged_user.total_games_played }}</td>
            <td>{{ logged_user.total_points }}</td>
            <td>{{ totalVictoriesDraws }}</td>
            <td>{{ totalLoses }}</td>
        </tr>
        </tbody>
    </table>

    </div>
    
    </div>
</template>

<script type="text/javascript">
    export default {

        data: function(){
            return {
                players: {},
                totalPlayers: 0,
                total_games_played : 0,
                players_by_points: {},
                players_by_game: {},
                byAvg: [{
                        nickname : null,
                        avg : null,
                        }],
                players_by_avg: {},
                isUserLogged: false,
                logged_user : {},
                user_games : 0,
                totalVictoriesDraws: 0,
                totalLoses: 0,
            }
        },
        methods: {
            getPlayers: function () {
                axios.get('api/users')
                .then(response => {
                    if (response.data.data.length != 0) {
                        this.players = response.data.data;
                        this.totalPlayers = this.players.length;
                        this.totalGames();
                        this.orderByPoints();
                        this.orderByGames();
                        this.orderByAVG();
                        this.getLoggedUser();
                        this.getUserVictories();
                        // console.log(this.players);
                        console.log("hi im here");
                        this.totalLoses = parseInt(this.logged_user.total_games_played) - parseInt(this.totalVictoriesDraws);
                    }
                }).catch (error => {
                    console.log(error);
                });
            }, // end function

            totalGames: function () {
                for (var i = 0; i < this.players.length; i++) {
                    this.total_games_played += parseInt(this.players[i].total_games_played);

                }         
            }, // end function

            home: function () {
                this.$router.push('/');
            }, // end function

            orderByPoints: function () {
                var byPoints = this.players.slice(0);
                    byPoints.sort(function(a,b) {
                        return b.total_points - a.total_points;
                    });
                    this.players_by_points = byPoints.slice(0,5);

            }, // end function

            orderByGames: function () {
                var byGames = this.players.slice(0);
                    byGames.sort(function(a,b) {
                        return b.total_games_played - a.total_games_played;
                    });
                    this.players_by_game = byGames.slice(0,5);

            }, // end function

            orderByAVG: function () {
                var currentAvg = 0;

                for( let i = 0; i < this.players.length; i++) {
                    if (this.players[i].total_games_played != 0) {
                        if (this.players[i].total_points >= 0)
                            currentAvg = this.players[i].total_points / this.players[i].total_games_played;
                        else 
                            currentAvg = 0;
                    }
                    else {
                        currentAvg = 0;
                    }
                    this.byAvg.push({ "avg" : currentAvg, "nickname" : this.players[i].nickname });
                    currentAvg = 0;
                }

                var byAvgAux = this.byAvg.slice(0);

                byAvgAux.sort(function(a,b) {
                    return b.avg - a.avg;
                });
                this.players_by_avg = byAvgAux.slice(0,5);
            },// end function

            getLoggedUser: function () {
                let token = localStorage.getItem('token');
                console.log("get Logged User");
                axios.get('/api/user', { 
                            headers: {'Content-Type' : 'application/json', 'Authorization' : 'Bearer ' + token }
                      }).then(response => {
                            this.logged_user = response.data;
                            //console.log (this.logged_user.id);
                            this.isUserLogged = true;

                      }).catch(error => {
                        // não está autenticado
                        this.isUserLogged = false;
                        console.log(error);
                      });
            }, // end function

            getUserVictories: function () {
                axios.get('/api/games/victories', this.logged_user.id)
                    .then(response => {
                        this.totalVictoriesDraws = parseInt(response.data);
                        
                    }).then( response => {
                        console.log('vitocies + empates', this.totalVictoriesDraws);
                    });
            },// end function
        },

        components: {

            },

            mounted() {

                this.getPlayers();
            },
            created() {
                this.getLoggedUser();

            }
        }

</script>
