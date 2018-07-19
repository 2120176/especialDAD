<template>
    <div>
        
        <table class="table table-striped">
            
            <a class="btn btn-xs btn-warning" v-on:click.prevent="home()"> Home</a>
            <thead>
                <h1>Games Finished</h1>
                <h2> Total of Games = {{ totalGames }}</h2>
                <h2> Average Players Per Game = {{ players_per_game }}</h2>
                <h2> Total of Players = {{ totalPlayers }}</h2>
                <tr>
                    <th><strong>Status</strong></th>
                    <th><strong>Total Players</strong></th>
                    <th><strong>Created by</strong></th>
                    <th><strong>Winner</strong></th>
                    <th><strong>Created at</strong></th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="game in games" :key="game.id">
                <td>{{game.status}}</td>
                <td>{{game.total_players}}</td>
                <td>{{game.created_by}}</td>
                <td>{{game.winner}}</td>
                <td>{{game.created_at.date}}</td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script type="text/javascript">
    export default {

        data: function(){
            return {
                games: [],
                totalGames: 0,
                winner: [],
                players_per_game: 0,
                totalPlayers: 0,
                players: {},
            }
        },
        methods: {
            getGames: function(){
                axios.get('api/games')
                    .then(response=>{
                        this.games = response.data.data;
                        this.totalGames = this.games.length;
                        console.log("1");
                    }).then(response=>{
                        this.average();
                        this.getPlayers();
                        console.log("2");
                        
                    });
            },
            average: function () {
                if (this.games.length == 0) {
                    this.players_per_game = 0;
                } else {
                    for (var i = 0; i < this.games.length; i++) {
                        this.totalPlayers += parseInt(this.games[i].totalPlayers);
                    }
                    this.players_per_game = this.totalPlayers / this.games.length;
                        console.log("3");
                    
                }
                
            },
            getPlayers: function () {
                axios.get('api/users')
                .then(response => {
                    if (response.data.data.length != 0) {
                        this.players = response.data.data;
                        console.log(this.players);
                        console.log("4");
                        
                    }
                }).catch (error => {
                    console.log(error);
                });
            },

            home: function () {
                this.$router.push('/adminMasterPage');
            },

        },

        components: {

            },

            mounted() {
                this.getGames();
            }
        }

</script>
